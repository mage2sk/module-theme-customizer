<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;

class NormalizeFontFamilyQuotes implements DataPatchInterface
{
    private const PATHS = [
        'theme_customizer/typography/font_family_base',
        'theme_customizer/typography/font_family_heading',
    ];

    private ModuleDataSetupInterface $moduleDataSetup;

    private FontFamilyNormalizer $normalizer;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        FontFamilyNormalizer $normalizer
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->normalizer = $normalizer;
    }

    public function apply()
    {
        $connection = $this->moduleDataSetup->getConnection();
        $table = $this->moduleDataSetup->getTable('core_config_data');

        $select = $connection->select()
            ->from($table, ['config_id', 'value'])
            ->where('path IN (?)', self::PATHS)
            ->where('value LIKE ?', '%"%');

        foreach ($connection->fetchAll($select) as $row) {
            $value = (string) $row['value'];
            $normalized = $this->normalizer->normalize($value);
            if ($normalized === $value) {
                continue;
            }
            $connection->update(
                $table,
                ['value' => $normalized],
                ['config_id = ?' => (int) $row['config_id']]
            );
        }

        return $this;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
