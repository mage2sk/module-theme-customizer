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
            $modulePath = $this->moduleReader->getModuleDir('', 'Panth_ThemeCustomizer');
            $readmePath = $modulePath . '/README.md';

            if ($this->fileDriver->isExists($readmePath)) {
                $content = $this->fileDriver->fileGetContents($readmePath);
                return $this->convertMarkdownToHtml($content);
            }
        } catch (\Exception $e) {
            return '<p>Error loading documentation: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        return '<p>Documentation not found.</p>';
    }

    protected function convertMarkdownToHtml($markdown)
    {
        $html = $markdown;

        $html = preg_replace_callback('/^(#{1,6})\s+(.+)$/m', function($matches) {
            $level = strlen($matches[1]);
            $text = trim($matches[2]);
            $id = $this->generateAnchorId($text);
            return sprintf(
                '<h%d id="%s"><a href="#%s" class="anchor-link">%s</a></h%d>',
                $level,
                $id,
                $id,
                $text,
                $level
            );
        }, $html);

        $html = preg_replace('/```(\w+)?\n(.*?)```/s', '<pre><code class="language-$1">$2</code></pre>', $html);
        $html = preg_replace('/`([^`]+)`/', '<code>$1</code>', $html);

        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);

        $html = preg_replace('/^\-\s+(.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);
        $html = preg_replace('/^\d+\.\s+(.+)$/m', '<li>$1</li>', $html);

        $html = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2" target="_blank">$1</a>', $html);

        $html = preg_replace('/\n\n/', '</p><p>', $html);
        $html = '<p>' . $html . '</p>';

        $html = preg_replace('/<p>\s*<\/p>/', '', $html);

        return $html;
    }

    protected function generateAnchorId($text)
    {
        $id = strtolower($text);
        $id = preg_replace('/[^a-z0-9\s-]/', '', $id);
        $id = preg_replace('/\s+/', '-', $id);
        $id = trim($id, '-');
        return $id;
    }

    public function getTableOfContents()
    {
        try {
            $modulePath = $this->moduleReader->getModuleDir('', 'Panth_ThemeCustomizer');
            $readmePath = $modulePath . '/README.md';

            if ($this->fileDriver->isExists($readmePath)) {
                $content = $this->fileDriver->fileGetContents($readmePath);

                preg_match_all('/^(#{1,3})\s+(.+)$/m', $content, $matches, PREG_SET_ORDER);

                $toc = [];
                foreach ($matches as $match) {
                    $level = strlen($match[1]);
                    $text = trim($match[2]);
                    $id = $this->generateAnchorId($text);

                    $toc[] = [
                        'level' => $level,
                        'text' => $text,
                        'id' => $id
                    ];
                }

                return $toc;
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }
}
