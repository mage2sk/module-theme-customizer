<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Console\Command;

use Panth\ThemeCustomizer\Console\Command\CheckRequirements;
use Panth\ThemeCustomizer\Console\Command\ExportCss;
use Panth\ThemeCustomizer\Model\BuildExecutor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CheckRequirementsTest extends TestCase
{
    private const ALL_OK = [
        'node_installed' => true,
        'npm_installed' => true,
        'tailwind_dir_exists' => true,
        'package_json_exists' => true,
        'tailwind_dir_writable' => true,
        'tailwind_directory' => '/var/www/app/design/frontend/Acme/shop/web/tailwind',
    ];

    private function runCheck(array $status): CommandTester
    {
        $executor = $this->createStub(BuildExecutor::class);
        $executor->method('getRequirementsStatus')->willReturn($status);
        $tester = new CommandTester(new CheckRequirements($executor));
        $tester->execute([]);
        return $tester;
    }

    public function testAllRequirementsMet(): void
    {
        $tester = $this->runCheck(self::ALL_OK);

        $this->assertSame(Command::SUCCESS, $tester->getStatusCode());
        $display = $tester->getDisplay();
        $this->assertStringContainsString('All requirements met!', $display);
        $this->assertStringContainsString('Path: ' . self::ALL_OK['tailwind_directory'], $display);
        $this->assertStringNotContainsString('NOT', $display);
    }

    public static function failingChecks(): array
    {
        return [
            'node' => ['node_installed', 'Node.js is NOT installed'],
            'npm' => ['npm_installed', 'npm is NOT installed'],
            'directory' => ['tailwind_dir_exists', 'Tailwind directory does NOT exist'],
            'package json' => ['package_json_exists', 'package.json does NOT exist'],
            'writable' => ['tailwind_dir_writable', 'Tailwind directory is NOT writable'],
        ];
    }

    #[DataProvider('failingChecks')]
    public function testAnySingleFailureFailsTheCommand(string $key, string $message): void
    {
        $status = self::ALL_OK;
        $status[$key] = false;
        $tester = $this->runCheck($status);

        $this->assertSame(Command::FAILURE, $tester->getStatusCode());
        $this->assertStringContainsString($message, $tester->getDisplay());
        $this->assertStringContainsString('Some requirements are missing', $tester->getDisplay());
    }

    public function testMissingDirectoryShowsExpectedPath(): void
    {
        $status = self::ALL_OK;
        $status['tailwind_dir_exists'] = false;

        $this->assertStringContainsString(
            'Expected: ' . self::ALL_OK['tailwind_directory'],
            $this->runCheck($status)->getDisplay()
        );
    }

    public function testDeprecatedExportCommandPointsToBuildCommand(): void
    {
        $command = new ExportCss();
        $tester = new CommandTester($command);

        $this->assertSame('panth:theme:export-css', $command->getName());
        $this->assertSame(Command::SUCCESS, $tester->execute([]));
        $this->assertStringContainsString('theme:customizer:build', $tester->getDisplay());
    }
}
