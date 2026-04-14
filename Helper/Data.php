<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Theme Customizer Helper - Simplified
 *
 * Most color/style getter methods have been removed.
 * Colors, typography, spacing, and visual settings are now configured
 * in theme-config.json and processed by the Node.js build script.
 *
 * Only runtime methods needed by PHP/phtml code are kept:
 * - getConfigValue() for generic config access
 * - isEnabled() for module enable/disable check
 * - getCustomTailwindCss() for admin custom CSS
 * - getFontFamilyBase() / getFontFamilyHeading() for Google Fonts loading
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_THEME_CUSTOMIZER = 'theme_customizer/';

    /**
     * Get configuration value
     *
     * @param string $group
     * @param string $field
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfigValue($group, $field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_THEME_CUSTOMIZER . $group . '/' . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if the module is enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled($storeId = null)
    {
        return (bool)$this->getConfigValue('general', 'enabled', $storeId);
    }

    // ========================================
    // Custom CSS (still managed in admin)
    // ========================================

    /**
     * Get custom Tailwind CSS from admin
     *
     * @param int|null $storeId
     * @return string|null
     */
    public function getCustomTailwindCss($storeId = null)
    {
        return $this->getConfigValue('custom_css', 'custom_tailwind_css', $storeId);
    }

    // ========================================
    // Typography - kept for Google Fonts block
    // ========================================

    /**
     * Get base font family (used by GoogleFonts block to load fonts from Google)
     *
     * @param int|null $storeId
     * @return string|null
     */
    public function getFontFamilyBase($storeId = null)
    {
        return $this->getConfigValue('typography', 'font_family_base', $storeId);
    }

    /**
     * Get heading font family (used by GoogleFonts block to load fonts from Google)
     *
     * @param int|null $storeId
     * @return string|null
     */
    public function getFontFamilyHeading($storeId = null)
    {
        return $this->getConfigValue('typography', 'font_family_heading', $storeId);
    }

    /**
     * Get breadcrumb separator character
     * Value comes from theme-config.json (default: /)
     *
     * @return string
     */
    public function getBreadcrumbSeparator(): string
    {
        return (string)($this->scopeConfig->getValue(
            'theme_customizer/breadcrumbs/breadcrumb_separator',
            ScopeInterface::SCOPE_STORE
        ) ?: '/');
    }
}
