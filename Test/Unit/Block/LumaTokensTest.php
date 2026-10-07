<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Block;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Design\ThemeInterface;
use Magento\Framework\View\DesignInterface;
use Panth\ThemeCustomizer\Block\LumaTokens;
use PHPUnit\Framework\TestCase;

class LumaTokensTest extends TestCase
{
    private function block(?string $themeCode, ?Repository $assetRepo = null): LumaTokens
    {
        $design = $this->createStub(DesignInterface::class);
        if ($themeCode === null) {
            $design->method('getDesignTheme')->willReturn(null);
        } else {
            $theme = $this->createStub(ThemeInterface::class);
            $theme->method('getCode')->willReturn($themeCode);
            $design->method('getDesignTheme')->willReturn($theme);
        }

        $block = (new \ReflectionClass(LumaTokens::class))->newInstanceWithoutConstructor();
        $property = new \ReflectionProperty(LumaTokens::class, '_design');
        $property->setValue($block, $design);
        if ($assetRepo !== null) {
            $repoProperty = new \ReflectionProperty(LumaTokens::class, '_assetRepo');
            $repoProperty->setValue($block, $assetRepo);
            $request = $this->createStub(RequestInterface::class);
            $request->method('isSecure')->willReturn(true);
            $requestProperty = new \ReflectionProperty(LumaTokens::class, '_request');
            $requestProperty->setValue($block, $request);
        }

        return $block;
    }

    private function render(LumaTokens $block): string
    {
        $method = new \ReflectionMethod(LumaTokens::class, '_toHtml');

        return (string) $method->invoke($block);
    }

    public function testLumaThemeIsDetected(): void
    {
        $this->assertTrue($this->block('Magento/luma')->isLumaTheme());
    }

    public function testHyvaThemeIsNotLuma(): void
    {
        $this->assertFalse($this->block('Panth/Infotech')->isLumaTheme());
        $this->assertFalse($this->block('Hyva/default')->isLumaTheme());
    }

    public function testOtherThemesAreNotLuma(): void
    {
        $this->assertFalse($this->block('Magento/blank')->isLumaTheme());
        $this->assertFalse($this->block('magento/luma')->isLumaTheme());
        $this->assertFalse($this->block('Vendor/luma-child')->isLumaTheme());
    }

    public function testMissingThemeIsNotLuma(): void
    {
        $this->assertFalse($this->block(null)->isLumaTheme());
    }

    public function testNothingIsRenderedOutsideLuma(): void
    {
        $this->assertSame('', $this->render($this->block('Panth/Infotech')));
        $this->assertSame('', $this->render($this->block('Magento/blank')));
        $this->assertSame('', $this->render($this->block(null)));
    }

    public function testStylesheetUrlUsesModuleAsset(): void
    {
        $repo = $this->createMock(Repository::class);
        $repo->expects($this->once())
            ->method('getUrlWithParams')
            ->with(LumaTokens::STYLESHEET, $this->callback(static fn ($params) => ($params['_secure'] ?? null) === true))
            ->willReturn('https://luma.test/static/frontend/Magento/luma/en_US/Panth_ThemeCustomizer/css/luma-tokens.css');

        $url = $this->block('Magento/luma', $repo)->getStylesheetUrl();

        $this->assertStringEndsWith('Panth_ThemeCustomizer/css/luma-tokens.css', $url);
    }

    public function testConstantsPointAtLumaAndModuleStylesheet(): void
    {
        $this->assertSame('Magento/luma', LumaTokens::THEME_CODE);
        $this->assertSame('Panth_ThemeCustomizer::css/luma-tokens.css', LumaTokens::STYLESHEET);
    }
}
