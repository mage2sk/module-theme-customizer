<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Model\Config;

use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FontFamilyNormalizerTest extends TestCase
{
    /**
     * @var FontFamilyNormalizer
     */
    private FontFamilyNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new FontFamilyNormalizer();
    }

    public static function normalizeCases(): array
    {
        return [
            'null becomes empty' => [null, ''],
            'blank becomes empty' => ["   \t ", ''],
            'double quotes become single' => ['"Open Sans", sans-serif', "'Open Sans', sans-serif"],
            'single quotes untouched' => ["'Inter', sans-serif", "'Inter', sans-serif"],
            'surrounding whitespace trimmed' => ['  system-ui, sans-serif  ', 'system-ui, sans-serif'],
            'mixed quotes' => ["\"Roboto\", 'Lato'", "'Roboto', 'Lato'"],
        ];
    }

    #[DataProvider('normalizeCases')]
    public function testNormalize($input, string $expected): void
    {
        $this->assertSame($expected, $this->normalizer->normalize($input));
    }

    public static function familyCases(): array
    {
        return [
            'quoted first family' => ["'Open Sans', sans-serif", 'Open Sans'],
            'double quoted is normalized first' => ['"Playfair Display", serif', 'Playfair Display'],
            'quoted family with inner spaces trimmed' => ["' Inter ', sans-serif", 'Inter'],
            'unquoted list uses first entry' => ['system-ui, -apple-system, sans-serif', 'system-ui'],
            'single unquoted name' => ['Roboto', 'Roboto'],
            'empty' => ['', ''],
            'null' => [null, ''],
        ];
    }

    #[DataProvider('familyCases')]
    public function testExtractFamilyName($input, string $expected): void
    {
        $this->assertSame($expected, $this->normalizer->extractFamilyName($input));
    }
}
