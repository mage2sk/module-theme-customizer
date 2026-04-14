<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Color Picker Frontend Model
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ColorPicker extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $value = $element->getData('value') ?: '#000000';
        $elementId = $element->getHtmlId();
        $elementName = $element->getName();

        // Escape all values for safe HTML output
        $escapedId = $this->escapeHtmlAttr($elementId);
        $escapedName = $this->escapeHtmlAttr($elementName);
        $escapedValue = $this->escapeHtmlAttr($value);

        // Create our own input instead of using getElementHtml()
        $html = '<input type="hidden" id="' . $escapedId . '" name="' . $escapedName . '" value="' . $escapedValue . '" class="input-text admin__control-text" />';

        $html .= '
        <div class="color-picker-container" style="display: flex; gap: 10px; align-items: center; margin-top: 5px;">
            <div id="preview_' . $escapedId . '" class="color-preview" style="width: 50px; height: 50px; border: 2px solid #ccc; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
            <input type="color" id="picker_' . $escapedId . '" style="position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;">
            <div style="flex: 1; display: flex; flex-direction: column; gap: 5px;">
                <input type="text" id="display_' . $escapedId . '" style="font-family: monospace; width: 100%; max-width: 250px; padding: 10px 12px; border: 1px solid #ccc; border-radius: 4px; background: #fff; font-size: 13px; font-weight: 500; color: #333;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <small style="color: #666;">Click the color box to pick a color or type hex code directly (e.g., #3B82F6)</small>
                    <span id="validation_' . $escapedId . '" style="font-size: 12px; font-weight: 600;"></span>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            require(["jquery"], function($) {
                $(document).ready(function() {
                    var elementId = "' . $this->escapeJs($elementId) . '";
                    var $input = $("#" + elementId);
                    var $display = $("#display_" + elementId);
                    var $preview = $("#preview_" + elementId);
                    var $picker = $("#picker_" + elementId);
                    var $validation = $("#validation_" + elementId);

                    // Function to validate hex color
                    function validateHexColor(color) {
                        if (!color || color.trim() === "") {
                            return { valid: false, message: "Empty value" };
                        }

                        var trimmed = color.trim();

                        // Check if it matches hex format
                        if (trimmed.match(/^#[0-9A-Fa-f]{6}$/i)) {
                            return { valid: true, message: "Valid" };
                        }

                        // Check common mistakes
                        if (trimmed.match(/^[0-9A-Fa-f]{6}$/i)) {
                            return { valid: false, message: "Missing # symbol" };
                        }

                        if (trimmed.match(/^#[0-9A-Fa-f]{3}$/i)) {
                            return { valid: false, message: "Use 6 digits (e.g., #3B82F6)" };
                        }

                        if (trimmed.toLowerCase().indexOf("oklch") !== -1) {
                            return { valid: false, message: "Use HEX format only" };
                        }

                        return { valid: false, message: "Invalid format. Use: #3B82F6" };
                    }

                    // Function to show validation result
                    function showValidation(result) {
                        if (result.valid) {
                            $validation.html("✓ " + result.message).css("color", "#10B981");
                            $display.css("border-color", "#10B981");
                        } else {
                            $validation.html("✗ " + result.message).css("color", "#EF4444");
                            $display.css("border-color", "#EF4444");
                        }
                    }

                    // Function to extract hex from any color format
                    function extractHex(value) {
                        if (value && value.match(/^#[0-9A-Fa-f]{6}$/i)) {
                            return value;
                        }
                        return "#000000";
                    }

                    // Function to update preview and hidden input
                    function updateAll(color) {
                        try {
                            $preview.css("background-color", color || "#000000");
                            $display.val(color);
                            $input.val(color).trigger("change");

                            // Validate the color
                            var validation = validateHexColor(color);
                            showValidation(validation);
                        } catch(e) {
                            $preview.css("background-color", "#000000");
                            $display.val("#000000");
                            $input.val("#000000").trigger("change");
                            showValidation({ valid: false, message: "Error" });
                        }
                    }

                    // Initialize preview with current value
                    var initialValue = $input.val() || "#000000";
                    $display.val(initialValue);
                    $preview.css("background-color", initialValue);
                    $picker.val(extractHex(initialValue));

                    // Initial validation
                    var initialValidation = validateHexColor(initialValue);
                    showValidation(initialValidation);

                    // When color picker changes, use hex directly (no conversion)
                    $picker.on("input change", function() {
                        var hexColor = $(this).val();
                        updateAll(hexColor);
                    });

                    // When text input changes, update everything
                    $display.on("input change blur", function() {
                        var manualColor = $(this).val().trim();
                        if (manualColor) {
                            updateAll(manualColor);
                            // If it is a hex color, update the picker
                            if (manualColor.match(/^#[0-9A-Fa-f]{6}$/i)) {
                                $picker.val(manualColor);
                            }
                        }
                    });

                    // Click preview to open color picker
                    $preview.on("click", function() {
                        $picker.trigger("click");
                    });
                });
            });
        </script>
        <style>
            .color-preview:hover {
                border-color: #666;
                transform: scale(1.05);
                transition: all 0.2s;
            }
        </style>
        ';

        return $html;
    }
}
