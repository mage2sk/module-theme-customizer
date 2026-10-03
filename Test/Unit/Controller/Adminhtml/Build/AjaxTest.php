<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Controller\Adminhtml\Build;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Panth\ThemeCustomizer\Controller\Adminhtml\Build\Ajax;
use Panth\ThemeCustomizer\Controller\Adminhtml\Build\ExportCss;
use Panth\ThemeCustomizer\Model\BuildExecutor;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AjaxTest extends TestCase
{
    /**
     * @var array|null
     */
    private ?array $data = null;

    private function jsonFactory(): JsonFactory
    {
        $json = $this->createStub(Json::class);
        $json->method('setData')->willReturnCallback(function ($data) use ($json) {
            $this->data = $data;
            return $json;
        });
        $factory = $this->createStub(JsonFactory::class);
        $factory->method('create')->willReturn($json);
        return $factory;
    }

    private function controller(BuildExecutor $executor, ?LoggerInterface $logger = null): Ajax
    {
        return new Ajax(
            $this->createStub(Context::class),
            $this->jsonFactory(),
            $executor,
            $logger ?? $this->createStub(LoggerInterface::class)
        );
    }

    public function testSuccessfulBuildReturnsReloadMessage(): void
    {
        $executor = $this->createMock(BuildExecutor::class);
        $executor->expects($this->once())
            ->method('exportAndBuild')
            ->with(true)
            ->willReturn(['success' => true, 'message' => 'ok', 'output' => 'built']);

        $result = $this->controller($executor)->execute();

        $this->assertInstanceOf(Json::class, $result);
        $this->assertSame([
            'success' => true,
            'message' => 'Theme built successfully! The page will reload shortly.',
            'output' => 'built',
            'npm_build_executed' => true,
        ], $this->data);
    }

    public function testSuccessWithoutOutputDefaultsToEmptyString(): void
    {
        $executor = $this->createStub(BuildExecutor::class);
        $executor->method('exportAndBuild')->willReturn(['success' => true, 'message' => 'ok']);

        $this->controller($executor)->execute();

        $this->assertSame('', $this->data['output']);
    }

    public function testFailedBuildIsLoggedAndReported(): void
    {
        $executor = $this->createStub(BuildExecutor::class);
        $executor->method('exportAndBuild')->willReturn(['success' => false, 'message' => 'npm missing']);
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with('[Ajax Controller] Build FAILED: npm missing');

        $this->controller($executor, $logger)->execute();

        $this->assertSame(
            ['success' => false, 'message' => 'Build failed: npm missing', 'output' => ''],
            $this->data
        );
    }

    public function testExceptionIsCaughtAndLoggedAsCritical(): void
    {
        $executor = $this->createStub(BuildExecutor::class);
        $executor->method('exportAndBuild')->willThrowException(new \RuntimeException('shell disabled'));
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('critical')
            ->with('[Ajax Controller] EXCEPTION: shell disabled');

        $this->controller($executor, $logger)->execute();

        $this->assertSame(['success' => false, 'message' => 'Build error: shell disabled'], $this->data);
    }

    public function testExportCssEndpointIsADeprecatedNoOp(): void
    {
        $controller = new ExportCss($this->createStub(Context::class), $this->jsonFactory());
        $controller->execute();

        $this->assertTrue($this->data['success']);
        $this->assertStringContainsString('theme-config.json', $this->data['message']);
    }

    public function testControllersRequireThemeCustomizerAcl(): void
    {
        $this->assertSame('Panth_ThemeCustomizer::config', Ajax::ADMIN_RESOURCE);
        $this->assertSame('Panth_ThemeCustomizer::config', ExportCss::ADMIN_RESOURCE);
    }
}
