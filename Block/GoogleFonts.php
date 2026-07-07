<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\ThemeCustomizer\Helper\Data as ThemeHelper;

class GoogleFonts extends Template
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

    protected function isGoogleFont($font)
    {
        $systemFonts = ['system-ui', '-apple-system', 'sans-serif', 'serif', 'monospace'];
        foreach ($systemFonts as $sysFont) {
            if (str_contains($font, $sysFont)) {
                return false;
            }
        }
        return true;
    }

    protected function extractFontName($fontFamily)
    {
        preg_match("/'([^']+)'/", $fontFamily, $matches);
        return $matches[1] ?? $fontFamily;
    }

    public function getGoogleFontsUrl()
    {
        $fonts = $this->getFontsToLoad();

        if (empty($fonts)) {
            return null;
        }

        $fontParams = [];
        foreach ($fonts as $font) {
            $fontParams[] = str_replace(' ', '+', $font) . ':wght@300;400;500;600;700;800';
        }

        return 'https://fonts.googleapis.com/css2?'  . implode('&', array_map(function($f) {
            return 'family=' . $f;
        }, $fontParams)) . '&display=swap';
    }
}
