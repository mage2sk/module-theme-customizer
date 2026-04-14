/**
 * Copyright © Panth Infotech. All rights reserved.
 * Theme Customizer Config Save with Auto-Build (No Popup)
 */
define([
    'jquery',
    'mage/translate'
], function($, $t) {
    'use strict';

    return function(config) {
        console.log('═══════════════════════════════════════════════════════');
        console.log('[ThemeCustomizer] Initializing config-save-modal.js');
        console.log('[ThemeCustomizer] Timestamp:', new Date().toISOString());
        console.log('[ThemeCustomizer] Config received:', config);
        console.log('═══════════════════════════════════════════════════════');

        // Validate configuration
        if (!config || !config.buildUrl) {
            console.error('[ThemeCustomizer] ❌ ERROR: buildUrl not provided in config');
            return;
        }

        var buildUrl = config.buildUrl;
        console.log('[ThemeCustomizer] ✓ Build URL:', buildUrl);

        // Multiple form selector strategies
        var formSelectors = [
            '#config-edit-form',
            'form[id="config-edit-form"]',
            'form[data-ui-id="config-edit-form"]',
            'form.config-edit'
        ];

        var $form = null;
        var formSelector = null;

        // Try to find the form using multiple selectors
        for (var i = 0; i < formSelectors.length; i++) {
            var selector = formSelectors[i];
            var $testForm = $(selector);
            if ($testForm.length > 0) {
                $form = $testForm;
                formSelector = selector;
                console.log('[ThemeCustomizer] Form found using selector:', selector);
                break;
            }
        }

        if (!$form || $form.length === 0) {
            console.error('[ThemeCustomizer] ERROR: Config form not found. Tried selectors:', formSelectors);
            console.log('[ThemeCustomizer] Available forms:', $('form').map(function() { return this.id || this.className; }).get());
            return;
        }

        console.log('[ThemeCustomizer] Form element:', $form[0]);
        console.log('[ThemeCustomizer] Form action:', $form.attr('action'));

        /**
         * Export backend-config.css
         * STEP 2: Export backend-config.css (ThemeCustomizer)
         */
        function exportAndBuildTheme() {
            console.log('═══════════════════════════════════════════════════════');
            console.log('[ThemeCustomizer] STEP 2: Exporting backend-config.css');
            console.log('[ThemeCustomizer] Timestamp:', new Date().toISOString());
            console.log('═══════════════════════════════════════════════════════');

            // CRITICAL: Prevent any navigation before starting AJAX calls
            window.onbeforeunload = function() {
                console.log('[ThemeCustomizer] ⚠️ BLOCKED navigation attempt - build in progress');
                return 'Build in progress. Please wait.';
            };

            // First, export backend-config.css using NEW lightweight endpoint (no npm build)
            const adminUrl = window.location.origin + window.location.pathname.replace(/\/system_config\/.*$/, '');
            const exportUrl = adminUrl + '/themecustomizer/build/exportcss/key/' + window.FORM_KEY;

            console.log('[ThemeCustomizer] Export URL (CSS only):', exportUrl);
            console.log('[ThemeCustomizer] Form key:', window.FORM_KEY);

            // Show progress message overlay with countdown FIRST
            var $progressOverlay = $('<div>')
                .attr('id', 'theme-build-progress')
                .css({
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%',
                    'background': 'rgba(0, 0, 0, 0.7)',
                    'z-index': '9999',
                    'display': 'flex',
                    'align-items': 'center',
                    'justify-content': 'center',
                    'color': '#fff',
                    'font-size': '18px',
                    'font-family': 'Arial, sans-serif'
                });

            var $progressContent = $('<div>')
                .css({
                    'text-align': 'center',
                    'background': '#333',
                    'padding': '30px 50px',
                    'border-radius': '8px',
                    'box-shadow': '0 4px 20px rgba(0,0,0,0.5)'
                })
                .html(
                    '<div style="font-size: 24px; margin-bottom: 15px;">⚙️ Building Theme CSS...</div>' +
                    '<div style="font-size: 16px; opacity: 0.8;">Configuration saved successfully!</div>' +
                    '<div style="font-size: 16px; opacity: 0.8; margin-top: 10px;">Compiling your changes...</div>' +
                    '<div id="build-countdown" style="font-size: 48px; margin-top: 20px; font-weight: bold; color: #1979c3;">--</div>' +
                    '<div style="margin-top: 20px; font-size: 14px; opacity: 0.6;">Do not close this window</div>'
                );

            $progressOverlay.append($progressContent);
            $('body').append($progressOverlay);

            // Start countdown timer
            var countdown = 60; // Max 60 seconds
            var $countdownEl = $('#build-countdown');
            var countdownInterval = setInterval(function() {
                countdown--;
                $countdownEl.text(countdown + 's');
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    $countdownEl.text('Almost done...');
                }
            }, 1000);

            // STEP 2A: Export backend-config.css first
            $.ajax({
                url: exportUrl,
                type: 'POST',
                data: {
                    force: true,
                    form_key: window.FORM_KEY
                },
                dataType: 'json',
                showLoader: false,
                timeout: 60000,
                beforeSend: function() {
                    console.log('[ThemeCustomizer] ⏳ Exporting backend-config.css...');
                    $countdownEl.text('Exporting CSS...');
                },
                success: function(exportResponse) {
                    console.log('[ThemeCustomizer] ✅ Export completed');
                    console.log('[ThemeCustomizer] Export response:', exportResponse);

                    if (!exportResponse.success) {
                        console.error('[ThemeCustomizer] ❌ Export failed:', exportResponse.message);
                        clearInterval(countdownInterval);

                        // Show export failure message with OK button
                        $progressContent.html(
                            '<div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>' +
                            '<div style="font-size: 24px; margin-bottom: 15px; color: #ff6b6b;">Export Failed</div>' +
                            '<div style="font-size: 14px; opacity: 0.9; margin-bottom: 20px; max-width: 400px; color: #fff;">' +
                            'Failed to export backend-config.css: ' + (exportResponse.message || 'Unknown error') +
                            '</div>' +
                            '<div style="margin-top: 30px;">' +
                            '<button id="reload-export-failed-btn" style="padding: 12px 40px; font-size: 18px; background: #1979c3; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">OK - Reload Page</button>' +
                            '</div>'
                        );

                        // Add click handler for OK button
                        $('#reload-export-failed-btn').on('click', function() {
                            location.reload();
                        });
                        return;
                    }

                    console.log('[ThemeCustomizer] backend-config.css exported successfully');

                    clearInterval(countdownInterval);

                    // Show success message with OK button
                    $progressContent.html(
                        '<div style="font-size: 48px; margin-bottom: 20px;">&#10004;</div>' +
                        '<div style="font-size: 24px; margin-bottom: 15px;">Export Complete!</div>' +
                        '<div style="font-size: 16px; opacity: 0.9; margin-bottom: 20px;">Theme CSS config has been exported successfully.</div>' +
                        '<div style="margin-top: 30px;">' +
                        '<button id="reload-ok-btn" style="padding: 12px 40px; font-size: 18px; background: #1979c3; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">OK - Reload Page</button>' +
                        '</div>'
                    );

                    // Add click handler for OK button
                    $('#reload-ok-btn').on('click', function() {
                        location.reload();
                    });

                    // Auto reload after 3 seconds
                    console.log('[ThemeCustomizer] Auto-reload in 3 seconds or click OK...');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.error('═══════════════════════════════════════════════════════');
                    console.error('[ThemeCustomizer] ❌ Export AJAX ERROR');
                    console.error('[ThemeCustomizer] Status:', status);
                    console.error('[ThemeCustomizer] Error:', error);
                    console.error('[ThemeCustomizer] XHR status:', xhr.status);
                    console.error('[ThemeCustomizer] XHR response:', xhr.responseText);
                    console.error('═══════════════════════════════════════════════════════');

                    clearInterval(countdownInterval);

                    // Show error message with OK button
                    $progressContent.html(
                        '<div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>' +
                        '<div style="font-size: 24px; margin-bottom: 15px; color: #ff6b6b;">Export Error</div>' +
                        '<div style="font-size: 14px; opacity: 0.9; margin-bottom: 20px; max-width: 400px; color: #fff;">' +
                        'Failed to export backend-config.css: ' + error +
                        '</div>' +
                        '<div style="margin-top: 30px;">' +
                        '<button id="reload-export-error-btn" style="padding: 12px 40px; font-size: 18px; background: #1979c3; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">OK - Reload Page</button>' +
                        '</div>'
                    );

                    // Add click handler for OK button
                    $('#reload-export-error-btn').on('click', function() {
                        location.reload();
                    });
                }
            });
        }

        /**
         * Save configuration via AJAX (silently)
         */
        function saveConfig() {
            console.log('───────────────────────────────────────────────────────');
            console.log('[ThemeCustomizer] 🔨 STEP 1: Starting silent config save');
            console.log('[ThemeCustomizer] Timestamp:', new Date().toISOString());
            console.log('───────────────────────────────────────────────────────');

            var formAction = $form.attr('action');
            var formData = $form.serialize();

            console.log('[ThemeCustomizer] Form action URL:', formAction);
            console.log('[ThemeCustomizer] Form data length:', formData.length, 'characters');

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                dataType: 'html',
                showLoader: true, // Show Magento default loader
                timeout: 60000, // 1 minute timeout
                beforeSend: function() {
                    console.log('[ThemeCustomizer] ⏳ Sending config save AJAX request...');
                },
                success: function(response) {
                    console.log('───────────────────────────────────────────────────────');
                    console.log('[ThemeCustomizer] ✅ Config save AJAX response received');
                    console.log('[ThemeCustomizer] Timestamp:', new Date().toISOString());
                    console.log('[ThemeCustomizer] Response length:', response.length, 'characters');
                    console.log('───────────────────────────────────────────────────────');

                    // CRITICAL: Parse response but DO NOT let browser process it
                    // The response contains redirect instructions that would reload the page
                    // We need to prevent that and only reload after CSS build completes

                    // Check if save was successful by looking for error messages
                    var hasError = response.indexOf('message-error') !== -1 ||
                                   response.indexOf('error message') !== -1;

                    if (hasError) {
                        console.error('[ThemeCustomizer] ❌ Config save error detected in response');
                        // Parse error message WITHOUT executing scripts
                        var $response = $('<div>').html(response);  // Wrap in div to prevent script execution
                        var errorMsg = $response.find('.message-error').text().trim() || 'Unknown error';
                        console.error('[ThemeCustomizer] Error message:', errorMsg);
                        alert($t('Config save failed: ') + errorMsg);
                        location.reload();
                    } else {
                        // Config saved successfully, now export backend-config.css and build
                        console.log('[ThemeCustomizer] ✓ Config saved successfully!');
                        console.log('[ThemeCustomizer] ⚠️ NOT reloading page yet - waiting for CSS export and build');
                        console.log('[ThemeCustomizer] 🔄 Starting CSS export and build...');

                        // First export backend-config.css, then build
                        exportAndBuildTheme();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('═══════════════════════════════════════════════════════');
                    console.error('[ThemeCustomizer] ❌ Config save AJAX ERROR');
                    console.error('[ThemeCustomizer] Status:', status);
                    console.error('[ThemeCustomizer] Error:', error);
                    console.error('[ThemeCustomizer] XHR status:', xhr.status);
                    console.error('[ThemeCustomizer] XHR response:', xhr.responseText);
                    console.error('═══════════════════════════════════════════════════════');
                    var errorMessage = $t('Save Error: ') + error;
                    if (status === 'timeout') {
                        errorMessage = $t('Save request timed out. Please try again.');
                    }
                    alert(errorMessage);
                    location.reload();
                }
            });
        }

        /**
         * Intercept form submit
         */
        $form.on('submit', function(e) {
            console.log('═══════════════════════════════════════════════════════');
            console.log('[ThemeCustomizer] 📝 Form submit event triggered');
            console.log('[ThemeCustomizer] Timestamp:', new Date().toISOString());
            console.log('═══════════════════════════════════════════════════════');

            // Check if this is a theme customizer save
            var formAction = $(this).attr('action');
            console.log('[ThemeCustomizer] Form action:', formAction);

            // Multiple ways to detect theme customizer section
            var isThemeCustomizer = false;
            var detectionMethods = [];

            if (formAction) {
                // Check URL for section parameter
                if (formAction.indexOf('section/theme_customizer') !== -1) {
                    isThemeCustomizer = true;
                    detectionMethods.push('URL contains section/theme_customizer');
                }

                // Check URL for encoded section parameter
                if (formAction.indexOf('theme_customizer') !== -1) {
                    isThemeCustomizer = true;
                    detectionMethods.push('URL contains theme_customizer');
                }
            }

            // Check current page URL
            var currentUrl = window.location.href;
            console.log('[ThemeCustomizer] Current page URL:', currentUrl);

            if (currentUrl.indexOf('section/theme_customizer') !== -1 ||
                currentUrl.indexOf('theme_customizer') !== -1) {
                isThemeCustomizer = true;
                detectionMethods.push('Current URL contains theme_customizer');
            }

            // Check for section input field
            var sectionInput = $form.find('input[name="section"]').val();
            console.log('[ThemeCustomizer] Section input value:', sectionInput);

            if (sectionInput === 'theme_customizer') {
                isThemeCustomizer = true;
                detectionMethods.push('Section input field = theme_customizer');
            }

            console.log('[ThemeCustomizer] Is theme customizer form:', isThemeCustomizer);
            console.log('[ThemeCustomizer] Detection methods matched:', detectionMethods);

            if (isThemeCustomizer) {
                console.log('[ThemeCustomizer] ✓ Detected theme_customizer section - intercepting form submission');
                console.log('[ThemeCustomizer] 🚫 Preventing default form submit');
                e.preventDefault();
                e.stopPropagation();

                console.log('[ThemeCustomizer] 🚀 Starting save and build process...');
                // Start save and build process immediately (no modal)
                saveConfig();

                return false;
            } else {
                console.log('[ThemeCustomizer] ⊘ Not a theme customizer form - allowing normal submit');
                console.log('[ThemeCustomizer] This is for section:', sectionInput || 'unknown');
            }
        });

        // Also intercept save button clicks directly
        $form.find('button[id="save"]').on('click', function(e) {
            console.log('═══════════════════════════════════════════════════════');
            console.log('[ThemeCustomizer] 💾 Save button clicked directly');
            console.log('[ThemeCustomizer] Timestamp:', new Date().toISOString());
            console.log('═══════════════════════════════════════════════════════');

            // Check if this is theme customizer page
            var currentUrl = window.location.href;
            var isThemeCustomizer = currentUrl.indexOf('section/theme_customizer') !== -1 ||
                                   currentUrl.indexOf('theme_customizer') !== -1;

            if (isThemeCustomizer) {
                console.log('[ThemeCustomizer] ✓ Theme customizer detected - intercepting button click');
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                // Trigger our custom save process
                saveConfig();
                return false;
            } else {
                console.log('[ThemeCustomizer] ⊘ Not theme customizer - allowing normal click');
            }
        });

        console.log('[ThemeCustomizer] Theme Customizer Save Modal initialized successfully');
        console.log('[ThemeCustomizer] Monitoring form:', formSelector);
    };
});
