<?php
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Test\Unit\Etc;

use PHPUnit\Framework\TestCase;

class ModuleConfigFilesTest extends TestCase
{
    private function load(string $relativePath): \SimpleXMLElement
    {
        $path = dirname(__DIR__, 3) . '/' . $relativePath;
        $this->assertTrue(is_file($path), $relativePath . ' is missing');

        $previous = libxml_use_internal_errors(true);
        $xml = simplexml_load_string((string) file_get_contents($path));
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $this->assertInstanceOf(\SimpleXMLElement::class, $xml, $relativePath . ' is not valid XML');

        return $xml;
    }

    private function field(\SimpleXMLElement $xml, string $section, string $group, string $field): \SimpleXMLElement
    {
        $nodes = $xml->xpath(sprintf(
            '/config/system/section[@id="%s"]/group[@id="%s"]/field[@id="%s"]',
            $section,
            $group,
            $field
        ));
        $this->assertNotEmpty($nodes, sprintf('%s/%s/%s is not declared', $section, $group, $field));

        return $nodes[0];
    }

    public function testThemeConfigurationGroupHasAFieldSoItIsRendered(): void
    {
        $xml = $this->load('etc/adminhtml/system.xml');
        $field = $this->field($xml, 'theme_customizer', 'general', 'theme_config_info');

        $this->assertSame('label', (string) $field['type']);
        $this->assertSame('1', (string) $field['showInDefault']);
        $this->assertSame('0', (string) $field['showInWebsite']);
        $this->assertSame('0', (string) $field['showInStore']);
        $this->assertStringContainsString('theme-config.json', (string) $field->comment);
        $this->assertStringContainsString('npm run build', (string) $field->comment);
    }

    public function testFreeShippingThresholdRejectsNegativeValues(): void
    {
        $xml = $this->load('etc/adminhtml/system.xml');
        $rules = explode(' ', (string) $this->field($xml, 'panth_header', 'minicart', 'free_shipping_threshold')->validate);

        $this->assertContains('validate-number', $rules);
        $this->assertContains('validate-zero-or-greater', $rules);
    }

    public function testFreeShippingFieldsDependOnTheEnableFlag(): void
    {
        $xml = $this->load('etc/adminhtml/system.xml');
        foreach (['free_shipping_threshold', 'free_shipping_message', 'free_shipping_success_message'] as $id) {
            $field = $this->field($xml, 'panth_header', 'minicart', $id);
            $this->assertSame('1', (string) $field->depends->field, $id);
            $this->assertSame('free_shipping_enabled', (string) $field->depends->field['id'], $id);
        }
    }

    public function testCustomCssAndGoogleFontsRenderInTheHead(): void
    {
        $xml = $this->load('view/frontend/layout/default.xml');
        $head = $xml->xpath('/page/body/referenceBlock[@name="head.additional"]/block');
        $names = array_map(static fn ($block) => (string) $block['name'], $head);

        $this->assertSame(
            ['theme.customizer.google.fonts', 'theme.customizer.luma.tokens', 'theme.customizer.custom.css'],
            $names
        );
        $this->assertSame('theme.customizer.google.fonts', (string) $head[1]['after']);
        $this->assertSame('theme.customizer.luma.tokens', (string) $head[2]['after']);
        $this->assertEmpty($xml->xpath('//referenceContainer[@name="after.body.start"]'));
    }

    public function testLumaTokensBlockUsesThemeCheckAndIsRemovedOnHyva(): void
    {
        $xml = $this->load('view/frontend/layout/default.xml');
        $block = $xml->xpath('//block[@name="theme.customizer.luma.tokens"]');

        $this->assertCount(1, $block);
        $this->assertSame('Panth\\ThemeCustomizer\\Block\\LumaTokens', (string) $block[0]['class']);
        $this->assertSame('Panth_ThemeCustomizer::luma-tokens.phtml', (string) $block[0]['template']);

        $hyva = $this->load('view/frontend/layout/default_hyva.xml');
        $removed = $hyva->xpath('//referenceBlock[@name="theme.customizer.luma.tokens"][@remove="true"]');
        $this->assertCount(1, $removed);
    }

    public function testDefaultFreeShippingThresholdMatchesHelperFallback(): void
    {
        $xml = $this->load('etc/config.xml');

        $this->assertSame('99', (string) $xml->default->panth_header->minicart->free_shipping_threshold);
        $this->assertStringContainsString(
            '{amount}',
            (string) $xml->default->panth_header->minicart->free_shipping_message
        );
    }
}
