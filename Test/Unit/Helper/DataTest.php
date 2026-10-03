<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Panth\ThemeCustomizer\Helper\Data;
use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /**
     * @var array
     */
    private array $calls = [];

    private function helper(array $values): Data
    {
        $this->calls = [];
        $scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(
            function ($path, $scope = null, $storeId = null) use ($values) {
                $this->calls[] = [$path, $scope, $storeId];
                return $values[$path] ?? null;
            }
        );
        $context = $this->createStub(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);

        return new Data($context, new FontFamilyNormalizer());
    }

    public function testGetConfigValueBuildsPathAndPassesStoreScope(): void
    {
        $helper = $this->helper(['theme_customizer/colors/primary' => '#123456']);

        $this->assertSame('#123456', $helper->getConfigValue('colors', 'primary', 3));
        $this->assertSame([['theme_customizer/colors/primary', ScopeInterface::SCOPE_STORE, 3]], $this->calls);
    }

    public function testFlagsCastToBoolean(): void
    {
        $on = $this->helper([
            'theme_customizer/general/enabled' => '1',
            'theme_customizer/typography/load_google_fonts' => '1',
        ]);
        $off = $this->helper(['theme_customizer/general/enabled' => '0']);

        $this->assertTrue($on->isEnabled());
        $this->assertTrue($on->isGoogleFontsLoadEnabled());
        $this->assertFalse($off->isEnabled());
        $this->assertFalse($off->isGoogleFontsLoadEnabled());
    }

    public function testCustomTailwindCssIsReturnedRaw(): void
    {
        $helper = $this->helper(['theme_customizer/custom_css/custom_tailwind_css' => '.a { color: red; }']);

        $this->assertSame('.a { color: red; }', $helper->getCustomTailwindCss(2));
        $this->assertSame(2, $this->calls[0][2]);
    }

    public function testFontFamiliesAreNormalized(): void
    {
        $helper = $this->helper([
            'theme_customizer/typography/font_family_base' => ' "Inter", sans-serif ',
            'theme_customizer/typography/font_family_heading' => null,
        ]);

        $this->assertSame("'Inter', sans-serif", $helper->getFontFamilyBase());
        $this->assertSame('', $helper->getFontFamilyHeading());
    }

    public function testFontFamilyNameExtractsFirstFamily(): void
    {
        $helper = $this->helper([]);

        $this->assertSame('Open Sans', $helper->getFontFamilyName('"Open Sans", sans-serif'));
        $this->assertSame('system-ui', $helper->getFontFamilyName('system-ui, sans-serif'));
    }

    public function testBreadcrumbSeparatorDefaultsToSlash(): void
    {
        $this->assertSame('/', $this->helper([])->getBreadcrumbSeparator());
        $this->assertSame(
            '>',
            $this->helper(['theme_customizer/breadcrumbs/breadcrumb_separator' => '>'])->getBreadcrumbSeparator()
        );
    }
}
