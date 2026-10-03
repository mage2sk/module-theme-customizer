<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Panth\ThemeCustomizer\Model\Config\FontFamilyNormalizer;

class Data extends AbstractHelper
{
    const XML_PATH_THEME_CUSTOMIZER = 'theme_customizer/';

    private FontFamilyNormalizer $fontFamilyNormalizer;

    public function __construct(
        Context $context,
        FontFamilyNormalizer $fontFamilyNormalizer
    ) {
        $this->fontFamilyNormalizer = $fontFamilyNormalizer;
        parent::__construct($context);
    }

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

    public function isGoogleFontsLoadEnabled($storeId = null): bool
    {
        return (bool)$this->getConfigValue('typography', 'load_google_fonts', $storeId);
    }

    public function getFontFamilyBase($storeId = null)
    {
        return $this->fontFamilyNormalizer->normalize(
            $this->getConfigValue('typography', 'font_family_base', $storeId)
        );
    }

    public function getFontFamilyHeading($storeId = null)
    {
        return $this->fontFamilyNormalizer->normalize(
            $this->getConfigValue('typography', 'font_family_heading', $storeId)
        );
    }

    public function getFontFamilyName(string $fontFamily): string
    {
        return $this->fontFamilyNormalizer->extractFamilyName($fontFamily);
    }

    public function getBreadcrumbSeparator(): string
    {
        return (string)($this->scopeConfig->getValue(
            'theme_customizer/breadcrumbs/breadcrumb_separator',
            ScopeInterface::SCOPE_STORE
        ) ?: '/');
    }
}
