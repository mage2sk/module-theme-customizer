<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Model\Config\Source;

use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;
use Panth\ThemeCustomizer\Model\Config\Source\ContainerWidth;
use Panth\ThemeCustomizer\Model\Config\Source\CounterStyle;
use Panth\ThemeCustomizer\Model\Config\Source\GoogleFonts;
use Panth\ThemeCustomizer\Model\Config\Source\Shadow;
use PHPUnit\Framework\TestCase;

class SourceModelsTest extends TestCase
{
    private function values(array $options): array
    {
        return array_column($options, 'value');
    }

    public function testContainerWidthMatchesHeaderContainerClasses(): void
    {
        $this->assertSame(
            ['container', 'container-fluid', 'full'],
            $this->values((new ContainerWidth())->toOptionArray())
        );
    }

    public function testCounterStyleOptions(): void
    {
        $this->assertSame(['circle', 'pill', 'square'], $this->values((new CounterStyle())->toOptionArray()));
    }

    public function testShadowStartsWithNoneAndHasUniqueValues(): void
    {
        $values = $this->values((new Shadow())->toOptionArray());

        $this->assertSame('', $values[0]);
        $this->assertCount(8, $values);
        $this->assertSame($values, array_values(array_unique($values)));
        $this->assertContains('var(--shadow-md)', $values);
    }

    public function testEveryOptionHasALabel(): void
    {
        $sources = [new ContainerWidth(), new CounterStyle(), new Shadow(), new GoogleFonts()];
        foreach ($sources as $source) {
            foreach ($source->toOptionArray() as $option) {
                $this->assertArrayHasKey('label', $option);
                $this->assertNotSame('', (string)$option['label']);
            }
        }
    }

    public function testGoogleFontOptionsStartWithSystemDefaultAndEndWithSystemStack(): void
    {
        $values = $this->values((new GoogleFonts())->toOptionArray());

        $this->assertSame('', $values[0]);
        $this->assertSame('system-ui, -apple-system, sans-serif', end($values));
        $this->assertCount(count(GoogleFonts::GOOGLE_FAMILIES) + 2, $values);
    }

    public function testEveryGoogleFamilyHasAnOptionResolvingBackToIt(): void
    {
        $normalizer = new FontFamilyNormalizer();
        $families = [];
        foreach ($this->values((new GoogleFonts())->toOptionArray()) as $value) {
            if ($value !== '') {
                $families[] = $normalizer->extractFamilyName($value);
            }
        }

        foreach (GoogleFonts::GOOGLE_FAMILIES as $family) {
            $this->assertContains($family, $families);
        }
        $this->assertNotContains('system-ui', GoogleFonts::GOOGLE_FAMILIES);
    }
}
