<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Cart Counter Badge Style Options
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CounterStyle implements OptionSourceInterface
{
    /**
     * Get counter badge style options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'circle', 'label' => __('Circle')],
            ['value' => 'pill', 'label' => __('Pill')],
            ['value' => 'square', 'label' => __('Square')],
        ];
    }
}
