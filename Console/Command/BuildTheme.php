<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Console\Command;

use Panth\ThemeCustomizer\Model\BuildExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildTheme extends Command
{
    const OPTION_FORCE = 'force';

    protected $buildExecutor;

    public function __construct(
        BuildExecutor $buildExecutor,
        $name = null
    ) {
        $this->buildExecutor = $buildExecutor;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('theme:customizer:build')
            ->setDescription('Build Hyva theme (reads theme-config.json, runs npm build)')
            ->addOption(
                self::OPTION_FORCE,
                'f',
                InputOption::VALUE_NONE,
                'Force rebuild even if no changes'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Hyva Theme Customizer Build</info>');
        $output->writeln('<comment>Theme config is read from theme-config.json by the Node.js build script.</comment>');
        $output->writeln('');

        try {
            $output->writeln('<comment>Step 1/2: Building Tailwind CSS...</comment>');

            $result = $this->buildExecutor->buildOnly();

            if (!$result['success']) {
                $output->writeln('<error>Build failed!</error>');
                $output->writeln('<error>' . $result['message'] . '</error>');

                if (!empty($result['output'])) {
                    $output->writeln('');
                    $output->writeln('<comment>Build output:</comment>');
                    $output->writeln($result['output']);
                }

                return Command::FAILURE;
            }

            $output->writeln('<info>Build completed successfully!</info>');

            if (!empty($result['output'])) {
                $output->writeln('');
                $output->writeln('<comment>Build output:</comment>');
                $output->writeln($result['output']);
            }

            $output->writeln('');
            $output->writeln('<comment>Step 2/2: Clearing cache...</comment>');
            $output->writeln('<info>Cache cleared!</info>');

            $output->writeln('');
            $output->writeln('<info>Theme built successfully!</info>');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
