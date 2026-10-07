<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\View;

use PHPUnit\Framework\TestCase;

class LumaComponentFamiliesStylesheetTest extends TestCase
{
    private const STYLESHEET = 'view/frontend/web/css/luma-tokens.css';

    private string $css = '';

    protected function setUp(): void
    {
        $path = dirname(__DIR__, 3) . '/' . self::STYLESHEET;
        $this->assertTrue(is_file($path), self::STYLESHEET . ' is missing');
        $this->css = (string) file_get_contents($path);
    }

    private function ruleBody(string $selectorStart): string
    {
        $start = strpos($this->css, $selectorStart);
        $this->assertNotFalse($start, 'Selector not found: ' . $selectorStart);
        $open = strpos($this->css, ') {', (int) $start);
        $this->assertNotFalse($open);
        $close = strpos($this->css, '}', (int) $open);

        return substr($this->css, (int) $open + 3, (int) $close - (int) $open - 3);
    }

    private function selectorList(string $selectorStart): string
    {
        $start = strpos($this->css, $selectorStart);
        $this->assertNotFalse($start, 'Selector not found: ' . $selectorStart);
        $open = strpos($this->css, ') {', (int) $start);

        return substr($this->css, (int) $start, (int) $open - (int) $start);
    }

    public function testStylesheetStaysAsciiAndBalanced(): void
    {
        $this->assertSame(1, preg_match('/^[\x09\x0A\x20-\x7E]*$/', $this->css));
        $this->assertSame(substr_count($this->css, '{'), substr_count($this->css, '}'));
    }

    public function testPrimaryFamilyCoversModuleButtonsWithOneStyle(): void
    {
        $selectors = $this->selectorList(":root body :is(\n    .action.primary,");
        foreach (['#panth-newsletter-btn', '.panth-cf-submit', '.pb-subscribe-form__btn', '.faq-search .action.search', '.faq-filters .filter-option.active', '.panth-404 .panth-404-search button', '.panth-404-btn-p'] as $selector) {
            $this->assertStringContainsString($selector, $selectors);
        }

        $body = $this->ruleBody(":root body :is(\n    .action.primary,");
        $this->assertStringContainsString('background: var(--pt-brand) !important;', $body);
        $this->assertStringContainsString('border: 1px solid var(--pt-brand) !important;', $body);
        $this->assertStringContainsString('border-radius: var(--pt-radius-md) !important;', $body);
        $this->assertStringContainsString('font-size: 15px !important;', $body);
        $this->assertStringContainsString('font-weight: 600 !important;', $body);
        $this->assertStringContainsString('height: var(--pt-btn-height) !important;', $body);
    }

    public function testSecondaryFamilyIsWhiteWithTheStrongBorder(): void
    {
        $selectors = $this->selectorList(":root body :is(\n    .action.secondary,");
        foreach (['.cart.main.actions .action', '#empty_cart_button', '.qv-action-btn', '.pb-share__btn', '.panth-404-btn-s', '.panth-tabs__btn.panth-tabs__btn--active'] as $selector) {
            $this->assertStringContainsString($selector, $selectors);
        }

        $body = $this->ruleBody(":root body :is(\n    .action.secondary,");
        $this->assertStringContainsString('background: var(--pt-surface) !important;', $body);
        $this->assertStringContainsString('border: 1px solid var(--pt-border-strong) !important;', $body);
        $this->assertStringContainsString('color: var(--pt-text) !important;', $body);
        $this->assertStringContainsString('height: var(--pt-btn-height) !important;', $body);
    }

    public function testTextLinkFamilyIsTealWithA44PixelTapTarget(): void
    {
        $selectors = $this->selectorList(":root body :is(\n    a.action:not(.primary)");
        $this->assertStringContainsString(':not(.skip)', $selectors);
        $this->assertStringContainsString(':not(.nav-toggle)', $selectors);
        $this->assertStringContainsString('.pa-trigger', $selectors);

        $body = $this->ruleBody(":root body :is(\n    a.action:not(.primary)");
        $this->assertStringContainsString('background: transparent !important;', $body);
        $this->assertStringContainsString('border: 0 !important;', $body);
        $this->assertStringContainsString('color: var(--pt-link) !important;', $body);
        $this->assertStringContainsString('min-height: var(--pt-icon-size) !important;', $body);
        $this->assertStringContainsString('--pt-link: #0F766E;', $this->css);
    }

