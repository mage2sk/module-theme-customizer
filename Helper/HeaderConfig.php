<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 * Header Configuration Helper
 * Migrated from Panth\Header\Helper\Data
 * Reads from panth_header/* config paths (preserves existing DB values)
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class HeaderConfig extends AbstractHelper
{
    public const XML_PATH_HEADER = 'panth_header/';

    /**
     * Get config value
     *
     * @param string $field
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfigValue(string $field, ?int $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_HEADER . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if custom header is enabled
     */
    public function isEnabled(): bool
    {
        return (bool) $this->getConfigValue('general/enabled');
    }

    /**
     * Check if sticky header is enabled
     */
    public function isStickyEnabled(): bool
    {
        return (bool) $this->getConfigValue('general/sticky_enabled');
    }

    /**
     * Check if show only on scroll up
     */
    public function showOnScrollUp(): bool
    {
        return (bool) $this->getConfigValue('general/show_on_scroll');
    }

    /**
     * Check if sticky shadow is enabled
     */
    public function hasStickyShadow(): bool
    {
        return (bool) $this->getConfigValue('design/sticky_shadow');
    }

    /**
     * Get header height
     */
    public function getHeight(): int
    {
        return (int) $this->getConfigValue('layout/height') ?: 80;
    }

    /**
     * Get desktop logo width
     */
    public function getLogoDesktopWidth(): int
    {
        $width = $this->getConfigValue('layout/logo_desktop_width');
        return $width ? (int) $width : 180;
    }

    /**
     * Get desktop logo height
     */
    public function getLogoDesktopHeight(): int
    {
        $height = $this->getConfigValue('layout/logo_desktop_height');
        return $height ? (int) $height : 50;
    }

    /**
     * Get mobile logo width
     */
    public function getLogoMobileWidth(): int
    {
        $width = $this->getConfigValue('layout/logo_mobile_width');
        return $width ? (int) $width : 150;
    }

    /**
     * Get mobile logo height
     */
    public function getLogoMobileHeight(): int
    {
        $height = $this->getConfigValue('layout/logo_mobile_height');
        return $height ? (int) $height : 45;
    }

    /**
     * Get logo width (legacy - returns desktop width)
     */
    public function getLogoWidth(): int
    {
        return $this->getLogoDesktopWidth();
    }

    /**
     * Get container width setting value
     */
    public function getContainerWidth(): string
    {
        return (string) ($this->getConfigValue('layout/container_width') ?: 'container');
    }

    /**
     * Get container class based on width setting
     */
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

    /**
     * Check if top bar is enabled
     */
    public function isTopBarEnabled(): bool
    {
        return (bool) $this->getConfigValue('topbar/enabled');
    }

    /**
     * Get top bar left text
     */
    public function getTopBarLeftText(): ?string
    {
        $value = $this->getConfigValue('topbar/left_text');
        return $value !== null ? (string) $value : null;
    }

    /**
     * Get top bar right text
     */
    public function getTopBarRightText(): ?string
    {
        $value = $this->getConfigValue('topbar/right_text');
        return $value !== null ? (string) $value : null;
    }

    /**
     * Check if search is enabled
     */
    public function isSearchEnabled(): bool
    {
        return (bool) $this->getConfigValue('search/enabled');
    }

    /**
     * Get search placeholder
     */
    public function getSearchPlaceholder(): string
    {
        return (string) ($this->getConfigValue('search/placeholder') ?: __('Search for products...'));
    }

    /**
     * Check if free shipping progress is enabled
     */
    public function isFreeShippingProgressEnabled(): bool
    {
        return (bool) $this->getConfigValue('minicart/free_shipping_enabled');
    }

    /**
     * Get free shipping threshold
     */
    public function getFreeShippingThreshold(): float
    {
        return (float) $this->getConfigValue('minicart/free_shipping_threshold') ?: 50.0;
    }

    /**
     * Get free shipping message
     */
    public function getFreeShippingMessage(): string
    {
        return (string) ($this->getConfigValue('minicart/free_shipping_message')
            ?: __('Add {amount} more to get FREE SHIPPING!'));
    }

    /**
     * Get free shipping success message
     */
    public function getFreeShippingSuccessMessage(): string
    {
        return (string) ($this->getConfigValue('minicart/free_shipping_success_message')
            ?: __('Congratulations! You\'ve qualified for FREE SHIPPING!'));
    }

    /**
     * Check if subtotal should be shown
     */
    public function showSubtotal(): bool
    {
        return (bool) $this->getConfigValue('minicart/show_subtotal');
    }

    /**
     * Check if continue shopping button should be shown
     */
    public function showContinueShopping(): bool
    {
        return (bool) $this->getConfigValue('minicart/show_continue_shopping');
    }

    /**
     * Check if search icon is enabled
     */
    public function isSearchIconEnabled(): bool
    {
        return (bool) $this->getConfigValue('icons/search_enabled');
    }

    /**
     * Get search icon color
     */
    public function getSearchIconColor(): string
    {
        return (string) ($this->getConfigValue('icons/search_icon_color') ?: '#374151');
    }

    /**
     * Get search icon hover color
     */
    public function getSearchIconHoverColor(): string
    {
        return (string) ($this->getConfigValue('icons/search_icon_hover_color') ?: '#111827');
    }

    /**
     * Check if account icon is enabled
     */
    public function isAccountIconEnabled(): bool
    {
        return (bool) $this->getConfigValue('icons/account_enabled');
    }

    /**
     * Get account icon color
     */
    public function getAccountIconColor(): string
    {
        return (string) ($this->getConfigValue('icons/account_icon_color') ?: '#374151');
    }

    /**
     * Get account icon hover color
     */
    public function getAccountIconHoverColor(): string
    {
        return (string) ($this->getConfigValue('icons/account_icon_hover_color') ?: '#111827');
    }

    /**
     * Check if minicart icon is enabled
     */
    public function isMinicartIconEnabled(): bool
    {
        return (bool) $this->getConfigValue('icons/minicart_enabled');
    }

    /**
     * Get minicart icon color
     */
    public function getMinicartIconColor(): string
    {
        return (string) ($this->getConfigValue('icons/minicart_icon_color') ?: '#374151');
    }

    /**
     * Get minicart icon hover color
     */
    public function getMinicartIconHoverColor(): string
    {
        return (string) ($this->getConfigValue('icons/minicart_icon_hover_color') ?: '#111827');
    }

    /**
     * Get counter badge background color
     */
    public function getCounterBgColor(): string
    {
        return (string) ($this->getConfigValue('icons/counter_bg_color') ?: '#ef4444');
    }

    /**
     * Get counter badge text color
     */
    public function getCounterTextColor(): string
    {
        return (string) ($this->getConfigValue('icons/counter_text_color') ?: '#ffffff');
    }

    /**
     * Get counter badge style
     */
    public function getCounterStyle(): string
    {
        return (string) ($this->getConfigValue('icons/counter_style') ?: 'circle');
    }

    /**
     * Get icon size
     */
    public function getIconSize(): int
    {
        return (int) $this->getConfigValue('icons/icon_size') ?: 24;
    }

    /**
     * Legacy alias for hasStickyShadow (theme template uses old typo)
     */
    public function hasStickyhadow(): bool
    {
        return $this->hasStickyShadow();
    }

    /**
     * Get sticky header background color
     */
    public function getStickyBackground(): string
    {
        return (string) ($this->getConfigValue('sticky/background') ?: '#ffffff');
    }

    /**
     * Check if WhatsApp button is enabled in header
     */
    public function isWhatsAppEnabled(): bool
    {
        return (bool) $this->getConfigValue('whatsapp/enabled');
    }

    /**
     * Get WhatsApp phone number
     */
    public function getWhatsAppPhone(): string
    {
        return (string) ($this->getConfigValue('whatsapp/phone') ?: '');
    }

    /**
     * Get WhatsApp pre-filled message
     */
    public function getWhatsAppMessage(): string
    {
        return (string) ($this->getConfigValue('whatsapp/message') ?: '');
    }

    /**
     * Get WhatsApp button text
     */
    public function getWhatsAppButtonText(): string
    {
        return (string) ($this->getConfigValue('whatsapp/button_text') ?: 'Chat with us');
    }

    /**
     * Get WhatsApp button position
     */
    public function getWhatsAppPosition(): string
    {
        return (string) ($this->getConfigValue('whatsapp/position') ?: 'right');
    }
}
