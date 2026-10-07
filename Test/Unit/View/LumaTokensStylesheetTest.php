<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\View;

use PHPUnit\Framework\TestCase;

class LumaTokensStylesheetTest extends TestCase
{
    private const STYLESHEET = 'view/frontend/web/css/luma-tokens.css';

    private string $css = '';

    protected function setUp(): void
    {
        $path = dirname(__DIR__, 3) . '/' . self::STYLESHEET;
        $this->assertTrue(is_file($path), self::STYLESHEET . ' is missing');
        $this->css = (string) file_get_contents($path);
    }

    private function ruleBody(string $selector): string
    {
        $start = strpos($this->css, $selector);
        $this->assertNotFalse($start, 'Selector not found: ' . $selector);
        $open = strpos($this->css, '{', (int) $start);
        $close = strpos($this->css, '}', (int) $open);

        return substr($this->css, (int) $open + 1, (int) $close - (int) $open - 1);
    }

    public function testStylesheetIsAsciiWithUnixLineEndings(): void
    {
        $this->assertSame(1, preg_match('/^[\x09\x0A\x20-\x7E]*$/', $this->css));
        $this->assertStringNotContainsString("\r", $this->css);
    }

    public function testBracesAreBalanced(): void
    {
        $this->assertSame(substr_count($this->css, '{'), substr_count($this->css, '}'));
    }

    public function testTopMenuListKeepsItsFirstPaintStyleAfterTheMenuWidgetLoads(): void
    {
        $body = $this->ruleBody(':root body .navigation > ul.ui-widget-content {');

        $this->assertStringContainsString('background: transparent !important;', $body);
        $this->assertStringContainsString('border: 0 !important;', $body);
        $this->assertStringContainsString('border-radius: 0 !important;', $body);
        $this->assertStringContainsString('box-shadow: none !important;', $body);
        $this->assertStringContainsString(':root body .navigation > ul.ui-menu,', $this->css);
    }

    public function testTopMenuChevronIsDrawnByCssAndTheScriptIconIsHidden(): void
    {
        $chevron = $this->ruleBody(':root body .navigation .level0.parent > .level-top::after {');
        $this->assertStringContainsString('content: "\e622";', $chevron);
        $this->assertStringContainsString('position: absolute;', $chevron);

        $icon = $this->ruleBody(':root body .navigation .level0.parent > .level-top > .ui-menu-icon {');
        $this->assertStringContainsString('display: none !important;', $icon);
    }

    public function testStoreSwitcherStripIsNeutralAndAtMost32PixelsTall(): void
    {
        $wrapper = $this->ruleBody(':root body .page-header .panel.wrapper {');
        $this->assertStringContainsString('background: var(--pt-page) !important;', $wrapper);
        $this->assertStringContainsString('border-bottom: 0 !important;', $wrapper);
        $this->assertStringContainsString('inset 0 -1px 0 var(--pt-border)', $wrapper);

        $panel = $this->ruleBody(':root body .page-header .panel.header {');
        $this->assertStringContainsString('max-height: 32px;', $panel);
        $this->assertStringContainsString('font-size: var(--pt-caption) !important;', $panel);
        $this->assertStringContainsString('padding-top: 0 !important;', $panel);
    }

    public function testProductTabsWrapInsideTheContainerFromTabletUp(): void
    {
        $body = $this->ruleBody(':root body .panth-tabs__nav {' . "\n" . '        flex-wrap');

        $this->assertStringContainsString('flex-wrap: wrap !important;', $body);
        $this->assertStringContainsString('overflow-x: visible !important;', $body);
        $this->assertStringContainsString("@media (min-width: 768px) {\n    :root body .panth-tabs__nav {", $this->css);
    }

    public function testSwatchAreaAndProductBreadcrumbsReserveTheirSpaceBeforeScriptsRun(): void
    {
        $swatch = $this->ruleBody(':root body .product-info-main .swatch-opt:empty {');
        $this->assertStringContainsString('min-height: 142px;', $swatch);

        $crumbs = $this->ruleBody(':root body.catalog-product-view .breadcrumbs {');
        $this->assertStringContainsString('min-height: 46px;', $crumbs);

        $button = $this->ruleBody(':root body #product-addtocart-button:disabled {');
        $this->assertStringContainsString('cursor: progress;', $button);
    }

    public function testEveryRadiusUsesTheApprovedScale(): void
    {
        preg_match_all('/border-radius:\s*([^;!]+)/', $this->css, $matches);
        $allowed = [
            '0',
            'var(--pt-radius-sm)',
            'var(--pt-radius-md)',
            'var(--pt-radius-lg)',
            'var(--pt-radius-full)',
        ];

        $this->assertNotEmpty($matches[1]);
        foreach ($matches[1] as $value) {
            $this->assertContains(trim($value), $allowed, 'Off-scale radius: ' . $value);
        }
    }

    public function testThumbnailsQuantityInputsAndButtonsUseTheTokens(): void
    {
        $radius = $this->ruleBody(':root body .cart-container .input-text.qty {');
        $this->assertStringContainsString('border-radius: var(--pt-radius-md) !important;', $radius);
        $this->assertStringContainsString(':root body .panth-gallery__thumb,', $this->css);

        $font = $this->ruleBody(':root body .cart-container .action.primary {');
        $this->assertStringContainsString('font-size: 15px !important;', $font);

        $bootstrap = $this->ruleBody(':root body .btn.btn-primary {');
        $this->assertStringContainsString('font-size: 15px !important;', $bootstrap);
    }

    public function testFieldSelectsAreSixteenPixelsToStopMobileZoom(): void
    {
        $body = $this->ruleBody(".field select,\n.control select,\n.cart-summary select,\n.sorter select,\n.limiter select {");

        $this->assertStringContainsString('font-size: 16px !important;', $body);
    }
}
