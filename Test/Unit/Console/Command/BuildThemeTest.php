<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Console\Command;

use Panth\ThemeCustomizer\Console\Command\BuildTheme;
use Panth\ThemeCustomizer\Model\BuildExecutor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class BuildThemeTest extends TestCase
{
    private function tester(BuildExecutor $executor): CommandTester
    {
        return new CommandTester(new BuildTheme($executor));
    }

    public function testCommandIsConfigured(): void
    {
        $command = new BuildTheme($this->createStub(BuildExecutor::class));

        $this->assertSame('theme:customizer:build', $command->getName());
        $this->assertTrue($command->getDefinition()->hasOption('force'));
        $this->assertSame('f', $command->getDefinition()->getOption('force')->getShortcut());
    }

    public function testSuccessfulBuildPrintsOutputAndReturnsSuccess(): void
    {
        $executor = $this->createMock(BuildExecutor::class);
        $executor->expects($this->once())
            ->method('buildOnly')
            ->willReturn(['success' => true, 'message' => 'ok', 'output' => 'compiled 12 files']);
        $tester = $this->tester($executor);

        $this->assertSame(Command::SUCCESS, $tester->execute([]));
        $display = $tester->getDisplay();
        $this->assertStringContainsString('Build completed successfully!', $display);
        $this->assertStringContainsString('compiled 12 files', $display);
        $this->assertStringContainsString('cache:flush', $display);
    }

    public function testSuccessWithoutOutputOmitsOutputSection(): void
    {
        $executor = $this->createStub(BuildExecutor::class);
        $executor->method('buildOnly')->willReturn(['success' => true, 'message' => 'ok', 'output' => '']);
        $tester = $this->tester($executor);

        $this->assertSame(Command::SUCCESS, $tester->execute([]));
        $this->assertStringNotContainsString('Build output:', $tester->getDisplay());
    }

    public function testFailedBuildPrintsMessageAndOutput(): void
    {
        $executor = $this->createStub(BuildExecutor::class);
        $executor->method('buildOnly')->willReturn(
            ['success' => false, 'message' => 'npm not found', 'output' => 'stack trace']
        );
        $tester = $this->tester($executor);

        $this->assertSame(Command::FAILURE, $tester->execute([]));
        $display = $tester->getDisplay();
        $this->assertStringContainsString('Build failed!', $display);
        $this->assertStringContainsString('npm not found', $display);
        $this->assertStringContainsString('stack trace', $display);
        $this->assertStringNotContainsString('Theme built successfully!', $display);
    }

    public function testExceptionIsReportedAsFailure(): void
    {
        $executor = $this->createStub(BuildExecutor::class);
        $executor->method('buildOnly')->willThrowException(new \RuntimeException('kaboom'));
        $tester = $this->tester($executor);

        $this->assertSame(Command::FAILURE, $tester->execute([]));
        $this->assertStringContainsString('Error: kaboom', $tester->getDisplay());
    }
}