    public function testIconButtonsAre44PixelsWithAnEightPixelRadius(): void
    {
        $selectors = $this->selectorList(":root body :is(\n    .panth-nbar-close,");
        foreach (['.panth-icon', '.action.sorter-action', '.panth-gallery__arrow', '.panth-ac-qty-btn', '.pages .action.next', '.panth-back-to-top'] as $selector) {
            $this->assertStringContainsString($selector, $selectors);
        }

        $body = $this->ruleBody(":root body :is(\n    .panth-nbar-close,");
        $this->assertStringContainsString('height: var(--pt-icon-size) !important;', $body);
        $this->assertStringContainsString('min-width: var(--pt-icon-size) !important;', $body);
        $this->assertStringContainsString('border-radius: var(--pt-radius-md) !important;', $body);
        $this->assertStringContainsString('--pt-icon-size: 44px;', $this->css);
    }

    public function testSearchInputsUseTheStandardField(): void
    {
        $selectors = $this->selectorList(":root body :is(\n    .toolbar select,");
        foreach (['#faq-search-input', '.pb-search-form input', '.panth-404 .panth-404-search input'] as $selector) {
            $this->assertStringContainsString($selector, $selectors);
        }

        $body = $this->ruleBody(":root body :is(\n    .toolbar select,");
        $this->assertStringContainsString('border: 1px solid var(--pt-border-strong) !important;', $body);
        $this->assertStringContainsString('font-size: 16px !important;', $body);
        $this->assertStringContainsString('height: var(--pt-input-height) !important;', $body);
    }

    public function testCartQuantityIsSixteenPixelsAnd44PixelsTall(): void
    {
        $start = strpos($this->css, ':root body .cart.item input.qty,');
        $this->assertNotFalse($start);
        $body = substr($this->css, (int) $start, (int) strpos($this->css, '}', (int) $start) - (int) $start);
        $this->assertStringContainsString('font-size: 16px !important;', $body);
        $this->assertStringContainsString('height: var(--pt-icon-size) !important;', $body);
        $this->assertStringContainsString('border: 1px solid var(--pt-border-strong) !important;', $body);
    }

    public function testPagerAnd404ChipsUseTheRadiusSet(): void
    {
        $this->assertStringContainsString(":root body .pages :is(a.page, strong.page) {\n    border: 1px solid var(--pt-border-strong) !important;\n    border-radius: var(--pt-radius-md) !important;", $this->css);
        $this->assertStringContainsString(":root body .panth-404-cat {\n    border-radius: var(--pt-radius-md) !important;", $this->css);
        $this->assertStringContainsString(":root body .pb-author-card {\n    border-radius: var(--pt-radius-lg) !important;", $this->css);
        $this->assertStringContainsString(":root body .panth-404-search {\n    align-items: center !important;\n    background: transparent !important;\n    border: 0 !important;", $this->css);
    }

    public function testRunningTextIsLimitedTo72Characters(): void
    {
        $this->assertStringContainsString('.tcard-quote, .category-description p, .product.attribute.description p, .panth-tabs__panel p, .widget.block-static-block p, [data-content-type="text"] p) {' . "\n" . '    max-width: min(72ch, var(--pt-text-page));', $this->css);
    }

    public function testCartHeadingsAndFooterTextUseThePalette(): void
    {
        $this->assertStringContainsString('#block-shipping-heading, #block-discount-heading),', $this->css);
        $this->assertStringContainsString(":root body .panth-newsletter-title {\n    color: #FFFFFF !important;", $this->css);
        $this->assertStringContainsString(":root body .page-footer :is(.panth-newsletter-text, .panth-footer-text) {\n    color: var(--pt-border) !important;", $this->css);
        $this->assertStringNotContainsString('#006BB4', strtoupper(substr($this->css, (int) strpos($this->css, '--pt-icon-size: 44px;'))));
    }

    public function testFaqSearchBorderBeatsTheModuleRule(): void
    {
        $this->assertStringContainsString(
            ":root body .panth-faq-module .faq-search #faq-search-input {\n    border: 1px solid var(--pt-border-strong) !important;",
            $this->css
        );
    }

    public function testCartNotesAndTableHeadersUseTheTextColours(): void
    {
        $this->assertStringContainsString(
            ":root body .cart-container :is(.panth-ac-note__range, .panth-estimated-delivery span) {\n    color: var(--pt-text) !important;",
            $this->css
        );
        $this->assertStringContainsString(":root body #shopping-cart-table th.col span {\n    color: var(--pt-muted) !important;", $this->css);
        $this->assertStringContainsString(":root body .nav-sections .navigation {\n    background: var(--pt-page) !important;", $this->css);
    }
}
