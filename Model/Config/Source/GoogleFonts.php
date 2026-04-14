<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Google Fonts Source Model
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class GoogleFonts implements OptionSourceInterface
{
    /**
     * Get popular Google Fonts options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('-- System Default --')],
            ['value' => "'Inter', sans-serif", 'label' => __('Inter (Modern, Clean)')],
            ['value' => "'Roboto', sans-serif", 'label' => __('Roboto (Google Default)')],
            ['value' => "'Open Sans', sans-serif", 'label' => __('Open Sans (Friendly)')],
            ['value' => "'Lato', sans-serif", 'label' => __('Lato (Professional)')],
            ['value' => "'Montserrat', sans-serif", 'label' => __('Montserrat (Geometric)')],
            ['value' => "'Poppins', sans-serif", 'label' => __('Poppins (Rounded)')],
            ['value' => "'Raleway', sans-serif", 'label' => __('Raleway (Elegant)')],
            ['value' => "'Nunito', sans-serif", 'label' => __('Nunito (Friendly)')],
            ['value' => "'Work Sans', sans-serif", 'label' => __('Work Sans (Contemporary)')],
            ['value' => "'Playfair Display', serif", 'label' => __('Playfair Display (Serif, Elegant)')],
            ['value' => "'Merriweather', serif", 'label' => __('Merriweather (Serif, Readable)')],
            ['value' => "'Source Sans Pro', sans-serif", 'label' => __('Source Sans Pro (Adobe)')],
            ['value' => "'Ubuntu', sans-serif", 'label' => __('Ubuntu (Modern)')],
            ['value' => "'Oswald', sans-serif", 'label' => __('Oswald (Bold Headers)')],
            ['value' => "'PT Sans', sans-serif", 'label' => __('PT Sans (Universal)')],
            ['value' => "'Quicksand', sans-serif", 'label' => __('Quicksand (Playful)')],
            ['value' => "'Barlow', sans-serif", 'label' => __('Barlow (Technical)')],
            ['value' => "'DM Sans', sans-serif", 'label' => __('DM Sans (Low Contrast)')],
            ['value' => "'Manrope', sans-serif", 'label' => __('Manrope (Modern)')],
            ['value' => "'Space Grotesk', sans-serif", 'label' => __('Space Grotesk (Futuristic)')],
            ['value' => "system-ui, -apple-system, sans-serif", 'label' => __('System Fonts (Fastest)')],
        ];
    }
}
