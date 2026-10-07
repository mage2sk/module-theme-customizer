<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block;

use Magento\Framework\View\Element\Template;

class LumaTokens extends Template
{
    public const THEME_CODE = 'Magento/luma';

    public const STYLESHEET = 'Panth_ThemeCustomizer::css/luma-tokens.css';

    public function isLumaTheme(): bool
    {
        $theme = $this->_design->getDesignTheme();

        return $theme !== null && (string) $theme->getCode() === self::THEME_CODE;
    }

    public function getStylesheetUrl(): string
    {
        return (string) $this->getViewFileUrl(self::STYLESHEET);
    }

    protected function _toHtml()
    {
        if (!$this->isLumaTheme()) {
            return '';
        }

        return parent::_toHtml();
    }
}
