<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 * System Configuration Documentation Block
 */

namespace Panth\ThemeCustomizer\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Filesystem\Driver\File;

class Documentation extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'Panth_ThemeCustomizer::system/config/documentation.phtml';

    /**
     * @var Reader
     */
    protected $moduleReader;

    /**
     * @var File
     */
    protected $fileDriver;

    /**
     * @param Context $context
     * @param Reader $moduleReader
     * @param File $fileDriver
     * @param array $data
     */
    public function __construct(
        Context $context,
        Reader $moduleReader,
        File $fileDriver,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleReader = $moduleReader;
        $this->fileDriver = $fileDriver;
    }

    /**
     * Render element
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Get README content
     *
     * @return string
     */
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
            return '<p class="message message-error">Error loading documentation: '
                . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        return '<p class="message message-warning">Documentation file not found.</p>';
    }

    /**
     * Simple Markdown to HTML converter
     *
     * @param string $markdown
     * @return string
     */
    protected function convertMarkdownToHtml($markdown)
    {
        // Remove front matter if present
        $markdown = preg_replace('/^---\s*\n.*?\n---\s*\n/s', '', $markdown);

        // Convert headers
        $html = preg_replace('/^### (.*?)$/m', '<h3>$1</h3>', $markdown);
        $html = preg_replace('/^## (.*?)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.*?)$/m', '<h1>$1</h1>', $html);

        // Convert bold and italic
        $html = preg_replace('/\*\*\*(.+?)\*\*\*/s', '<strong><em>$1</em></strong>', $html);
        $html = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $html);
        $html = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $html);
        $html = preg_replace('/_(.+?)_/s', '<em>$1</em>', $html);

        // Convert inline code
        $html = preg_replace('/`([^`]+)`/', '<code>$1</code>', $html);

        // Convert code blocks
        $html = preg_replace_callback('/```(\w*)\n(.*?)\n```/s', function($matches) {
            $lang = $matches[1] ?: 'text';
            $code = htmlspecialchars($matches[2]);
            return '<pre class="code-block"><code class="language-' . $lang . '">' . $code . '</code></pre>';
        }, $html);

        // Convert links
        $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank">$1</a>', $html);

        // Convert lists
        $html = preg_replace_callback('/((?:^[\*\-\+] .+$\n?)+)/m', function($matches) {
            $items = preg_replace('/^[\*\-\+] (.+)$/m', '<li>$1</li>', $matches[1]);
            return '<ul>' . $items . '</ul>';
        }, $html);

        $html = preg_replace_callback('/((?:^\d+\. .+$\n?)+)/m', function($matches) {
            $items = preg_replace('/^\d+\. (.+)$/m', '<li>$1</li>', $matches[1]);
            return '<ol>' . $items . '</ol>';
        }, $html);

        // Convert blockquotes
        $html = preg_replace('/^> (.+)$/m', '<blockquote>$1</blockquote>', $html);

        // Convert horizontal rules
        $html = preg_replace('/^(---|\*\*\*|___)$/m', '<hr>', $html);

        // Convert paragraphs
        $html = preg_replace('/\n\n+/', '</p><p>', $html);
        $html = '<p>' . $html . '</p>';

        // Clean up empty paragraphs
        $html = preg_replace('/<p>(\s*)<\/p>/', '', $html);
        $html = preg_replace('/<p>(\s*<(?:h[1-6]|ul|ol|pre|blockquote|hr))/s', '$1', $html);
        $html = preg_replace('/(<\/(?:h[1-6]|ul|ol|pre|blockquote|hr)>)\s*<\/p>/s', '$1', $html);

        return $html;
    }
}
