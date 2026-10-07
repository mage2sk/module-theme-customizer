<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\ThemeCustomizer\Helper\Data as ThemeHelper;
use Panth\ThemeCustomizer\Model\Config\Source\GoogleFonts as GoogleFontsSource;

class GoogleFonts extends Template
{
    private const FONT_WEIGHTS = '300;400;500;600;700;800';

    protected $themeHelper;

    public function __construct(
        Context $context,
        ThemeHelper $themeHelper,
        array $data = []
    ) {
        $this->themeHelper = $themeHelper;
        parent::__construct($context, $data);
    }

    public function isLoadEnabled(): bool
    {
        return $this->themeHelper->isGoogleFontsLoadEnabled();
    }

    public function getFontsToLoad()
    {
        if (!$this->isLoadEnabled()) {
            return [];
        }

        $fonts = [];
        foreach ([$this->themeHelper->getFontFamilyBase(), $this->themeHelper->getFontFamilyHeading()] as $font) {
            if ($font && $this->isGoogleFont($font)) {
                $fonts[] = $this->extractFontName($font);
            }
        }

        return array_values(array_unique($fonts));
    }

    protected function isGoogleFont($font)
    {
        return in_array($this->extractFontName($font), GoogleFontsSource::GOOGLE_FAMILIES, true);
    }

    protected function extractFontName($fontFamily)
    {
        return $this->themeHelper->getFontFamilyName((string) $fontFamily);
    }

    public function getGoogleFontsUrl()
    {
        $fonts = $this->getFontsToLoad();

        if (empty($fonts)) {
            return null;
        }

        $families = [];
        foreach ($fonts as $font) {
            $families[] = 'family=' . str_replace(' ', '+', $font) . ':wght@' . self::FONT_WEIGHTS;
        }

        return 'https://fonts.googleapis.com/css2?' . implode('&', $families) . '&display=swap';
    }
}
