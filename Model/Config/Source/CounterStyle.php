<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CounterStyle implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'circle', 'label' => __('Circle')],
            ['value' => 'pill', 'label' => __('Pill')],
            ['value' => 'square', 'label' => __('Square')],
        ];
    }
}
