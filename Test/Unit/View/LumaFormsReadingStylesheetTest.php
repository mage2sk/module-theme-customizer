<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\View;

use PHPUnit\Framework\TestCase;

class LumaFormsReadingStylesheetTest extends TestCase
{
    private const STYLESHEET = 'view/frontend/web/css/luma-tokens.css';

    private string $css = '';

    protected function setUp(): void
    {
        $path = dirname(__DIR__, 3) . '/' . self::STYLESHEET;
        $this->assertTrue(is_file($path), self::STYLESHEET . ' is missing');
        $this->css = (string) file_get_contents($path);
    }

    private function lastRuleBody(string $selector): string
    {
        $start = strrpos($this->css, $selector);
        $this->assertNotFalse($start, 'Selector not found: ' . $selector);
        $open = strpos($this->css, '{', (int) $start);
        $close = strpos($this->css, '}', (int) $open);

        return substr($this->css, (int) $open + 1, (int) $close - (int) $open - 1);
    }

    public function testFieldSpacingIsSixteenAndGroupsTwentyFourOutsideCheckout(): void
    {
        $field = $this->lastRuleBody('body:not(.checkout-index-index) form .fieldset > .fields > .field {');
        $this->assertStringContainsString('margin-bottom: 16px;', $field);

        $group = $this->lastRuleBody('body:not(.checkout-index-index) form .fieldset:not(:last-child) {');
        $this->assertStringContainsString('margin-bottom: 24px;', $group);
    }

    public function testCaptchaReloadUsesTheSecondaryButtonStyle(): void
    {
        $body = $this->lastRuleBody(':root body .captcha-reload {');

        $this->assertStringContainsString('background: var(--pt-surface) !important;', $body);
        $this->assertStringContainsString('border: 1px solid var(--pt-border-strong) !important;', $body);
        $this->assertStringContainsString('border-radius: var(--pt-radius-md) !important;', $body);
        $this->assertStringContainsString('font-size: 15px !important;', $body);
        $this->assertStringContainsString('font-weight: 600 !important;', $body);
        $this->assertStringContainsString('min-height: var(--pt-btn-height) !important;', $body);
    }

    public function testFooterNewsletterInputIsAWhiteInput(): void
    {
        $body = $this->lastRuleBody(':root body .panth-newsletter input[type="email"] {');

        $this->assertStringContainsString('background-color: var(--pt-surface) !important;', $body);
        $this->assertStringContainsString('border: 1px solid var(--pt-border-strong) !important;', $body);
        $this->assertStringContainsString('color: var(--pt-text) !important;', $body);
    }

    public function testCmsLeadParagraphStaysInsideTheTextColumn(): void
    {
        $body = $this->lastRuleBody(':root body.cms-page-view .column.main .cms-content-important {');

        $this->assertStringContainsString('margin: 0 0 24px;', $body);
        $this->assertStringContainsString('font-size: 18px;', $body);
        $this->assertStringContainsString('line-height: 1.5;', $body);
        $this->assertStringNotContainsString('-20px', $body);
    }

    public function testCmsReadingRhythmImagesAndTables(): void
    {
        $text = $this->lastRuleBody('.cms-page-view .column.main :is(p, li) {');
        $this->assertStringContainsString('line-height: 1.5;', $text);

        $h2 = $this->lastRuleBody('.cms-page-view:not(.cms-index-index) .column.main h2 {');
        $this->assertStringContainsString('margin-top: 40px;', $h2);

        $media = $this->lastRuleBody('.cms-page-view:not(.cms-index-index) .column.main :is(img, video, iframe) {');
        $this->assertStringContainsString('max-width: 100%;', $media);

        $table = $this->lastRuleBody(
            '.cms-page-view:not(.cms-index-index) .column.main table:not(.table-wrapper table) {'
        );
        $this->assertStringContainsString('overflow-x: auto;', $table);
    }

    public function testStylesheetStaysValid(): void
    {
        $this->assertSame(substr_count($this->css, '{'), substr_count($this->css, '}'));
        $this->assertSame(1, preg_match('/^[\x09\x0A\x20-\x7E]*$/', $this->css));
    }
}
