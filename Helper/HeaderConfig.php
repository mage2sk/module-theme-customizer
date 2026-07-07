<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class HeaderConfig extends AbstractHelper
{
    public const XML_PATH_HEADER = 'panth_header/';

    public function getConfigValue(string $field, ?int $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_HEADER . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabled(): bool
    {
        return (bool) $this->getConfigValue('general/enabled');
    }

    public function isStickyEnabled(): bool
    {
        return (bool) $this->getConfigValue('general/sticky_enabled');
    }

    public function showOnScrollUp(): bool
    {
        return (bool) $this->getConfigValue('general/show_on_scroll');
    }

    public function hasStickyShadow(): bool
    {
        return (bool) $this->getConfigValue('design/sticky_shadow');
    }

    public function getHeight(): int
    {
        return (int) $this->getConfigValue('layout/height') ?: 80;
    }

    public function getLogoDesktopWidth(): int
    {
        $width = $this->getConfigValue('layout/logo_desktop_width');
        return $width ? (int) $width : 180;
    }

    public function getLogoDesktopHeight(): int
    {
        $height = $this->getConfigValue('layout/logo_desktop_height');
        return $height ? (int) $height : 50;
    }

    public function getLogoMobileWidth(): int
    {
        $width = $this->getConfigValue('layout/logo_mobile_width');
        return $width ? (int) $width : 150;
    }

    public function getLogoMobileHeight(): int
    {
        $height = $this->getConfigValue('layout/logo_mobile_height');
        return $height ? (int) $height : 45;
    }

    public function getLogoWidth(): int
    {
        return $this->getLogoDesktopWidth();
    }

    public function getContainerWidth(): string
    {
        return (string) ($this->getConfigValue('layout/container_width') ?: 'container');
    }

    public function getContainerClass(): string
    {
        $width = $this->getContainerWidth();
        $classes = [
            'full' => 'w-full px-4 sm:px-6 lg:px-8',
            'container' => 'container mx-auto px-4 sm:px-6 lg:px-8',
            'container-fluid' => 'container-fluid mx-auto px-4 sm:px-6 lg:px-8',
        ];

        return $classes[$width] ?? $classes['container'];
    }

    public function isTopBarEnabled(): bool
    {
        return (bool) $this->getConfigValue('topbar/enabled');
    }

    public function getTopBarLeftText(): ?string
    {
        $value = $this->getConfigValue('topbar/left_text');
        return $value !== null ? (string) $value : null;
    }

    public function getTopBarRightText(): ?string
    {
        $value = $this->getConfigValue('topbar/right_text');
        return $value !== null ? (string) $value : null;
    }

    public function isSearchEnabled(): bool
    {
        return (bool) $this->getConfigValue('search/enabled');
    }

    public function getSearchPlaceholder(): string
    {
        return (string) ($this->getConfigValue('search/placeholder') ?: __('Search for products...'));
    }

    public function isFreeShippingProgressEnabled(): bool
    {
        return (bool) $this->getConfigValue('minicart/free_shipping_enabled');
    }

    public function getFreeShippingThreshold(): float
    {
        return (float) $this->getConfigValue('minicart/free_shipping_threshold') ?: 50.0;
    }

    public function getFreeShippingMessage(): string
    {
        return (string) ($this->getConfigValue('minicart/free_shipping_message')
            ?: __('Add {amount} more to get FREE SHIPPING!'));
    }

    public function getFreeShippingSuccessMessage(): string
    {
        return (string) ($this->getConfigValue('minicart/free_shipping_success_message')
            ?: __('Congratulations! You\'ve qualified for FREE SHIPPING!'));
    }

    public function showSubtotal(): bool
    {
        return (bool) $this->getConfigValue('minicart/show_subtotal');
    }

    public function showContinueShopping(): bool
    {
        return (bool) $this->getConfigValue('minicart/show_continue_shopping');
    }

    public function isSearchIconEnabled(): bool
    {
        return (bool) $this->getConfigValue('icons/search_enabled');
    }

    public function getSearchIconColor(): string
    {
        return (string) ($this->getConfigValue('icons/search_icon_color') ?: '#374151');
    }

    public function getSearchIconHoverColor(): string
    {
        return (string) ($this->getConfigValue('icons/search_icon_hover_color') ?: '#111827');
    }

    public function isAccountIconEnabled(): bool
    {
        return (bool) $this->getConfigValue('icons/account_enabled');
    }

    public function getAccountIconColor(): string
    {
        return (string) ($this->getConfigValue('icons/account_icon_color') ?: '#374151');
    }

    public function getAccountIconHoverColor(): string
    {
        return (string) ($this->getConfigValue('icons/account_icon_hover_color') ?: '#111827');
    }

    public function isMinicartIconEnabled(): bool
    {
        return (bool) $this->getConfigValue('icons/minicart_enabled');
    }

    public function getMinicartIconColor(): string
    {
        return (string) ($this->getConfigValue('icons/minicart_icon_color') ?: '#374151');
    }

    public function getMinicartIconHoverColor(): string
    {
        return (string) ($this->getConfigValue('icons/minicart_icon_hover_color') ?: '#111827');
    }

    public function getCounterBgColor(): string
    {
        return (string) ($this->getConfigValue('icons/counter_bg_color') ?: '#ef4444');
    }

    public function getCounterTextColor(): string
    {
        return (string) ($this->getConfigValue('icons/counter_text_color') ?: '#ffffff');
    }

    public function getCounterStyle(): string
    {
        return (string) ($this->getConfigValue('icons/counter_style') ?: 'circle');
    }

    public function getIconSize(): int
    {
        return (int) $this->getConfigValue('icons/icon_size') ?: 24;
    }

    public function hasStickyhadow(): bool
    {
        return $this->hasStickyShadow();
    }

    public function getStickyBackground(): string
    {
        return (string) ($this->getConfigValue('sticky/background') ?: '#ffffff');
    }

    public function isWhatsAppEnabled(): bool
    {
        return (bool) $this->getConfigValue('whatsapp/enabled');
    }

    public function getWhatsAppPhone(): string
    {
        return (string) ($this->getConfigValue('whatsapp/phone') ?: '');
    }

    public function getWhatsAppMessage(): string
    {
        return (string) ($this->getConfigValue('whatsapp/message') ?: '');
    }

    public function getWhatsAppButtonText(): string
    {
        return (string) ($this->getConfigValue('whatsapp/button_text') ?: 'Chat with us');
    }

    public function getWhatsAppPosition(): string
    {
        return (string) ($this->getConfigValue('whatsapp/position') ?: 'right');
    }
}
