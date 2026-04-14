<?php
/**
 * Copyright (c) Panth Infotech. All rights reserved.
 * Tailwind CSS Editor Frontend Model
 */
declare(strict_types=1);

namespace Panth\ThemeCustomizer\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class TailwindCss extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setStyle('font-family: monospace; width: 100%; min-height: 400px;');
        $element->setRows(20);

        $html = $element->getElementHtml();
        $elementId = $element->getHtmlId();
        $escapedId = $this->escapeHtmlAttr($elementId);

        $html .= '
        <div id="' . $escapedId . '_editor" style="width: 100%; min-height: 400px; border: 1px solid #ccc; border-radius: 4px;"></div>
        <div style="margin-top: 10px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <button type="button" id="' . $escapedId . '_beautify" class="action-default scalable">
                <span>Beautify CSS</span>
            </button>
            <button type="button" id="' . $escapedId . '_validate" class="action-default scalable">
                <span>Validate CSS</span>
            </button>
            <button type="button" id="' . $escapedId . '_download" class="action-default scalable" style="background: #2563EB; color: white; border: none;">
                <span>Download CSS</span>
            </button>
            <label for="' . $escapedId . '_import" class="action-default scalable" style="background: #10B981; color: white; border: none; cursor: pointer; margin: 0;">
                <span>Import CSS</span>
            </label>
            <input type="file" id="' . $escapedId . '_import" accept=".css" style="display: none;">
            <span id="' . $escapedId . '_status" style="margin-left: 5px; font-weight: bold;"></span>
        </div>

        <script type="text/javascript">
            require(["jquery"], function($) {
                $(document).ready(function() {
                    var elementId = "' . $this->escapeJs($elementId) . '";
                    var $textarea = $("#" + elementId);
                    var editor = null;

                    // Try to initialize ACE editor if available
                    if (typeof ace !== "undefined") {
                        $textarea.hide();
                        editor = ace.edit(elementId + "_editor");
                        editor.setTheme("ace/theme/monokai");
                        editor.session.setMode("ace/mode/css");
                        editor.setOptions({
                            fontSize: "14px",
                            showPrintMargin: false
                        });
                        editor.setValue($textarea.val() || "", -1);
                        editor.session.on("change", function() {
                            $textarea.val(editor.getValue()).trigger("change");
                        });
                    } else {
                        // Hide the editor div if ACE is not available, show textarea
                        $("#" + elementId + "_editor").hide();
                        $textarea.show();
                    }

                    // Helper to get/set CSS value from editor or textarea
                    function getCssValue() {
                        return editor ? editor.getValue() : $textarea.val();
                    }
                    function setCssValue(val) {
                        if (editor) {
                            editor.setValue(val, -1);
                        }
                        $textarea.val(val).trigger("change");
                    }

                    // Beautify function
                    $("#" + elementId + "_beautify").on("click", function() {
                        try {
                            var css = getCssValue();
                            var beautified = beautifyCSS(css);
                            setCssValue(beautified);
                            showStatus("✓ CSS beautified successfully!", "success");
                        } catch(e) {
                            showStatus("✗ Error beautifying CSS: " + e.message, "error");
                        }
                    });

                    // Validate function
                    $("#" + elementId + "_validate").on("click", function() {
                        try {
                            var css = getCssValue();
                            var result = validateTailwindCSS(css);

                            if (result.valid) {
                                var msg = "✓ " + (result.message || "CSS is valid!");
                                showStatus(msg, "success");

                                // Show warnings if any
                                if (result.warnings && result.warnings.length > 0) {
                                    console.log("CSS Validation Warnings:");
                                    result.warnings.forEach(function(w) {
                                        console.log("  - " + w);
                                    });
                                }
                            } else {
                                showStatus("✗ " + result.error, "error");

                                // Show warnings if any
                                if (result.warnings && result.warnings.length > 0) {
                                    console.log("CSS Validation Warnings:");
                                    result.warnings.forEach(function(w) {
                                        console.log("  - " + w);
                                    });
                                }
                            }
                        } catch(e) {
                            showStatus("✗ Validation error: " + e.message, "error");
                        }
                    });

                    // Download CSS function
                    $("#" + elementId + "_download").on("click", function() {
                        try {
                            var css = getCssValue();
                            var blob = new Blob([css], { type: "text/css" });
                            var url = URL.createObjectURL(blob);
                            var a = document.createElement("a");
                            a.href = url;
                            a.download = "custom-tailwind-" + Date.now() + ".css";
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            URL.revokeObjectURL(url);
                            showStatus("✓ CSS downloaded successfully!", "success");
                        } catch(e) {
                            showStatus("✗ Download failed: " + e.message, "error");
                        }
                    });

                    // Import CSS function
                    $("#" + elementId + "_import").on("change", function(e) {
                        var file = e.target.files[0];
                        if (!file) return;

                        // Validate file type
                        if (!file.name.endsWith(".css")) {
                            showStatus("✗ Must be a .css file", "error");
                            return;
                        }

                        var reader = new FileReader();
                        reader.onload = function(event) {
                            try {
                                var css = event.target.result;

                                // Validate the CSS before importing
                                var validation = validateTailwindCSS(css);

                                if (!validation.valid) {
                                    showStatus("✗ Invalid CSS: " + validation.error, "error");
                                    return;
                                }

                                // Import the CSS
                                setCssValue(css);

                                var msg = "✓ CSS imported successfully!";
                                if (validation.warnings && validation.warnings.length > 0) {
                                    msg += " (with " + validation.warnings.length + " warnings - see console)";
                                    console.log("Import warnings:");
                                    validation.warnings.forEach(function(w) {
                                        console.log("  - " + w);
                                    });
                                }

                                showStatus(msg, "success");

                            } catch(error) {
                                showStatus("✗ Import failed: " + error.message, "error");
                            }
                        };

                        reader.onerror = function() {
                            showStatus("✗ Failed to read file", "error");
                        };

                        reader.readAsText(file);

                        // Reset file input
                        $(this).val("");
                    });

                    function beautifyCSS(css) {
                        if (!css || css.trim() === "") {
                            return "";
                        }

                        try {
                            // Remove all existing formatting
                            var cleaned = css
                                .replace(/\\/\\*[\\s\\S]*?\\*\\//g, function(match) {
                                    return "COMMENT_PLACEHOLDER_" + Math.random().toString(36).substr(2, 9);
                                })
                                .replace(/\\s+/g, " ")
                                .replace(/\\s*{\\s*/g, " {")
                                .replace(/\\s*}\\s*/g, "}")
                                .replace(/\\s*;\\s*/g, ";")
                                .replace(/\\s*,\\s*/g, ", ")
                                .replace(/\\s*:\\s*/g, ": ")
                                .trim();

                            // Format with proper indentation
                            var formatted = "";
                            var indentLevel = 0;
                            var inSelector = false;
                            var buffer = "";

                            for (var i = 0; i < cleaned.length; i++) {
                                var char = cleaned.charAt(i);
                                var nextChar = cleaned.charAt(i + 1);

                                if (char === "{") {
                                    formatted += buffer.trim() + " {\\n";
                                    buffer = "";
                                    indentLevel++;
                                    inSelector = false;
                                } else if (char === "}") {
                                    if (buffer.trim()) {
                                        formatted += "  ".repeat(indentLevel) + buffer.trim() + "\\n";
                                        buffer = "";
                                    }
                                    indentLevel = Math.max(0, indentLevel - 1);
                                    formatted += "  ".repeat(indentLevel) + "}\\n\\n";
                                } else if (char === ";" && indentLevel > 0) {
                                    formatted += "  ".repeat(indentLevel) + buffer.trim() + ";\\n";
                                    buffer = "";
                                } else {
                                    buffer += char;
                                }
                            }

                            // Clean up extra newlines
                            formatted = formatted
                                .replace(/\\n{3,}/g, "\\n\\n")
                                .replace(/\\n+$/g, "\\n")
                                .trim();

                            return formatted;
                        } catch (e) {
                            throw new Error("Beautify failed: " + e.message);
                        }
                    }

                    function validateTailwindCSS(css) {
                        if (!css || css.trim() === "") {
                            return {valid: true, message: "Empty CSS (valid)"};
                        }

                        var errors = [];
                        var warnings = [];

                        // 1. Check for balanced braces
                        var openBraces = (css.match(/{/g) || []).length;
                        var closeBraces = (css.match(/}/g) || []).length;

                        if (openBraces !== closeBraces) {
                            errors.push("Unbalanced braces: " + openBraces + " opening, " + closeBraces + " closing");
                        }

                        // 2. Check for unclosed comments
                        var openComments = (css.match(/\\/\\*/g) || []).length;
                        var closeComments = (css.match(/\\*\\//g) || []).length;

                        if (openComments !== closeComments) {
                            errors.push("Unclosed comments: " + openComments + " open, " + closeComments + " closed");
                        }

                        // 3. Check for unclosed strings
                        var singleQuotes = (css.match(/(?<!\\\\)\\x27/g) || []).length;
                        var doubleQuotes = (css.match(/(?<!\\\\)"/g) || []).length;

                        if (singleQuotes % 2 !== 0) {
                            errors.push("Unclosed single quote detected");
                        }
                        if (doubleQuotes % 2 !== 0) {
                            errors.push("Unclosed double quote detected");
                        }

                        // 4. Check for dangerous patterns (security)
                        var dangerousPatterns = [
                            {pattern: /<script/i, msg: "Script tags not allowed"},
                            {pattern: /javascript:/i, msg: "JavaScript protocol not allowed"},
                            {pattern: /<iframe/i, msg: "Iframe tags not allowed"},
                            {pattern: /expression\\(/i, msg: "CSS expressions not allowed"},
                            {pattern: /@import\\s+url\\(/i, msg: "@import with url() not recommended"}
                        ];

                        for (var i = 0; i < dangerousPatterns.length; i++) {
                            if (dangerousPatterns[i].pattern.test(css)) {
                                errors.push(dangerousPatterns[i].msg);
                            }
                        }

                        // 5. Check for invalid property-value pairs
                        var propertyRegex = /([a-z-]+)\\s*:\\s*([^;]+);/gi;
                        var matches = css.match(propertyRegex);

                        if (matches) {
                            matches.forEach(function(match) {
                                var parts = match.split(":");
                                if (parts.length === 2) {
                                    var property = parts[0].trim();
                                    var value = parts[1].replace(/;$/, "").trim();

                                    // Check for empty values
                                    if (!value || value === "") {
                                        warnings.push("Empty value for property: " + property);
                                    }

                                    // Check for missing semicolons in block
                                    if (match.indexOf("}") > -1) {
                                        warnings.push("Property might be missing semicolon: " + property);
                                    }
                                }
                            });
                        }

                        // 6. Check for common CSS mistakes
                        if (css.indexOf(";;") > -1) {
                            warnings.push("Double semicolons detected");
                        }

                        if (css.indexOf("::") > -1 && css.indexOf("::before") === -1 && css.indexOf("::after") === -1) {
                            warnings.push("Double colons detected (not pseudo-element)");
                        }

                        // 7. Check for proper @ directives
                        var atRules = css.match(/@[a-z-]+/gi);
                        if (atRules) {
                            var validAtRules = ["@theme", "@layer", "@media", "@supports", "@keyframes",
                                                "@font-face", "@import", "@charset", "@namespace",
                                                "@utility", "@variant", "@apply"];

                            atRules.forEach(function(rule) {
                                var ruleLower = rule.toLowerCase();
                                var isValid = validAtRules.some(function(valid) {
                                    return ruleLower === valid.toLowerCase();
                                });

                                if (!isValid) {
                                    warnings.push("Unknown @-rule: " + rule);
                                }
                            });
                        }

                        // 8. Return validation result
                        if (errors.length > 0) {
                            return {
                                valid: false,
                                error: errors.join(" | "),
                                warnings: warnings
                            };
                        }

                        if (warnings.length > 0) {
                            return {
                                valid: true,
                                message: "Valid (with " + warnings.length + " warnings)",
                                warnings: warnings
                            };
                        }

                        return {
                            valid: true,
                            message: "CSS is valid! No errors or warnings found."
                        };
                    }

                    function showStatus(message, type) {
                        var $status = $("#" + elementId + "_status");
                        $status.text(message);
                        $status.css("color", type === "success" ? "#10b981" : "#ef4444");

                        setTimeout(function() {
                            $status.fadeOut(function() {
                                $(this).text("").show();
                            });
                        }, 3000);
                    }
                });
            });
        </script>

        <style>
            #' . $escapedId . '_editor {
                font-size: 14px;
            }
            .ace_editor {
                border: 1px solid #ccc;
                border-radius: 4px;
            }
        </style>
        ';

        return $html;
    }
}
