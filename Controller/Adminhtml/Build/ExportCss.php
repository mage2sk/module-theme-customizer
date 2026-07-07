<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Controller\Adminhtml\Build;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class ExportCss extends Action
{
    const ADMIN_RESOURCE = 'Panth_ThemeCustomizer::config';

    private $resultJsonFactory;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData([
            'success' => true,
            'message' => 'CSS export is no longer needed. Theme config is now in theme-config.json.'
        ]);
    }
}
