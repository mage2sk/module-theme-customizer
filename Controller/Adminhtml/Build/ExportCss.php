<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Export CSS Controller - DEPRECATED
 *
 * CSS export from database is no longer needed.
 * Theme config is now in theme-config.json, processed by Node.js build script.
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Controller\Adminhtml\Build;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class ExportCss extends Action
{
    const ADMIN_RESOURCE = 'Panth_ThemeCustomizer::config';

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Returns deprecation notice. CSS export is no longer needed.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData([
            'success' => true,
            'message' => 'CSS export is no longer needed. Theme config is now in theme-config.json.'
        ]);
    }
}
