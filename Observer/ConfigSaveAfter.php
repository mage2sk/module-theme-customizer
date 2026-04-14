<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * ConfigSaveAfter Observer - DEPRECATED
 *
 * CSS export on config save is no longer needed.
 * Theme config is now managed via theme-config.json and built by Node.js.
 * This observer is kept as a no-op for backward compatibility.
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ConfigSaveAfter implements ObserverInterface
{
    /**
     * No-op: CSS export on config save is no longer needed.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        // No-op: Theme config is now managed via theme-config.json
    }
}
