<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Block\Adminhtml;

use Magento\Framework\Escaper;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Module\Dir\Reader as ModuleReader;
use Panth\ThemeCustomizer\Block\Adminhtml\Documentation;
use PHPUnit\Framework\TestCase;

class DocumentationTest extends TestCase
{
    private function block(?string $readme, ?\Exception $readerError = null): Documentation
    {
        $reader = $this->createStub(ModuleReader::class);
        if ($readerError !== null) {
            $reader->method('getModuleDir')->willThrowException($readerError);
        } else {
            $reader->method('getModuleDir')->willReturnCallback(
                static fn($type, $module) => $module === 'Panth_ThemeCustomizer' ? '/modules/theme' : '/elsewhere'
            );
        }
        $driver = $this->createStub(File::class);
        $driver->method('isExists')->willReturnCallback(
            static fn($path) => $readme !== null && $path === '/modules/theme/README.md'
        );
        $driver->method('fileGetContents')->willReturn((string)$readme);

        $escape = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        $escaper = $this->createStub(Escaper::class);
        $escaper->method('escapeHtml')->willReturnCallback($escape);
        $escaper->method('escapeHtmlAttr')->willReturnCallback($escape);
        $escaper->method('escapeUrl')->willReturnCallback($escape);

        $block = (new \ReflectionClass(Documentation::class))->newInstanceWithoutConstructor();
        foreach (['moduleReader' => $reader, 'fileDriver' => $driver, '_escaper' => $escaper] as $name => $value) {
            (new \ReflectionProperty($block, $name))->setValue($block, $value);
        }
        return $block;
    }

    private function render(string $markdown): string
    {
        return $this->block($markdown)->getReadmeContent();
    }

    public function testMissingReadmeShowsNotFound(): void
    {
        $this->assertSame('<p>Documentation not found.</p>', $this->block(null)->getReadmeContent());
    }

    public function testReaderErrorIsEscapedIntoMessage(): void
    {
        $html = $this->block('x', new \RuntimeException('bad <path>'))->getReadmeContent();

        $this->assertSame('<p>Error loading documentation: bad &lt;path&gt;</p>', $html);
    }

    public function testFirstH1IsSkippedAndLaterOnesRendered(): void
    {
        $this->assertSame(
            "<p>Hello</p>\n<h1 id=\"second\">Second</h1>",
            $this->render("# Module Title\n\nHello\n\n# Second")
        );
    }

    public function testHeadingsGetSlugIdsAndLoseTrailingHashes(): void
    {
        $this->assertSame(
            "<h2 id=\"install-setup-guide\">Install &amp; Setup Guide!</h2>\n<h3 id=\"notes\">Notes</h3>",
            $this->render("# T\n## Install & Setup Guide!\n### Notes ##")
        );
    }

    public function testParagraphLinesAreJoinedAndCrlfNormalized(): void
    {
        $this->assertSame("<p>line one line two</p>\n<p>next</p>", $this->render("# T\r\n\r\nline one\r\nline two\r\n\r\nnext"));
    }

    public function testFencedCodeIsEscapedAndTagged(): void
    {
        $this->assertSame(
            "<pre><code class=\"language-php\">&lt;?php echo 1;\n  \$a = &#039;**x**&#039;;</code></pre>",
            $this->render("```php\n<?php echo 1;\n  \$a = '**x**';\n```")
        );
    }

    public function testFenceWithoutLanguageHasNoClass(): void
    {
        $this->assertSame("<pre><code>plain</code></pre>\n<p>after</p>", $this->render("```\nplain\n```\nafter"));
    }

    public function testHorizontalRules(): void
    {
        $this->assertSame("<p>a</p>\n<hr>\n<p>b</p>\n<hr>", $this->render("a\n---\nb\n***"));
    }

    public function testTablesRenderHeaderAndPadMissingCells(): void
    {
        $this->assertSame(
            '<div class="doc-table-wrap"><table class="doc-table"><thead><tr><th>Name</th><th>Value</th></tr>'
            . '</thead><tbody><tr><td>a</td><td><code>b</code></td></tr><tr><td>c</td><td></td></tr></tbody>'
            . '</table></div>',
            $this->render("| Name | Value |\n|---|:---:|\n| a | `b` |\n| c |")
        );
    }

    public function testPipeLineWithoutSeparatorIsAParagraph(): void
    {
        $this->assertSame('<p>| not | a table |</p>', $this->render('| not | a table |'));
    }

    public function testUnorderedListWithContinuationLine(): void
    {
        $this->assertSame('<ul><li>one</li><li>two more</li></ul>', $this->render("- one\n* two\n  more"));
    }

    public function testOrderedListAcceptsDotAndParen(): void
    {
        $this->assertSame('<ol><li>first</li><li>second</li></ol>', $this->render("1. first\n2) second"));
    }

    public function testBlockquoteLinesAreMerged(): void
    {
        $this->assertSame('<blockquote>quoted lines</blockquote>', $this->render("> quoted\n>lines"));
    }

    public function testLinksAllowOnlySafeSchemesAndAnchors(): void
    {
        $this->assertSame(
            '<p><a href="https://x.test/a?b=1&amp;c=2" target="_blank" rel="noopener noreferrer">Docs</a> '
            . '<a href="mailto:a@b.test" target="_blank" rel="noopener noreferrer">Mail</a> '
            . '<a href="#top">Top</a> Bad</p>',
            $this->render('[Docs](https://x.test/a?b=1&c=2) [Mail](mailto:a@b.test) [Top](#top) [Bad](javascript:alert)')
        );
    }

    public function testEmphasisRules(): void
    {
        $this->assertSame(
            '<p><strong>bold</strong> and <em>em</em> but not a*b*c or * spaced *</p>',
            $this->render('**bold** and *em* but not a*b*c or * spaced *')
        );
    }

    public function testCodeSpansAreProtectedAndRawHtmlEscaped(): void
    {
        $this->assertSame(
            '<p>Use <code>**not bold**</code> and <code>&lt;tag&gt;</code> &lt;b&gt;x&lt;/b&gt;</p>',
            $this->render('Use `**not bold**` and `<tag>` <b>x</b>')
        );
    }

    public function testTableOfContentsListsLevelTwoAndThreeOutsideCodeFences(): void
    {
        $toc = $this->block("# T\n## One\n```\n## Not a heading\n```\n### Two `code` **b**\n#### Deep")
            ->getTableOfContents();

        $this->assertSame([
            ['level' => 2, 'text' => 'One', 'id' => 'one'],
            ['level' => 3, 'text' => 'Two code b', 'id' => 'two-code-b'],
        ], $toc);
    }

    public function testTableOfContentsIsEmptyWithoutReadmeOrOnError(): void
    {
        $this->assertSame([], $this->block(null)->getTableOfContents());
        $this->assertSame([], $this->block('## x', new \RuntimeException('fail'))->getTableOfContents());
    }
}
