<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * ConfigSavePlugin - DEPRECATED
 *
 * Auto CSS export and npm build on config save is no longer needed.
 * Theme config is now managed via theme-config.json and built by Node.js.
 * This plugin is kept as a no-op for backward compatibility.
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Plugin;

use Magento\Config\Controller\Adminhtml\System\Config\Save as ConfigSave;

class ConfigSavePlugin
{
    /**
     * No-op: CSS export on config save is no longer needed.
     *
     * @param ConfigSave $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterExecute(ConfigSave $subject, $result)
    {
        return $result;
    }
}
