<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Module\Dir\Reader as ModuleReader;
use Magento\Framework\Filesystem\Driver\File;

class Documentation extends Template
{
    protected $moduleReader;

    protected $fileDriver;

    private array $codeSpans = [];

    public function __construct(
        Context $context,
        ModuleReader $moduleReader,
        File $fileDriver,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleReader = $moduleReader;
        $this->fileDriver = $fileDriver;
    }

    public function getReadmeContent()
    {
        try {
            $content = $this->readReadme();
            if ($content !== null) {
                return $this->convertMarkdownToHtml($content);
            }
        } catch (\Exception $e) {
            return '<p>Error loading documentation: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        return '<p>Documentation not found.</p>';
    }

    private function readReadme(): ?string
    {
        $modulePath = $this->moduleReader->getModuleDir('', 'Panth_ThemeCustomizer');
        $readmePath = $modulePath . '/README.md';
        if (!$this->fileDriver->isExists($readmePath)) {
            return null;
        }
        return str_replace(["\r\n", "\r"], "\n", (string)$this->fileDriver->fileGetContents($readmePath));
    }

    protected function convertMarkdownToHtml($markdown)
    {
        $lines = explode("\n", (string)$markdown);
        $count = count($lines);
        $html = [];
        $paragraph = [];
        $skippedTitle = false;
        $i = 0;

        $flush = function () use (&$paragraph, &$html) {
            if ($paragraph !== []) {
                $html[] = '<p>' . $this->renderInline(implode(' ', $paragraph)) . '</p>';
                $paragraph = [];
            }
        };

        while ($i < $count) {
            $line = $lines[$i];
            $trim = trim($line);

            if ($trim === '') {
                $flush();
                $i++;
                continue;
            }

            if (preg_match('/^```\s*([\w-]*)\s*$/', $trim, $m)) {
                $flush();
                $code = [];
                $i++;
                while ($i < $count && !preg_match('/^```\s*$/', trim($lines[$i]))) {
                    $code[] = $lines[$i];
                    $i++;
                }
                $i++;
                $lang = $m[1] !== '' ? ' class="language-' . $this->escapeHtmlAttr($m[1]) . '"' : '';
                $html[] = '<pre><code' . $lang . '>' . $this->escapeHtml(implode("\n", $code)) . '</code></pre>';
                continue;
            }

            if (preg_match('/^(#{1,6})\s+(.+?)\s*#*$/', $trim, $m)) {
                $flush();
                $level = strlen($m[1]);
                $text = trim($m[2]);
                $i++;
                if ($level === 1 && !$skippedTitle) {
                    $skippedTitle = true;
                    continue;
                }
                $id = $this->generateAnchorId($text);
                $html[] = sprintf(
                    '<h%1$d id="%2$s">%3$s</h%1$d>',
                    $level,
                    $this->escapeHtmlAttr($id),
                    $this->renderInline($text)
                );
                continue;
            }

            if (preg_match('/^(-{3,}|\*{3,}|_{3,})$/', $trim)) {
                $flush();
                $html[] = '<hr>';
                $i++;
                continue;
            }

            if ($trim[0] === '|' && $i + 1 < $count && preg_match('/^\|?\s*:?-{2,}:?\s*(\|\s*:?-{2,}:?\s*)*\|?$/', trim($lines[$i + 1]))) {
                $flush();
                $header = $this->splitRow($trim);
                $i += 2;
                $rows = [];
                while ($i < $count && strpos(trim($lines[$i]), '|') === 0) {
                    $rows[] = $this->splitRow(trim($lines[$i]));
                    $i++;
                }
                $table = '<div class="doc-table-wrap"><table class="doc-table"><thead><tr>';
                foreach ($header as $cell) {
                    $table .= '<th>' . $this->renderInline($cell) . '</th>';
                }
                $table .= '</tr></thead><tbody>';
                foreach ($rows as $row) {
                    $table .= '<tr>';
                    foreach ($header as $index => $unused) {
                        $table .= '<td>' . $this->renderInline($row[$index] ?? '') . '</td>';
                    }
                    $table .= '</tr>';
                }
                $html[] = $table . '</tbody></table></div>';
                continue;
            }

            if (preg_match('/^([-*+]|\d+[.)])\s+/', $trim, $m)) {
                $flush();
                $ordered = !in_array($m[1], ['-', '*', '+'], true);
                $items = [];
                while ($i < $count) {
                    $current = trim($lines[$i]);
                    if ($current === '') {
                        break;
                    }
                    if (preg_match($ordered ? '/^\d+[.)]\s+(.*)$/' : '/^[-*+]\s+(.*)$/', $current, $im)) {
                        $items[] = $im[1];
                    } elseif ($items !== []) {
                        $items[count($items) - 1] .= ' ' . $current;
                    } else {
                        break;
                    }
                    $i++;
                }
                $tag = $ordered ? 'ol' : 'ul';
                $list = '<' . $tag . '>';
                foreach ($items as $item) {
                    $list .= '<li>' . $this->renderInline($item) . '</li>';
                }
                $html[] = $list . '</' . $tag . '>';
                continue;
            }

            if (strpos($trim, '>') === 0) {
                $flush();
                $quote = [];
                while ($i < $count && strpos(trim($lines[$i]), '>') === 0) {
                    $quote[] = ltrim(substr(trim($lines[$i]), 1));
                    $i++;
                }
                $html[] = '<blockquote>' . $this->renderInline(implode(' ', $quote)) . '</blockquote>';
                continue;
            }

            $paragraph[] = $trim;
            $i++;
        }
        $flush();

        return implode("\n", $html);
    }

    private function splitRow(string $row): array
    {
        $row = trim($row);
        if (strpos($row, '|') === 0) {
            $row = substr($row, 1);
        }
        if (substr($row, -1) === '|') {
            $row = substr($row, 0, -1);
        }
        return array_map('trim', explode('|', $row));
    }

    private function renderInline(string $text): string
    {
        $this->codeSpans = [];
        $text = preg_replace_callback('/`([^`]+)`/', function ($m) {
            $key = "\x01" . count($this->codeSpans) . "\x02";
            $this->codeSpans[$key] = '<code>' . $this->escapeHtml($m[1]) . '</code>';
            return $key;
        }, $text);

        $links = [];
        $text = preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', function ($m) use (&$links) {
            $key = "\x03" . count($links) . "\x04";
            $label = $this->renderEmphasis($this->escapeHtml($m[1]));
            $url = $m[2];
            if (preg_match('#^(https?://|mailto:)#i', $url)) {
                $links[$key] = '<a href="' . $this->escapeUrl($url) . '" target="_blank" rel="noopener noreferrer">' . $label . '</a>';
            } elseif (strpos($url, '#') === 0) {
                $links[$key] = '<a href="' . $this->escapeHtmlAttr($url) . '">' . $label . '</a>';
            } else {
                $links[$key] = $label;
            }
            return $key;
        }, $text);

        $text = $this->renderEmphasis($this->escapeHtml($text));
        $text = strtr($text, $links);
        return strtr($text, $this->codeSpans);
    }

    private function renderEmphasis(string $html): string
    {
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
        return preg_replace('/(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])/', '<em>$1</em>', $html);
    }

    protected function generateAnchorId($text)
    {
        $id = strtolower((string)$text);
        $id = preg_replace('/[^a-z0-9\s-]/', '', $id);
        $id = preg_replace('/\s+/', '-', $id);
        $id = trim($id, '-');
        return $id;
    }

    public function getTableOfContents()
    {
        try {
            $content = $this->readReadme();
            if ($content === null) {
                return [];
            }
            $content = preg_replace('/^```.*?^```\s*$/ms', '', $content);
            preg_match_all('/^(#{2,3})\s+(.+?)\s*#*$/m', $content, $matches, PREG_SET_ORDER);

            $toc = [];
            foreach ($matches as $match) {
                $text = trim($match[2]);
                $toc[] = [
                    'level' => strlen($match[1]),
                    'text' => trim(preg_replace('/[`*]/', '', $text)),
                    'id' => $this->generateAnchorId($text)
                ];
            }

            return $toc;
        } catch (\Exception $e) {
            return [];
        }
    }
}
