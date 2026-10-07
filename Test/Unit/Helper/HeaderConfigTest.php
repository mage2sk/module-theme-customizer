<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Panth\ThemeCustomizer\Helper\HeaderConfig;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HeaderConfigTest extends TestCase
{
    private function config(array $values, array &$calls = []): HeaderConfig
    {
        $scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(
            function ($path, $scope = null, $storeId = null) use ($values, &$calls) {
                $calls[] = [$path, $scope, $storeId];
                $short = substr((string)$path, strlen(HeaderConfig::XML_PATH_HEADER));
                return $values[$path] ?? $values[$short] ?? null;
            }
        );
        $context = $this->createStub(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);

        return new HeaderConfig($context);
    }

    public function testConfigValueUsesHeaderPrefixAndStoreScope(): void
    {
        $calls = [];
        $config = $this->config(['panth_header/general/enabled' => '1'], $calls);

        $this->assertSame('1', $config->getConfigValue('general/enabled', 4));
        $this->assertSame([['panth_header/general/enabled', ScopeInterface::SCOPE_STORE, 4]], $calls);
    }

    public static function defaults(): array
    {
        return [
            ['getHeight', 80],
            ['getLogoDesktopWidth', 180],
            ['getLogoDesktopHeight', 50],
            ['getLogoMobileWidth', 150],
            ['getLogoMobileHeight', 45],
            ['getLogoWidth', 180],
            ['getContainerWidth', 'container'],
            ['getContainerClass', 'container mx-auto px-4 sm:px-6 lg:px-8'],
            ['getTopBarLeftText', null],
            ['getTopBarRightText', null],
            ['getSearchPlaceholder', 'Search for products...'],
            ['getFreeShippingThreshold', 99.0],
            ['getFreeShippingMessage', 'Add {amount} more to get FREE SHIPPING!'],
            ['getFreeShippingSuccessMessage', 'Congratulations! You\'ve qualified for FREE SHIPPING!'],
            ['getSearchIconColor', '#374151'],
            ['getSearchIconHoverColor', '#111827'],
            ['getAccountIconColor', '#374151'],
            ['getAccountIconHoverColor', '#111827'],
            ['getMinicartIconColor', '#374151'],
            ['getMinicartIconHoverColor', '#111827'],
            ['getCounterBgColor', '#ef4444'],
            ['getCounterTextColor', '#ffffff'],
            ['getCounterStyle', 'circle'],
            ['getIconSize', 24],
            ['getStickyBackground', '#ffffff'],
            ['getWhatsAppPhone', ''],
            ['getWhatsAppMessage', ''],
            ['getWhatsAppButtonText', 'Chat with us'],
            ['getWhatsAppPosition', 'right'],
        ];
    }

    #[DataProvider('defaults')]
    public function testDefaultsWhenNothingIsConfigured(string $method, $expected): void
    {
        $this->assertSame($expected, $this->config([])->$method());
    }

    public static function configured(): array
    {
        return [
            ['getHeight', 'layout/height', '96', 96],
            ['getLogoDesktopWidth', 'layout/logo_desktop_width', '200', 200],
            ['getLogoDesktopHeight', 'layout/logo_desktop_height', '60', 60],
            ['getLogoMobileWidth', 'layout/logo_mobile_width', '120', 120],
            ['getLogoMobileHeight', 'layout/logo_mobile_height', '30', 30],
            ['getContainerWidth', 'layout/container_width', 'full', 'full'],
            ['getTopBarLeftText', 'topbar/left_text', 'Free returns', 'Free returns'],
            ['getTopBarRightText', 'topbar/right_text', '', ''],
            ['getSearchPlaceholder', 'search/placeholder', 'Find it', 'Find it'],
            ['getFreeShippingThreshold', 'minicart/free_shipping_threshold', '50.5', 50.5],
            ['getCounterStyle', 'icons/counter_style', 'pill', 'pill'],
            ['getIconSize', 'icons/icon_size', '32', 32],
            ['getWhatsAppPhone', 'whatsapp/phone', '+441234', '+441234'],
            ['getWhatsAppPosition', 'whatsapp/position', 'left', 'left'],
        ];
    }

    #[DataProvider('configured')]
    public function testConfiguredValuesAreCast(string $method, string $path, $raw, $expected): void
    {
        $this->assertSame($expected, $this->config([$path => $raw])->$method());
    }

    public function testZeroNumericValuesFallBackToDefaults(): void
    {
        $config = $this->config([
            'layout/height' => '0',
            'layout/logo_desktop_width' => '0',
            'icons/icon_size' => '0',
        ]);

        $this->assertSame(80, $config->getHeight());
        $this->assertSame(180, $config->getLogoDesktopWidth());
        $this->assertSame(24, $config->getIconSize());
    }

    public function testZeroFreeShippingThresholdIsRespected(): void
    {
        $this->assertSame(0.0, $this->config(['minicart/free_shipping_threshold' => '0'])->getFreeShippingThreshold());
        $this->assertSame(0.0, $this->config(['minicart/free_shipping_threshold' => '0.00'])->getFreeShippingThreshold());
        $this->assertSame(0.0, $this->config(['minicart/free_shipping_threshold' => '-5'])->getFreeShippingThreshold());
        $this->assertSame(99.0, $this->config(['minicart/free_shipping_threshold' => ''])->getFreeShippingThreshold());
        $this->assertSame(99.0, $this->config(['minicart/free_shipping_threshold' => 'abc'])->getFreeShippingThreshold());
    }

    public static function containerClasses(): array
    {
        return [
            ['full', 'w-full px-4 sm:px-6 lg:px-8'],
            ['container', 'container mx-auto px-4 sm:px-6 lg:px-8'],
            ['container-fluid', 'container-fluid mx-auto px-4 sm:px-6 lg:px-8'],
            ['unknown-value', 'container mx-auto px-4 sm:px-6 lg:px-8'],
        ];
    }

    #[DataProvider('containerClasses')]
    public function testContainerClassMapping(string $width, string $class): void
    {
        $this->assertSame($class, $this->config(['layout/container_width' => $width])->getContainerClass());
    }

    public function testBooleanFlags(): void
    {
        $flags = [
            'isEnabled' => 'general/enabled',
            'isStickyEnabled' => 'general/sticky_enabled',
            'showOnScrollUp' => 'general/show_on_scroll',
            'hasStickyShadow' => 'design/sticky_shadow',
            'hasStickyhadow' => 'design/sticky_shadow',
            'isTopBarEnabled' => 'topbar/enabled',
            'isSearchEnabled' => 'search/enabled',
            'isFreeShippingProgressEnabled' => 'minicart/free_shipping_enabled',
            'showSubtotal' => 'minicart/show_subtotal',
            'showContinueShopping' => 'minicart/show_continue_shopping',
            'isSearchIconEnabled' => 'icons/search_enabled',
            'isAccountIconEnabled' => 'icons/account_enabled',
            'isMinicartIconEnabled' => 'icons/minicart_enabled',
            'isWhatsAppEnabled' => 'whatsapp/enabled',
        ];
        $off = $this->config([]);
        $on = $this->config(array_fill_keys(array_values($flags), '1'));

        foreach ($flags as $method => $path) {
            $this->assertFalse($off->$method(), $method . ' should be off by default');
            $this->assertTrue($on->$method(), $method . ' should read ' . $path);
        }
    }
}
