<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_THEME_CUSTOMIZER = 'theme_customizer/';

    public function getConfigValue($group, $field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_THEME_CUSTOMIZER . $group . '/' . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabled($storeId = null)
    {
        return (bool)$this->getConfigValue('general', 'enabled', $storeId);
    }

    public function getCustomTailwindCss($storeId = null)
    {
        return $this->getConfigValue('custom_css', 'custom_tailwind_css', $storeId);
    }

    public function getFontFamilyBase($storeId = null)
    {
        return $this->getConfigValue('typography', 'font_family_base', $storeId);
    }

    public function getFontFamilyHeading($storeId = null)
    {
        return $this->getConfigValue('typography', 'font_family_heading', $storeId);
    }

    public function getBreadcrumbSeparator(): string
    {
        return (string)($this->scopeConfig->getValue(
            'theme_customizer/breadcrumbs/breadcrumb_separator',
            ScopeInterface::SCOPE_STORE
        ) ?: '/');
    }
}
