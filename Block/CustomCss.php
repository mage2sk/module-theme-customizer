<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Custom CSS Block - Simplified
 *
 * Only outputs custom Tailwind CSS from admin config.
 * All color/style CSS generation has been removed - those are now
 * handled by theme-config.json and the Node.js build script.
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\ThemeCustomizer\Helper\Data as ThemeHelper;

class CustomCss extends Template
{
    /**
     * @var ThemeHelper
     */
    protected $themeHelper;

    /**
     * @param Context $context
     * @param ThemeHelper $themeHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        ThemeHelper $themeHelper,
        array $data = []
    ) {
        $this->themeHelper = $themeHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get theme helper
     *
     * @return ThemeHelper
     */
    public function getThemeHelper()
    {
        return $this->themeHelper;
    }

    /**
     * Get custom Tailwind CSS from admin config
     *
     * @return string
     */
    public function getCustomTailwindCss()
    {
        return $this->themeHelper->getCustomTailwindCss() ?: '';
    }
}
