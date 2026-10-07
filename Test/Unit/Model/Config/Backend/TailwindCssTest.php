<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeList;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Panth\ThemeCustomizer\Model\Config\Backend\TailwindCss;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TailwindCssTest extends TestCase
{
    private function context(): Context
    {
        $context = $this->createStub(Context::class);
        $context->method('getEventDispatcher')->willReturn($this->createStub(ManagerInterface::class));
        return $context;
    }

    private function model($value, ?TypeList $typeList = null): TailwindCss
    {
        $model = new TailwindCss(
            $this->context(),
            $this->createStub(Registry::class),
            $this->createStub(ScopeConfigInterface::class),
            $typeList ?? $this->createStub(TypeList::class)
        );
        $model->setValue($value);
        $model->setPath('theme_customizer/custom_css/custom_tailwind_css');
        return $model;
    }

    public static function validCss(): array
    {
        return [
            'empty string skips validation' => [''],
            'null skips validation' => [null],
            'simple rule' => ['.btn { color: red; }'],
            'theme block' => ['@theme { --color-primary: #3b82f6; }'],
            'utility block' => ['@utility tab-4 { tab-size: 4; }'],
            'function call with parentheses' => ['.a { background: rgb(1, 2, 3); }'],
            'plain declaration without braces' => ['color: red;'],
            'several rules with pseudo selector' => ['.a { color: red; } .b:hover { color: blue; margin: 0; }'],
            'media query' => ['@media (min-width: 768px) { .a { color: red; } }'],
        ];
    }

    #[DataProvider('validCss')]
    public function testValidCssIsAccepted($css): void
    {
        $model = $this->model($css);

        $this->assertSame($model, $model->beforeSave());
        $this->assertSame($css, $model->getValue());
    }

    public static function invalidCss(): array
    {
        return [
            'unbalanced braces' => [
                '.a { color: red;',
                'Invalid CSS: Unbalanced braces. Found 1 opening and 0 closing braces.',
            ],
            'unbalanced parentheses' => [
                '.a { background: rgb(1, 2, 3; }',
                'Invalid CSS: Unbalanced parentheses. Found 1 opening and 0 closing parentheses.',
            ],
            'import directive' => [
                '@import "foo.css";',
                'Directive @import is not allowed. Use the Tailwind build system instead.',
            ],
            'charset directive case insensitive' => [
                '@CHARSET "utf-8";',
                'Directive @charset is not allowed. Use the Tailwind build system instead.',
            ],
            'namespace directive' => [
                '@namespace svg url(x);',
                'Directive @namespace is not allowed. Use the Tailwind build system instead.',
            ],
            'block without properties' => [
                '.a { }',
                'Invalid CSS: No valid CSS properties found. Expected format: property: value;',
            ],
            'consecutive semicolons without declarations' => [
                '.a;;',
                'Invalid CSS: Multiple consecutive semicolons found.',
            ],
            'missing semicolon' => [
                '.a { color: red }',
                'Invalid CSS: Missing semicolon after CSS property value.',
            ],
            'missing semicolon after an earlier valid declaration' => [
                '.a { color: red; } .b { color: blue }',
                'Invalid CSS: Missing semicolon after CSS property value.',
            ],
            'consecutive semicolons after an earlier valid declaration' => [
                '.a { color: red;; }',
                'Invalid CSS: Multiple consecutive semicolons found.',
            ],
            'script tag' => [
                '<script>alert(1)</script>',
                'Security violation: Script tags are not allowed in CSS.',
            ],
            'style tag' => [
                '</style>',
                'Security violation: Style tags are not allowed in CSS.',
            ],
            'javascript url' => [
                '.a { background: url(javascript:alert(1)); }',
                'Security violation: JavaScript URLs are not allowed in CSS.',
            ],
            'iframe' => [
                '<iframe src=x>',
                'Security violation: IFrame tags are not allowed in CSS.',
            ],
            'event handler' => [
                'x onerror=alert(1)',
                'Security violation: Event handlers are not allowed in CSS.',
            ],
            'eval' => [
                '.a { width: eval (1); }',
                'Security violation: Eval functions are not allowed in CSS.',
            ],
            'css expression' => [
                '.a { width: expression(document.body.clientWidth); }',
                'Security violation: CSS expressions are not allowed in CSS.',
            ],
        ];
    }

    #[DataProvider('invalidCss')]
    public function testInvalidCssIsRejected(string $css, string $message): void
    {
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage($message);

        $this->model($css)->beforeSave();
    }

    public function testAfterSaveInvalidatesFrontendAndConfigCaches(): void
    {
        $invalidated = [];
        $typeList = $this->createMock(TypeList::class);
        $typeList->expects($this->exactly(4))
            ->method('invalidate')
            ->willReturnCallback(function ($type) use (&$invalidated) {
                $invalidated[] = $type;
            });

        $model = $this->model('.a { color: red; }', $typeList);
        $this->assertSame($model, $model->afterSave());
        // The parent config value model invalidates the config cache last because the value changed.
        $this->assertSame(['layout', 'block_html', 'full_page', 'config'], $invalidated);
    }
}
