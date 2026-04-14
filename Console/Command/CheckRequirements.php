<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Check Requirements CLI Command
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Console\Command;

use Panth\ThemeCustomizer\Model\BuildExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckRequirements extends Command
{
    /**
     * @var BuildExecutor
     */
    protected $buildExecutor;

    /**
     * @param BuildExecutor $buildExecutor
     * @param string|null $name
     */
    public function __construct(
        BuildExecutor $buildExecutor,
        $name = null
    ) {
        $this->buildExecutor = $buildExecutor;
        parent::__construct($name);
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('theme:customizer:check')
            ->setDescription('Check theme build requirements');
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Theme Customizer Build Requirements Check</info>');
        $output->writeln('═══════════════════════════════════════════════');
        $output->writeln('');

        $status = $this->buildExecutor->getRequirementsStatus();
        $allPassed = true;

        // Check Node.js
        if ($status['node_installed']) {
            $output->writeln('<info>✓ Node.js is installed</info>');
        } else {
            $output->writeln('<error>✗ Node.js is NOT installed or not in PATH</error>');
            $allPassed = false;
        }

        // Check npm
        if ($status['npm_installed']) {
            $output->writeln('<info>✓ npm is installed</info>');
        } else {
            $output->writeln('<error>✗ npm is NOT installed or not in PATH</error>');
            $allPassed = false;
        }

        // Check Tailwind directory
        if ($status['tailwind_dir_exists']) {
            $output->writeln('<info>✓ Tailwind directory exists</info>');
            $output->writeln('  Path: ' . $status['tailwind_directory']);
        } else {
            $output->writeln('<error>✗ Tailwind directory does NOT exist</error>');
            $output->writeln('  Expected: ' . $status['tailwind_directory']);
            $allPassed = false;
        }

        // Check package.json
        if ($status['package_json_exists']) {
            $output->writeln('<info>✓ package.json exists</info>');
        } else {
            $output->writeln('<error>✗ package.json does NOT exist</error>');
            $allPassed = false;
        }

        // Check write permissions
        if ($status['tailwind_dir_writable']) {
            $output->writeln('<info>✓ Tailwind directory is writable</info>');
        } else {
            $output->writeln('<error>✗ Tailwind directory is NOT writable</error>');
            $allPassed = false;
        }

        $output->writeln('');
        $output->writeln('═══════════════════════════════════════════════');

        if ($allPassed) {
            $output->writeln('<info>✓ All requirements met! You can build the theme.</info>');
            $output->writeln('');
            $output->writeln('<comment>Run: php bin/magento theme:customizer:build</comment>');
            return Command::SUCCESS;
        } else {
            $output->writeln('<error>✗ Some requirements are missing. Please fix them before building.</error>');
            $output->writeln('');
            $output->writeln('<comment>Installation instructions:</comment>');
            $output->writeln('1. Install Node.js: https://nodejs.org/');
            $output->writeln('2. Ensure npm is available in PATH');
            $output->writeln('3. Check file/directory permissions');
            return Command::FAILURE;
        }
    }
}
