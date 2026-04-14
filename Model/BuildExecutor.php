<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Build Executor - Executes Tailwind build via npm
 *
 * Simplified: No longer exports CSS from database.
 * Theme config is now in theme-config.json, read directly by the Node.js build script.
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Framework\Shell;
use Magento\Framework\View\DesignInterface;
use Panth\Core\Api\ThemeBuildExecutorInterface;
use Psr\Log\LoggerInterface;

class BuildExecutor implements ThemeBuildExecutorInterface
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Shell
     */
    protected $shell;

    /**
     * @var DesignInterface
     */
    protected $design;

    /**
     * @var string Fallback theme path if active theme has no tailwind directory
     */
    protected $fallbackThemePath = 'frontend/Panth/Infotech';

    /**
     * @param Filesystem $filesystem
     * @param CacheManager $cacheManager
     * @param LoggerInterface $logger
     * @param Shell $shell
     * @param DesignInterface $design
     */
    public function __construct(
        Filesystem $filesystem,
        CacheManager $cacheManager,
        LoggerInterface $logger,
        Shell $shell,
        DesignInterface $design
    ) {
        $this->filesystem = $filesystem;
        $this->cacheManager = $cacheManager;
        $this->logger = $logger;
        $this->shell = $shell;
        $this->design = $design;
    }

    /**
     * Run npm build and clear cache.
     *
     * @inheritDoc
     */
    public function exportAndBuild(bool $forceNpmBuild = false): array
    {
        try {
            $this->logger->info('[BuildExecutor] Starting build (theme-config.json mode)');

            // Run npm build
            $this->logger->info('[BuildExecutor] Running npm build...');
            $buildStart = microtime(true);
            $buildResult = $this->buildOnly();
            $buildTime = round((microtime(true) - $buildStart), 2);
            $this->logger->info('[BuildExecutor] npm build completed in ' . $buildTime . ' seconds');

            if (!$buildResult['success']) {
                $this->logger->error('[BuildExecutor] npm build FAILED: ' . $buildResult['message']);
                return array_merge($buildResult, ['stats' => []]);
            }

            // Clear Magento cache
            $this->logger->info('[BuildExecutor] Clearing Magento cache...');
            $this->clearCache();

            $this->logger->info('[BuildExecutor] Build completed successfully');

            return [
                'success' => true,
                'message' => 'Theme built successfully!',
                'output' => $buildResult['output'],
                'stats' => [],
                'npm_build_executed' => true
            ];
        } catch (\Exception $e) {
            $this->logger->error('[BuildExecutor] BUILD EXCEPTION: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Build failed: ' . $e->getMessage(),
                'output' => '',
                'stats' => []
            ];
        }
    }

    /**
     * Export CSS only - DEPRECATED, returns no-op result
     *
     * @return array ['path' => string, 'stats' => array]
     */
    public function exportOnly()
    {
        $this->logger->info('[BuildExecutor] exportOnly() is deprecated - CSS is now generated from theme-config.json');
        return [
            'path' => '',
            'stats' => [],
            'success' => true,
            'message' => 'CSS export is now handled by Node.js reading theme-config.json'
        ];
    }

    /**
     * Build Tailwind only (run npm build)
     *
     * @return array ['success' => bool, 'message' => string, 'output' => string]
     */
    public function buildOnly()
    {
        try {
            // Validate Node.js is available
            if (!$this->validateNodeInstalled()) {
                return [
                    'success' => false,
                    'message' => 'Node.js is not installed or not in PATH. Please install Node.js to build the theme.',
                    'output' => ''
                ];
            }

            // Get tailwind directory path
            $tailwindDir = $this->getTailwindDirectory();

            if (!is_dir($tailwindDir)) {
                return [
                    'success' => false,
                    'message' => 'Tailwind directory not found: ' . $tailwindDir,
                    'output' => ''
                ];
            }

            // Check if package.json exists
            if (!file_exists($tailwindDir . '/package.json')) {
                return [
                    'success' => false,
                    'message' => 'package.json not found in: ' . $tailwindDir,
                    'output' => ''
                ];
            }

            // Detect npm path for cross-environment compatibility
            $npmPath = $this->detectNpmPath();
            if (!$npmPath) {
                return [
                    'success' => false,
                    'message' => 'npm not found. Please install Node.js/npm or check your installation.',
                    'output' => ''
                ];
            }

            // Execute npm run build
            $this->logger->info('Running npm build in: ' . $tailwindDir . ' using npm: ' . $npmPath);

            $npmBinDir = dirname($npmPath);
            $currentPath = getenv('PATH') ?: '';
            $newPath = $npmBinDir . ':' . $currentPath;

            $command = sprintf(
                'cd %s && PATH=%s %s run build',
                escapeshellarg($tailwindDir),
                escapeshellarg($newPath),
                escapeshellarg($npmPath)
            );

            try {
                $outputString = $this->shell->execute($command);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->logger->error('Build failed: ' . $e->getMessage());

                return [
                    'success' => false,
                    'message' => 'Build failed: ' . $e->getMessage(),
                    'output' => $e->getMessage()
                ];
            }

            $this->logger->info('Build completed successfully');

            return [
                'success' => true,
                'message' => 'Build completed successfully',
                'output' => $outputString
            ];
        } catch (\Exception $e) {
            $this->logger->error('Build exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Build exception: ' . $e->getMessage(),
                'output' => ''
            ];
        }
    }

    /**
     * Validate Node.js is installed
     *
     * @return bool
     */
    public function validateNodeInstalled()
    {
        try {
            $this->shell->execute('node --version');
            return true;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return false;
        }
    }

    /**
     * Validate npm is installed
     *
     * @return bool
     */
    public function validateNpmInstalled()
    {
        try {
            $this->shell->execute('npm --version');
            return true;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return false;
        }
    }

    /**
     * Get Tailwind directory path
     *
     * @return string
     */
    protected function getTailwindDirectory()
    {
        $appDir = $this->filesystem->getDirectoryRead(DirectoryList::APP)->getAbsolutePath();

        // Try active theme's tailwind directory first
        $activeThemePath = $this->getActiveThemePath();
        if ($activeThemePath) {
            $activeDir = $appDir . 'design/frontend/' . $activeThemePath . '/web/tailwind';
            if (is_dir($activeDir)) {
                return $activeDir;
            }
        }

        // Fallback to parent theme path
        return $appDir . 'design/' . $this->fallbackThemePath . '/web/tailwind';
    }

    /**
     * Get the active theme path from Magento's design configuration
     *
     * @return string|null
     */
    protected function getActiveThemePath()
    {
        try {
            $theme = $this->design->getDesignTheme();
            return $theme ? $theme->getThemePath() : null;
        } catch (\Exception $e) {
            $this->logger->warning('[BuildExecutor] Could not detect active theme: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Clear Magento cache
     */
    protected function clearCache()
    {
        try {
            $this->logger->info('Clearing Magento cache...');

            $cacheTypes = [
                'config',
                'layout',
                'block_html',
                'full_page'
            ];

            $this->cacheManager->flush($cacheTypes);

            $this->logger->info('Cache cleared successfully');
        } catch (\Exception $e) {
            $this->logger->warning('Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Get build requirements status
     *
     * @return array
     */
    public function getRequirementsStatus()
    {
        $tailwindDir = $this->getTailwindDirectory();

        return [
            'node_installed' => $this->validateNodeInstalled(),
            'npm_installed' => $this->validateNpmInstalled(),
            'tailwind_dir_exists' => is_dir($tailwindDir),
            'package_json_exists' => file_exists($tailwindDir . '/package.json'),
            'tailwind_dir_writable' => is_writable($tailwindDir),
            'tailwind_directory' => $tailwindDir
        ];
    }

    /**
     * Detect npm path with cross-environment compatibility
     *
     * @return string|null
     */
    protected function detectNpmPath()
    {
        // Method 1: Check common NVM installation paths
        $homeDir = getenv('HOME') ?: (getenv('USERPROFILE') ?: '/root');
        $nvmPaths = [
            $homeDir . '/.nvm/versions/node',
            '/usr/local/nvm/versions/node',
        ];

        foreach ($nvmPaths as $nvmBase) {
            if (is_dir($nvmBase)) {
                $versions = glob($nvmBase . '/v*', GLOB_ONLYDIR);
                if (!empty($versions)) {
                    usort($versions, function($a, $b) {
                        return version_compare(basename($b), basename($a));
                    });

                    foreach ($versions as $versionPath) {
                        $npmPath = $versionPath . '/bin/npm';
                        if (file_exists($npmPath)) {
                            $this->logger->info('npm detected via NVM', ['path' => $npmPath]);
                            return $npmPath;
                        }
                    }
                }
            }
        }

        // Method 2: Try 'which npm' command
        try {
            $whichOutput = $this->shell->execute('which npm');
            $whichPath = trim($whichOutput);
            if ($whichPath && file_exists($whichPath)) {
                $this->logger->info('npm detected via which command', ['path' => $whichPath]);
                return $whichPath;
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // which command failed, continue to next method
        }

        // Method 3: Check common system installation paths
        $systemPaths = [
            '/usr/local/bin/npm',
            '/usr/bin/npm',
            '/opt/homebrew/bin/npm',
            '/usr/local/opt/node/bin/npm',
        ];

        foreach ($systemPaths as $path) {
            if (file_exists($path)) {
                $this->logger->info('npm detected in system path', ['path' => $path]);
                return $path;
            }
        }

        // Method 4: Try using PATH environment variable
        $pathEnv = getenv('PATH');
        if ($pathEnv) {
            $paths = explode(':', $pathEnv);
            foreach ($paths as $dir) {
                $npmPath = rtrim($dir, '/') . '/npm';
                if (file_exists($npmPath)) {
                    $this->logger->info('npm detected via PATH env', ['path' => $npmPath]);
                    return $npmPath;
                }
            }
        }

        $this->logger->warning('npm not found in any standard location');
        return null;
    }
}
