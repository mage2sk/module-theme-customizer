<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Model;

use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Shell;
use Magento\Framework\View\Design\ThemeInterface;
use Magento\Framework\View\DesignInterface;
use Panth\ThemeCustomizer\Model\BuildExecutor;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class BuildExecutorTest extends TestCase
{
    private const SYSTEM_NPM_PATHS = [
        '/usr/local/bin/npm',
        '/usr/bin/npm',
        '/opt/homebrew/bin/npm',
        '/usr/local/opt/node/bin/npm',
    ];

    /**
     * @var string
     */
    private string $root;

    /**
     * @var string|false
     */
    private $originalHome;

    /**
     * @var string|false
     */
    private $originalPath;

    /**
     * @var string[]
     */
    private array $commands = [];

    protected function setUp(): void
    {
        $this->root = sys_get_temp_dir() . '/tc_build_' . uniqid('', true);
        mkdir($this->root . '/app', 0777, true);
        mkdir($this->root . '/home', 0777, true);
        $this->originalHome = getenv('HOME');
        $this->originalPath = getenv('PATH');
        putenv('HOME=' . $this->root . '/home');
    }

    protected function tearDown(): void
    {
        putenv($this->originalHome === false ? 'HOME' : 'HOME=' . $this->originalHome);
        putenv($this->originalPath === false ? 'PATH' : 'PATH=' . $this->originalPath);
        $this->removeDir($this->root);
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } elseif (is_file($item->getPathname())) {
                unlink($item->getPathname());
            }
        }
        rmdir($dir);
    }

    private function touchFile(string $path): void
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        file_put_contents($path, '');
    }

    private function themeDir(string $themePath): string
    {
        $dir = $this->root . '/app/design/frontend/' . $themePath . '/web/tailwind';
        mkdir($dir, 0777, true);
        return $dir;
    }

    private function fakeNvm(array $versions): void
    {
        foreach ($versions as $version) {
            $this->touchFile($this->root . '/home/.nvm/versions/node/' . $version . '/bin/npm');
        }
    }

    private function systemNpmPresent(): bool
    {
        foreach (self::SYSTEM_NPM_PATHS as $path) {
            if (file_exists($path)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array<string, string|\Throwable> $responses prefix => output or exception
     */
    private function shell(array $responses): Shell
    {
        $shell = $this->createStub(Shell::class);
        $shell->method('execute')->willReturnCallback(function ($command) use ($responses) {
            $this->commands[] = $command;
            foreach ($responses as $needle => $response) {
                if (strpos($command, $needle) !== false) {
                    if ($response instanceof \Throwable) {
                        throw $response;
                    }
                    return $response;
                }
            }
            throw new LocalizedException(new Phrase('command not found'));
        });
        return $shell;
    }

    private function design(?string $themePath, bool $throws = false): DesignInterface
    {
        $design = $this->createStub(DesignInterface::class);
        if ($throws) {
            $design->method('getDesignTheme')->willThrowException(new \RuntimeException('no area'));
            return $design;
        }
        $theme = null;
        if ($themePath !== null) {
            $theme = $this->createStub(ThemeInterface::class);
            $theme->method('getThemePath')->willReturn($themePath);
        }
        $design->method('getDesignTheme')->willReturn($theme);
        return $design;
    }

    private function executor(
        Shell $shell,
        ?DesignInterface $design = null,
        ?LoggerInterface $logger = null,
        ?CacheManager $cacheManager = null
    ): BuildExecutor {
        $read = $this->createStub(ReadInterface::class);
        $read->method('getAbsolutePath')->willReturn($this->root . '/app/');
        $filesystem = $this->createStub(Filesystem::class);
        $filesystem->method('getDirectoryRead')->willReturn($read);

        return new BuildExecutor(
            $filesystem,
            $cacheManager ?? $this->createStub(CacheManager::class),
            $logger ?? $this->createStub(LoggerInterface::class),
            $shell,
            $design ?? $this->design('Acme/shop')
        );
    }

    public function testValidateNodeAndNpmReflectShellOutcome(): void
    {
        $ok = $this->executor($this->shell(['node --version' => 'v20.1.0', 'npm --version' => '10.0.0']));
        $this->assertTrue($ok->validateNodeInstalled());
        $this->assertTrue($ok->validateNpmInstalled());

        $missing = $this->executor($this->shell([]));
        $this->assertFalse($missing->validateNodeInstalled());
        $this->assertFalse($missing->validateNpmInstalled());
    }

    public function testBuildFailsWhenNodeIsMissing(): void
    {
        $result = $this->executor($this->shell([]))->buildOnly();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Node.js is not installed', $result['message']);
        $this->assertSame(['node --version'], $this->commands);
    }

    public function testBuildFallsBackToDefaultThemeWhenActiveThemeHasNoTailwindDir(): void
    {
        $result = $this->executor($this->shell(['node --version' => 'v20']))->buildOnly();

        $this->assertFalse($result['success']);
        $this->assertSame(
            'Tailwind directory not found: ' . $this->root . '/app/design/frontend/Panth/Infotech/web/tailwind',
            $result['message']
        );
    }

    public function testBuildFallsBackToDefaultThemeWhenThemeDetectionThrows(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('warning')
            ->with($this->stringContains('Could not detect active theme: no area'));
        $this->themeDir('Acme/shop');

        $result = $this->executor($this->shell(['node --version' => 'v20']), $this->design(null, true), $logger)
            ->buildOnly();

        $this->assertStringContainsString('frontend/Panth/Infotech/web/tailwind', $result['message']);
    }

    public function testBuildFallsBackWhenNoThemeIsActive(): void
    {
        $this->themeDir('Acme/shop');
        $result = $this->executor($this->shell(['node --version' => 'v20']), $this->design(null))->buildOnly();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('frontend/Panth/Infotech/web/tailwind', $result['message']);
    }

    public function testBuildFailsWhenPackageJsonIsMissing(): void
    {
        $dir = $this->themeDir('Acme/shop');
        $result = $this->executor($this->shell(['node --version' => 'v20']))->buildOnly();

        $this->assertFalse($result['success']);
        $this->assertSame('package.json not found in: ' . $dir, $result['message']);
    }

    public function testBuildRunsNpmFromNewestNvmVersion(): void
    {
        $dir = $this->themeDir('Acme/shop');
        $this->touchFile($dir . '/package.json');
        $this->fakeNvm(['v9.11.2', 'v20.11.0', 'v18.2.0']);
        $npm = $this->root . '/home/.nvm/versions/node/v20.11.0/bin/npm';

        $result = $this->executor($this->shell(['node --version' => 'v20', 'run build' => 'BUILD OK']))->buildOnly();

        $this->assertTrue($result['success']);
        $this->assertSame('BUILD OK', $result['output']);
        $buildCommand = end($this->commands);
        $this->assertStringStartsWith('cd ' . escapeshellarg($dir) . ' && PATH=', $buildCommand);
        $this->assertStringContainsString("PATH='" . dirname($npm) . ':', $buildCommand);
        $this->assertStringEndsWith(escapeshellarg($npm) . ' run build', $buildCommand);
    }

    public function testBuildReportsNpmFailureOutput(): void
    {
        $dir = $this->themeDir('Acme/shop');
        $this->touchFile($dir . '/package.json');
        $this->fakeNvm(['v20.0.0']);

        $result = $this->executor($this->shell([
            'node --version' => 'v20',
            'run build' => new LocalizedException(new Phrase('tailwind exploded')),
        ]))->buildOnly();

        $this->assertFalse($result['success']);
        $this->assertSame('Build failed: tailwind exploded', $result['message']);
        $this->assertSame('tailwind exploded', $result['output']);
    }

    public function testBuildCatchesUnexpectedExceptions(): void
    {
        $dir = $this->themeDir('Acme/shop');
        $this->touchFile($dir . '/package.json');
        $this->fakeNvm(['v20.0.0']);

        $result = $this->executor($this->shell([
            'node --version' => 'v20',
            'run build' => new \RuntimeException('disk full'),
        ]))->buildOnly();

        $this->assertFalse($result['success']);
        $this->assertSame('Build exception: disk full', $result['message']);
    }

    public function testNpmIsDetectedThroughWhichWhenNvmIsAbsent(): void
    {
        $dir = $this->themeDir('Acme/shop');
        $this->touchFile($dir . '/package.json');
        $npm = $this->root . '/bin/npm';
        $this->touchFile($npm);

        $result = $this->executor($this->shell([
            'node --version' => 'v20',
            'which npm' => $npm . "\n",
            'run build' => 'done',
        ]))->buildOnly();

        $this->assertTrue($result['success']);
        $this->assertStringEndsWith(escapeshellarg($npm) . ' run build', end($this->commands));
    }

    public function testNpmIsDetectedThroughPathEnvironment(): void
    {
        if ($this->systemNpmPresent()) {
            $this->markTestSkipped('A system npm binary would take precedence over PATH lookup.');
        }
        $dir = $this->themeDir('Acme/shop');
        $this->touchFile($dir . '/package.json');
        $npm = $this->root . '/pathbin/npm';
        $this->touchFile($npm);
        putenv('PATH=' . $this->root . '/nowhere:' . $this->root . '/pathbin/');

        $result = $this->executor($this->shell(['node --version' => 'v20', 'run build' => 'done']))->buildOnly();

        $this->assertTrue($result['success']);
        $this->assertStringEndsWith(escapeshellarg($npm) . ' run build', end($this->commands));
    }

    public function testBuildFailsWhenNpmCannotBeFound(): void
    {
        if ($this->systemNpmPresent()) {
            $this->markTestSkipped('A system npm binary is installed.');
        }
        $dir = $this->themeDir('Acme/shop');
        $this->touchFile($dir . '/package.json');
        putenv('PATH=' . $this->root . '/nowhere');

        $result = $this->executor($this->shell(['node --version' => 'v20']))->buildOnly();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('npm not found', $result['message']);
        $this->assertCount(0, array_filter($this->commands, static fn($c) => strpos($c, 'run build') !== false));
    }

    private function partialExecutor(CacheManager $cacheManager, ?LoggerInterface $logger = null): BuildExecutor
    {
        $read = $this->createStub(ReadInterface::class);
        $read->method('getAbsolutePath')->willReturn($this->root . '/app/');
        $filesystem = $this->createStub(Filesystem::class);
        $filesystem->method('getDirectoryRead')->willReturn($read);

        return $this->getMockBuilder(BuildExecutor::class)
            ->setConstructorArgs([
                $filesystem,
                $cacheManager,
                $logger ?? $this->createStub(LoggerInterface::class),
                $this->createStub(Shell::class),
                $this->createStub(DesignInterface::class),
            ])
            ->onlyMethods(['buildOnly'])
            ->getMock();
    }

    public function testExportAndBuildFlushesCachesOnSuccess(): void
    {
        $cache = $this->createMock(CacheManager::class);
        $cache->expects($this->once())
            ->method('flush')
            ->with(['config', 'layout', 'block_html', 'full_page']);
        $executor = $this->partialExecutor($cache);
        $executor->expects($this->once())->method('buildOnly')->willReturn(['success' => true, 'message' => 'ok', 'output' => 'log']);

        $result = $executor->exportAndBuild(true);

        $this->assertSame([
            'success' => true,
            'message' => 'Theme built successfully!',
            'output' => 'log',
            'stats' => [],
            'npm_build_executed' => true,
        ], $result);
    }

    public function testExportAndBuildDoesNotFlushWhenBuildFails(): void
    {
        $cache = $this->createMock(CacheManager::class);
        $cache->expects($this->never())->method('flush');
        $executor = $this->partialExecutor($cache);
        $executor->expects($this->once())->method('buildOnly')->willReturn(['success' => false, 'message' => 'nope', 'output' => 'x']);

        $result = $executor->exportAndBuild();

        $this->assertSame(['success' => false, 'message' => 'nope', 'output' => 'x', 'stats' => []], $result);
    }

    public function testExportAndBuildStillSucceedsWhenCacheFlushFails(): void
    {
        $cache = $this->createStub(CacheManager::class);
        $cache->method('flush')->willThrowException(new \RuntimeException('redis down'));
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('warning')
            ->with('Failed to clear cache: redis down');
        $executor = $this->partialExecutor($cache, $logger);
        $executor->expects($this->once())->method('buildOnly')->willReturn(['success' => true, 'message' => 'ok', 'output' => '']);

        $this->assertTrue($executor->exportAndBuild()['success']);
    }

    public function testExportAndBuildCatchesExceptions(): void
    {
        $executor = $this->partialExecutor($this->createStub(CacheManager::class));
        $executor->expects($this->once())->method('buildOnly')->willThrowException(new \RuntimeException('boom'));

        $result = $executor->exportAndBuild();

        $this->assertFalse($result['success']);
        $this->assertSame('Build failed: boom', $result['message']);
        $this->assertSame('', $result['output']);
    }

    public function testExportOnlyIsANoOp(): void
    {
        $result = $this->executor($this->shell([]))->exportOnly();

        $this->assertTrue($result['success']);
        $this->assertSame('', $result['path']);
        $this->assertSame([], $this->commands);
    }

    public function testRequirementsStatusForReadyTheme(): void
    {
        $dir = $this->themeDir('Acme/shop');
        $this->touchFile($dir . '/package.json');

        $status = $this->executor($this->shell(['node --version' => 'v20']))->getRequirementsStatus();

        $this->assertSame([
            'node_installed' => true,
            'npm_installed' => false,
            'tailwind_dir_exists' => true,
            'package_json_exists' => true,
            'tailwind_dir_writable' => true,
            'tailwind_directory' => $dir,
        ], $status);
    }

    public function testRequirementsStatusForMissingTheme(): void
    {
        $status = $this->executor($this->shell([]))->getRequirementsStatus();

        $this->assertFalse($status['node_installed']);
        $this->assertFalse($status['tailwind_dir_exists']);
        $this->assertFalse($status['package_json_exists']);
        $this->assertFalse($status['tailwind_dir_writable']);
        $this->assertStringEndsWith('/design/frontend/Panth/Infotech/web/tailwind', $status['tailwind_directory']);
    }
}
