<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\ThemeCustomizer\Helper\Data as ThemeHelper;

class CustomCss extends Template
{
    protected $themeHelper;

    public function __construct(
        Context $context,
        ThemeHelper $themeHelper,
        array $data = []
    ) {
        $this->themeHelper = $themeHelper;
        parent::__construct($context, $data);
    }

    public function getThemeHelper()
    {
        return $this->themeHelper;
    }

    public function getCustomTailwindCss()
    {
        return $this->themeHelper->getCustomTailwindCss() ?: '';
    }
}
