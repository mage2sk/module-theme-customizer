<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Escaper;
use Panth\ThemeCustomizer\Block\Adminhtml\System\Config\TailwindCss;
use PHPUnit\Framework\TestCase;

class TailwindCssTest extends TestCase
{
    private function render(AbstractElement $element): string
    {
        $escaper = $this->createStub(Escaper::class);
        $escaper->method('escapeHtmlAttr')->willReturnCallback(
            static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8')
        );
        $escaper->method('escapeJs')->willReturnCallback(static fn($value) => addslashes((string)$value));
        $escaper->method('escapeHtml')->willReturnCallback(
            static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8')
        );

        $block = (new \ReflectionClass(TailwindCss::class))->newInstanceWithoutConstructor();
        (new \ReflectionProperty($block, '_escaper'))->setValue($block, $escaper);
        $method = new \ReflectionMethod(TailwindCss::class, '_getElementHtml');
        return (string)$method->invoke($block, $element);
    }

    private function element(string $htmlId): AbstractElement
    {
        return new class ($htmlId) extends AbstractElement {
            /**
             * @var string
             */
            private string $fixedId;

            public function __construct(string $fixedId)
            {
                $this->fixedId = $fixedId;
            }

            public function getHtmlId()
            {
                return $this->fixedId;
            }

            public function getElementHtml()
            {
                return '<textarea id="' . $this->fixedId . '"></textarea>';
            }
        };
    }

    public function testTextareaIsConfiguredAndRenderedFirst(): void
    {
        $element = $this->element('custom_css');
        $html = $this->render($element);

        $this->assertStringStartsWith('<textarea id="custom_css"></textarea>', $html);
        $this->assertSame(20, $element->getData('rows'));
        $this->assertStringContainsString('min-height: 400px', $element->getData('style'));
    }

    public function testToolbarControlsAreBoundToTheElementId(): void
    {
        $html = $this->render($this->element('custom_css'));

        foreach (['_editor', '_beautify', '_validate', '_download', '_import'] as $suffix) {
            $this->assertStringContainsString('id="custom_css' . $suffix . '"', $html);
        }
    }

    public function testElementIdIsEscapedInAttributes(): void
    {
        $html = $this->render($this->element('x"onmouseover="y'));

        $this->assertStringContainsString('id="x&quot;onmouseover=&quot;y_beautify"', $html);
        $this->assertStringNotContainsString('id="x"onmouseover="y_beautify"', $html);
    }
}
