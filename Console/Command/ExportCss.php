<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * ExportCss CLI Command - DEPRECATED
 *
 * CSS export from database is no longer needed.
 * Theme config is now in theme-config.json, processed by Node.js build script.
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCss extends Command
{
    protected function configure()
    {
        $this->setName('panth:theme:export-css')
            ->setDescription('[DEPRECATED] CSS export is no longer needed - theme config is now in theme-config.json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>DEPRECATED: This command is no longer needed.</comment>');
        $output->writeln('');
        $output->writeln('Theme colors and styles are now configured in theme-config.json');
        $output->writeln('and processed by the Node.js build script.');
        $output->writeln('');
        $output->writeln('To build the theme, run: <info>php bin/magento theme:customizer:build</info>');

        return Command::SUCCESS;
    }
}
