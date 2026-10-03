<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Setup\Patch\Data;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;
use Panth\ThemeCustomizer\Setup\Patch\Data\NormalizeFontFamilyQuotes;
use PHPUnit\Framework\TestCase;

class NormalizeFontFamilyQuotesTest extends TestCase
{
    public function testOnlyRowsThatChangeAreUpdated(): void
    {
        $wheres = [];
        $select = $this->createStub(Select::class);
        $select->method('from')->willReturnSelf();
        $select->method('where')->willReturnCallback(function ($cond, $value = null) use ($select, &$wheres) {
            $wheres[] = [$cond, $value];
            return $select;
        });

        $updates = [];
        $connection = $this->createMock(AdapterInterface::class);
        $connection->method('select')->willReturn($select);
        $connection->expects($this->once())->method('fetchAll')->with($select)->willReturn([
            ['config_id' => '7', 'value' => '"Inter", sans-serif'],
            ['config_id' => '8', 'value' => "'Roboto', sans-serif"],
            ['config_id' => '9', 'value' => ' "Lato" '],
        ]);
        $connection->expects($this->exactly(2))
            ->method('update')
            ->willReturnCallback(function ($table, $bind, $where) use (&$updates) {
                $updates[] = [$table, $bind, $where];
                return 1;
            });

        $setup = $this->createStub(ModuleDataSetupInterface::class);
        $setup->method('getConnection')->willReturn($connection);
        $setup->method('getTable')->willReturnCallback(static fn($name) => 'pfx_' . $name);

        $patch = new NormalizeFontFamilyQuotes($setup, new FontFamilyNormalizer());

        $this->assertSame($patch, $patch->apply());
        $this->assertSame([
            ['pfx_core_config_data', ['value' => "'Inter', sans-serif"], ['config_id = ?' => 7]],
            ['pfx_core_config_data', ['value' => "'Lato'"], ['config_id = ?' => 9]],
        ], $updates);
        $this->assertSame(
            [
                ['path IN (?)', [
                    'theme_customizer/typography/font_family_base',
                    'theme_customizer/typography/font_family_heading',
                ]],
                ['value LIKE ?', '%"%'],
            ],
            $wheres
        );
    }

    public function testPatchHasNoDependenciesOrAliases(): void
    {
        $patch = new NormalizeFontFamilyQuotes(
            $this->createStub(ModuleDataSetupInterface::class),
            new FontFamilyNormalizer()
        );

        $this->assertSame([], NormalizeFontFamilyQuotes::getDependencies());
        $this->assertSame([], $patch->getAliases());
    }
}
