<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Model\Config;

class FontFamilyNormalizer
{
    public function normalize($value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        return str_replace('"', "'", $value);
    }

    public function extractFamilyName($value): string
    {
        $value = $this->normalize($value);
        if ($value === '') {
            return '';
        }

        if (preg_match("/^'([^']+)'/", $value, $matches)) {
            return trim($matches[1]);
        }

        $parts = explode(',', $value);

        return trim($parts[0]);
    }
}
