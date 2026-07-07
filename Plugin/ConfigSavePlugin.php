<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Plugin;

use Magento\Config\Controller\Adminhtml\System\Config\Save as ConfigSave;

class ConfigSavePlugin
{
    public function afterExecute(ConfigSave $subject, $result)
    {
        return $result;
    }
}
