<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Google Fonts Loader Block
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\ThemeCustomizer\Helper\Data as ThemeHelper;

class GoogleFonts extends Template
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
     * Get unique font families to load
     *
     * @return array
     */
    public function getFontsToLoad()
    {
        $fonts = [];

        $baseFont = $this->themeHelper->getFontFamilyBase();
        $headingFont = $this->themeHelper->getFontFamilyHeading();

        if ($baseFont && $this->isGoogleFont($baseFont)) {
            $fonts[] = $this->extractFontName($baseFont);
        }

        if ($headingFont && $this->isGoogleFont($headingFont) && $headingFont !== $baseFont) {
            $fonts[] = $this->extractFontName($headingFont);
        }

        return array_unique($fonts);
    }

    /**
     * Check if font is a Google Font
     *
     * @param string $font
     * @return bool
     */
    protected function isGoogleFont($font)
    {
        // System fonts don't need to be loaded from Google
        $systemFonts = ['system-ui', '-apple-system', 'sans-serif', 'serif', 'monospace'];
        foreach ($systemFonts as $sysFont) {
            if (str_contains($font, $sysFont)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Extract font name from font family string
     *
     * @param string $fontFamily
     * @return string
     */
    protected function extractFontName($fontFamily)
    {
        // Extract font name: "'Inter', sans-serif" -> "Inter"
        preg_match("/'([^']+)'/", $fontFamily, $matches);
        return $matches[1] ?? $fontFamily;
    }

    /**
     * Get Google Fonts URL
     *
     * @return string|null
     */
    public function getGoogleFontsUrl()
    {
        $fonts = $this->getFontsToLoad();

        if (empty($fonts)) {
            return null;
        }

        // Build Google Fonts URL with weights
        $fontParams = [];
        foreach ($fonts as $font) {
            // Load multiple weights for better flexibility
            $fontParams[] = str_replace(' ', '+', $font) . ':wght@300;400;500;600;700;800';
        }

        return 'https://fonts.googleapis.com/css2?'  . implode('&', array_map(function($f) {
            return 'family=' . $f;
        }, $fontParams)) . '&display=swap';
    }
}
