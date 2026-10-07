<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\View;

use PHPUnit\Framework\TestCase;

class LumaHomeCheckoutStylesheetTest extends TestCase
{
    private const STYLESHEET = 'view/frontend/web/css/luma-tokens.css';

    private string $css = '';

    protected function setUp(): void
    {
        $path = dirname(__DIR__, 3) . '/' . self::STYLESHEET;
        $this->assertTrue(is_file($path), self::STYLESHEET . ' is missing');
        $this->css = (string) file_get_contents($path);
    }

    private function rule(string $selector): string
    {
        $start = strpos($this->css, $selector . ' {');
        $this->assertNotFalse($start, 'Selector not found: ' . $selector);
        $open = (int) $start + strlen($selector) + 2;
        $close = strpos($this->css, '}', $open);
        $this->assertNotFalse($close);

        return substr($this->css, $open, (int) $close - $open);
    }

    public function testHomeTilesUseThePaletteInsteadOfTailwindGreys(): void
    {
        $tile = $this->rule(':root body .pds-tile');
        $this->assertStringContainsString('background-color: var(--pt-surface) !important;', $tile);
        $this->assertStringContainsString('border: 1px solid var(--pt-border) !important;', $tile);
        $this->assertStringContainsString(
            'color: var(--pt-text) !important;',
            $this->rule(':root body .pds-tile .pds-copy strong')
        );
        $this->assertStringContainsString(
            'color: var(--pt-muted) !important;',
            $this->rule(':root body :is(.pds-tile .pds-copy span, .pds-heading p, .tcard-role)')
        );
        $this->assertStringContainsString(
            'color: var(--pt-text) !important;',
            $this->rule(':root body .crosslink-test-block')
        );
    }

    public function testHomeProductSliderUsesThePalette(): void
    {
        $this->assertStringContainsString(
            'background-color: var(--pt-page) !important;',
            $this->rule(':root body .panth-product-slider .product-card-image')
        );
        $this->assertStringContainsString(
            'color: var(--pt-danger) !important;',
            $this->rule(':root body .panth-product-slider .product-card-price .special-price')
        );
        $bullet = $this->rule(
            ':root body .panth-product-slider .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)'
        );
        $this->assertStringContainsString('background: var(--pt-border-strong) !important;', $bullet);
        $this->assertStringContainsString('opacity: 1 !important;', $bullet);
    }

    public function testCheckoutVariablesPointAtThePalette(): void
    {
        $vars = $this->rule(':root body.panth-checkout-extended');
        $this->assertStringContainsString('--panth-co-faint: var(--pt-muted);', $vars);
        $this->assertStringContainsString('--panth-co-surface: var(--pt-page);', $vars);
        $this->assertStringContainsString('--panth-co-danger: var(--pt-danger);', $vars);
    }

    public function testCheckoutFieldsUseTheInputBorderWithFocusAndErrorStates(): void
    {
        $fields = ':root body.panth-checkout-extended :is(.checkout-container, .modal-popup) '
            . ':is(input[type="text"], input[type="email"], input[type="tel"], input[type="number"], '
            . 'input[type="password"], select, textarea)';
        $this->assertStringContainsString('border-color: var(--pt-border-strong) !important;', $this->rule($fields));
        $this->assertStringContainsString('border-color: var(--pt-brand) !important;', $this->rule($fields . ':focus'));
        $error = $this->rule(
            ':root body.panth-checkout-extended :is(.checkout-container, .modal-popup) '
            . ':is(.field._error input, .field._error select, .field._error textarea, '
            . 'input.mage-error, select.mage-error, textarea.mage-error)'
        );
        $this->assertStringContainsString('border-color: var(--pt-danger) !important;', $error);
        $this->assertGreaterThan(
            strpos($this->css, $fields . ':focus {'),
            strpos($this->css, '.field._error input, .field._error select')
        );
    }

    public function testCheckoutSummaryAndDiscountFollowTheSpec(): void
    {
        $this->assertStringContainsString(
            'border-radius: var(--pt-radius-md) !important;',
            $this->rule(':root body.panth-checkout-extended .panth-options-list')
        );
        $this->assertStringContainsString(
            'color: var(--pt-text) !important;',
            $this->rule(':root body.panth-checkout-extended .opc-block-summary .table-totals :is(.mark, .mark .label)')
        );
        $apply = $this->rule(':root body.panth-checkout-extended .form-discount .action-apply');
        $this->assertStringContainsString('font-size: 15px !important;', $apply);
        $this->assertStringContainsString('font-weight: 600 !important;', $apply);
        $this->assertStringContainsString('color: var(--pt-muted) !important;', $this->rule(':root body .panth-item-sku'));
    }

    public function testFooterNewsletterColourNoLongerHidesTheCheckoutNewsletterLabel(): void
    {
        $this->assertStringNotContainsString(':root body :is(.panth-newsletter-text, .panth-footer-text) {', $this->css);
        $this->assertStringContainsString(
            'color: var(--pt-border) !important;',
            $this->rule(':root body .page-footer :is(.panth-newsletter-text, .panth-footer-text)')
        );
        $this->assertStringContainsString(
            'color: var(--pt-text) !important;',
            $this->rule(':root body .checkout-container .panth-newsletter-text')
        );
    }

    public function testSuccessPageBlocksUseCardAndButtonTokens(): void
    {
        $block = $this->rule(':root body .panth-success-info-block');
        $this->assertStringContainsString('background-color: var(--pt-surface) !important;', $block);
        $this->assertStringContainsString('border: 1px solid var(--pt-border) !important;', $block);
        $this->assertStringContainsString('border-radius: var(--pt-radius-lg) !important;', $block);
        $this->assertStringContainsString(
            'color: var(--pt-text) !important;',
            $this->rule(':root body .panth-success-info-block h3')
        );
        $this->assertStringContainsString(
            'color: var(--pt-muted) !important;',
            $this->rule(':root body .panth-success-info-block p')
        );
        $this->assertStringContainsString(
            'border-radius: var(--pt-radius-md) !important;',
            $this->rule(':root body .panth-continue-btn')
        );
    }

    public function testCartEditAndDeleteIconsDoNotOverlap(): void
    {
        $this->assertStringContainsString(
            'min-height: var(--pt-icon-size);',
            $this->rule(':root body .cart.item .actions-toolbar')
        );
        $edit = $this->rule(':root body .cart.item .actions-toolbar > .action-edit');
        $this->assertStringContainsString('min-width: var(--pt-icon-size) !important;', $edit);
        $this->assertStringContainsString('min-height: var(--pt-icon-size) !important;', $edit);
        $this->assertStringContainsString('right: calc(var(--pt-icon-size) + 4px) !important;', $edit);
    }

    public function testStylesheetStaysAsciiAndBalanced(): void
    {
        $this->assertSame(1, preg_match('/^[\x09\x0A\x20-\x7E]*$/', $this->css));
        $this->assertSame(substr_count($this->css, '{'), substr_count($this->css, '}'));
    }
}
