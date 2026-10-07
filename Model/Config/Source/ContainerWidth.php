<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ContainerWidth implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'container', 'label' => __('Container (Centered, Max Width)')],
            ['value' => 'container-fluid', 'label' => __('Container Fluid (Full Width with Padding)')],
            ['value' => 'full', 'label' => __('Full Width (Edge to Edge)')],
        ];
    }
}
