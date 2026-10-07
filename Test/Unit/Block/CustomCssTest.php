<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Block;

use Panth\ThemeCustomizer\Block\CustomCss;
use Panth\ThemeCustomizer\Helper\Data;
use PHPUnit\Framework\TestCase;

class CustomCssTest extends TestCase
{
    private function block($css): CustomCss
    {
        $helper = $this->createStub(Data::class);
        $helper->method('getCustomTailwindCss')->willReturn($css);

        $block = (new \ReflectionClass(CustomCss::class))->newInstanceWithoutConstructor();
        $property = new \ReflectionProperty(CustomCss::class, 'themeHelper');
        $property->setValue($block, $helper);
        return $block;
    }

    public function testCssIsReturnedUnchangedWhenSafe(): void
    {
        $this->assertSame('.a > .b { color: red; }', $this->block('.a > .b { color: red; }')->getCustomTailwindCss());
    }

    public function testLessThanIsEscapedSoStyleTagCannotBeClosed(): void
    {
        $css = $this->block('.a{} </style><script>alert(1)</script>')->getCustomTailwindCss();

        $this->assertStringNotContainsString('<', $css);
        $this->assertSame('.a{} \3c /style>\3c script>alert(1)\3c /script>', $css);
    }

    public function testEmptyValuesBecomeEmptyString(): void
    {
        $this->assertSame('', $this->block(null)->getCustomTailwindCss());
        $this->assertSame('', $this->block(false)->getCustomTailwindCss());
    }

    public function testThemeHelperIsExposed(): void
    {
        $block = $this->block('');

        $this->assertInstanceOf(Data::class, $block->getThemeHelper());
    }
}
