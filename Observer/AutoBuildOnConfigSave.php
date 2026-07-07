<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AutoBuildOnConfigSave implements ObserverInterface
{
    public function execute(Observer $observer)
    {
    }
}
