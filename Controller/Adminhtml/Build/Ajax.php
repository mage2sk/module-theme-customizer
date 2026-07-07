<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Controller\Adminhtml\Build;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Panth\ThemeCustomizer\Model\BuildExecutor;
use Psr\Log\LoggerInterface;

class Ajax extends Action
{
    const ADMIN_RESOURCE = 'Panth_ThemeCustomizer::config';

    private $resultJsonFactory;

    private $buildExecutor;

    private $logger;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        BuildExecutor $buildExecutor,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->buildExecutor = $buildExecutor;
        $this->logger = $logger;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            $this->logger->info('[Ajax Controller] Theme build request received');

            $buildStart = microtime(true);
            $result = $this->buildExecutor->exportAndBuild(true);
            $buildTime = round((microtime(true) - $buildStart), 2);
            $this->logger->info('[Ajax Controller] Build completed in ' . $buildTime . ' seconds');

            if ($result['success']) {
                $this->logger->info('[Ajax Controller] Build SUCCESS');

                return $resultJson->setData([
                    'success' => true,
                    'message' => 'Theme built successfully! The page will reload shortly.',
                    'output' => $result['output'] ?? '',
                    'npm_build_executed' => true
                ]);
            } else {
                $this->logger->error('[Ajax Controller] Build FAILED: ' . $result['message']);

                return $resultJson->setData([
                    'success' => false,
                    'message' => 'Build failed: ' . $result['message'],
                    'output' => $result['output'] ?? ''
                ]);
            }
        } catch (\Exception $e) {
            $this->logger->critical('[Ajax Controller] EXCEPTION: ' . $e->getMessage());

            return $resultJson->setData([
                'success' => false,
                'message' => 'Build error: ' . $e->getMessage()
            ]);
        }
    }
}
