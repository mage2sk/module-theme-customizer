<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Shadow Options Source Model
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Shadow implements OptionSourceInterface
{
    /**
     * Get shadow options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('None')],
            ['value' => 'var(--shadow-sm)', 'label' => __('Small Shadow')],
            ['value' => 'var(--shadow-md)', 'label' => __('Medium Shadow')],
            ['value' => 'var(--shadow-lg)', 'label' => __('Large Shadow')],
            ['value' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)', 'label' => __('Custom Small')],
            ['value' => '0 4px 6px -1px rgba(0, 0, 0, 0.1)', 'label' => __('Custom Medium')],
            ['value' => '0 10px 15px -3px rgba(0, 0, 0, 0.1)', 'label' => __('Custom Large')],
            ['value' => '0 20px 25px -5px rgba(0, 0, 0, 0.1)', 'label' => __('Custom Extra Large')],
        ];
    }
}
