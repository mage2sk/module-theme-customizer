<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Tailwind CSS Backend Model
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeList;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

class TailwindCss extends Value
{
    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeList $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeList $cacheTypeList,
        ?AbstractResource $resource = null,
        ?AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->cacheTypeList = $cacheTypeList;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * Validate before save
     *
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();

        if ($value) {
            // Validate balanced braces
            $this->validateBalancedBraces($value);

            // Validate Tailwind directives
            $this->validateTailwindDirectives($value);

            // Validate CSS syntax
            $this->validateCssSyntax($value);

            // Check for dangerous code
            $this->validateSecurity($value);
        }

        return parent::beforeSave();
    }

    /**
     * Validate balanced braces
     *
     * @param string $value
     * @throws LocalizedException
     */
    protected function validateBalancedBraces($value)
    {
        $openBraces = substr_count($value, '{');
        $closeBraces = substr_count($value, '}');

        if ($openBraces !== $closeBraces) {
            throw new LocalizedException(
                __('Invalid CSS: Unbalanced braces. Found %1 opening and %2 closing braces.', $openBraces, $closeBraces)
            );
        }

        $openParens = substr_count($value, '(');
        $closeParens = substr_count($value, ')');

        if ($openParens !== $closeParens) {
            throw new LocalizedException(
                __('Invalid CSS: Unbalanced parentheses. Found %1 opening and %2 closing parentheses.', $openParens, $closeParens)
            );
        }
    }

    /**
     * Validate Tailwind directives
     *
     * @param string $value
     * @throws LocalizedException
     */
    protected function validateTailwindDirectives($value)
    {
        // Check for @theme blocks
        if (preg_match('/@theme\s*{/', $value)) {
            if (!preg_match('/@theme\s*{[^}]*}/s', $value)) {
                throw new LocalizedException(
                    __('Invalid @theme directive: Block must be properly closed.')
                );
            }
        }

        // Check for @utility blocks
        if (preg_match('/@utility\s+[\w-]+\s*{/', $value)) {
            if (!preg_match('/@utility\s+[\w-]+\s*{[^}]*}/s', $value)) {
                throw new LocalizedException(
                    __('Invalid @utility directive: Block must be properly closed.')
                );
            }
        }

        // Disallow dangerous directives
        $disallowedDirectives = ['@import', '@charset', '@namespace'];
        foreach ($disallowedDirectives as $directive) {
            if (stripos($value, $directive) !== false) {
                throw new LocalizedException(
                    __('Directive %1 is not allowed. Use the Tailwind build system instead.', $directive)
                );
            }
        }
    }

    /**
     * Validate basic CSS syntax
     *
     * @param string $value
     * @throws LocalizedException
     */
    protected function validateCssSyntax($value)
    {
        // Check for valid CSS property format (property: value;)
        if (preg_match('/([a-z-]+)\s*:\s*[^;]*;/i', $value)) {
            // Valid CSS properties found
            return;
        }

        // If it contains braces but no valid properties, it might be invalid
        if (preg_match('/\{[^}]*\}/s', $value) && !preg_match('/([a-z-]+)\s*:/i', $value)) {
            throw new LocalizedException(
                __('Invalid CSS: No valid CSS properties found. Expected format: property: value;')
            );
        }

        // Check for multiple semicolons (typo)
        if (preg_match('/;;+/', $value)) {
            throw new LocalizedException(
                __('Invalid CSS: Multiple consecutive semicolons found.')
            );
        }

        // Check for missing semicolons in property blocks
        if (preg_match('/{[^}]*[a-z-]+\s*:[^;{}]+}/is', $value)) {
            throw new LocalizedException(
                __('Invalid CSS: Missing semicolon after CSS property value.')
            );
        }
    }

    /**
     * Validate security - check for dangerous code
     *
     * @param string $value
     * @throws LocalizedException
     */
    protected function validateSecurity($value)
    {
        $dangerousPatterns = [
            '/<script/i' => 'Script tags',
            '/javascript:/i' => 'JavaScript URLs',
            '/<iframe/i' => 'IFrame tags',
            '/onerror=/i' => 'Event handlers',
            '/onclick=/i' => 'Event handlers',
            '/onload=/i' => 'Event handlers',
            '/eval\s*\(/i' => 'Eval functions',
            '/expression\s*\(/i' => 'CSS expressions',
        ];

        foreach ($dangerousPatterns as $pattern => $description) {
            if (preg_match($pattern, $value)) {
                throw new LocalizedException(
                    __('Security violation: %1 are not allowed in CSS.', $description)
                );
            }
        }
    }

    /**
     * After save - clear cache
     *
     * @return $this
     */
    public function afterSave()
    {
        // Clear view_preprocessed cache when CSS is updated
        $this->cacheTypeList->invalidate('layout');
        $this->cacheTypeList->invalidate('block_html');
        $this->cacheTypeList->invalidate('full_page');

        return parent::afterSave();
    }
}
