<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 * Import/Export Theme Settings Controller
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Controller\Adminhtml\ImportExport;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'Panth_ThemeCustomizer::config';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Import/Export Index action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Panth_ThemeCustomizer::importexport');
        $resultPage->getConfig()->getTitle()->prepend(__('Import/Export Theme Settings'));

        return $resultPage;
    }

    /**
     * Check permission
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
