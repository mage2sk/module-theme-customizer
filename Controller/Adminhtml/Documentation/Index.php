<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Controller\Adminhtml\Documentation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Panth_ThemeCustomizer::documentation');
        $resultPage->getConfig()->getTitle()->prepend(__('Theme Customizer Documentation'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Panth_ThemeCustomizer::config');
    }
}
