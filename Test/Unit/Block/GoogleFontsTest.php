<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Panth\ThemeCustomizer\Block\GoogleFonts;
use Panth\ThemeCustomizer\Helper\Data;
use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;
use PHPUnit\Framework\TestCase;

class GoogleFontsTest extends TestCase
{
    private function block(bool $load, ?string $base, ?string $heading): GoogleFonts
    {
        $values = [
            'theme_customizer/typography/load_google_fonts' => $load ? '1' : '0',
            'theme_customizer/typography/font_family_base' => $base,
            'theme_customizer/typography/font_family_heading' => $heading,
        ];
        $scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(static fn($path) => $values[$path] ?? null);
        $context = $this->createStub(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);
        $helper = new Data($context, new FontFamilyNormalizer());

        $block = (new \ReflectionClass(GoogleFonts::class))->newInstanceWithoutConstructor();
        $property = new \ReflectionProperty(GoogleFonts::class, 'themeHelper');
        $property->setValue($block, $helper);
        return $block;
    }

    public function testNothingLoadsWhenDisabled(): void
    {
        $block = $this->block(false, "'Inter', sans-serif", "'Roboto', sans-serif");

        $this->assertFalse($block->isLoadEnabled());
        $this->assertSame([], $block->getFontsToLoad());
        $this->assertNull($block->getGoogleFontsUrl());
    }

    public function testBaseAndHeadingFontsAreCollected(): void
    {
        $block = $this->block(true, "'Open Sans', sans-serif", '"Playfair Display", serif');

        $this->assertSame(['Open Sans', 'Playfair Display'], $block->getFontsToLoad());
        $this->assertSame(
            'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800'
            . '&family=Playfair+Display:wght@300;400;500;600;700;800&display=swap',
            $block->getGoogleFontsUrl()
        );
    }

    public function testSameFontIsOnlyRequestedOnce(): void
    {
        $block = $this->block(true, "'Inter', sans-serif", '"Inter", sans-serif');

        $this->assertSame(['Inter'], $block->getFontsToLoad());
    }

    public function testNonGoogleFontsAreIgnored(): void
    {
        $block = $this->block(true, 'system-ui, -apple-system, sans-serif', "'Comic Sans MS', cursive");

        $this->assertSame([], $block->getFontsToLoad());
        $this->assertNull($block->getGoogleFontsUrl());
    }

    public function testEmptyFamiliesAreSkipped(): void
    {
        $block = $this->block(true, null, "'Lato', sans-serif");

        $this->assertSame(['Lato'], $block->getFontsToLoad());
    }

    public function testFamilyMatchingIsCaseSensitive(): void
    {
        $block = $this->block(true, "'inter', sans-serif", null);

        $this->assertSame([], $block->getFontsToLoad());
    }
}
