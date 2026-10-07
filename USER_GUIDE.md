# Panth Theme Customizer -- User Guide

This guide walks store administrators through installing, configuring,
and using the Panth Theme Customizer extension.

---

## Table of contents

1. [Installation](#1-installation)
2. [Verifying the module is active](#2-verifying-the-module-is-active)
3. [Theme Customizer configuration](#3-theme-customizer-configuration)
4. [Header configuration](#4-header-configuration)
5. [Visual styling with theme-config.json](#5-visual-styling-with-theme-configjson)
6. [Building the theme](#6-building-the-theme)
7. [Custom CSS editor](#7-custom-css-editor)
8. [Luma compatibility](#8-luma-compatibility)
9. [CLI reference](#9-cli-reference)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. Installation

### Composer (recommended)

```bash
composer require mage2kishan/module-theme-customizer
bin/magento module:enable Panth_Core Panth_ThemeCustomizer
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual zip

1. Download the extension package zip
2. Extract to `app/code/Panth/ThemeCustomizer`
3. Run the same `module:enable ... cache:flush` commands above

> **Note:** Panth_Core is a required dependency and must also be
> installed. Composer handles this automatically.

---

## 2. Verifying the module is active

```bash
bin/magento module:status Panth_ThemeCustomizer
# Module is enabled
```

You should see **Theme Customizer** and **Header Configuration** entries
under the **Panth Infotech** menu in the admin sidebar, and **Theme
Customizer** and **Header Configuration** sections under the **Panth
Extensions** tab in Stores -> Configuration.

---

## 3. Theme Customizer configuration

Navigate to **Stores -> Configuration -> Panth Extensions -> Theme
Customizer**.

### Google Fonts

Select a body font and/or heading font from the dropdown.

**Load Google Fonts** (default **No**) controls whether the module
requests the selected fonts from Google. When it is **Yes** and the
Body Font or Heading Font is one of the listed Google fonts, the page
head gets preconnect hints for fonts.googleapis.com and
fonts.gstatic.com and one Google Fonts stylesheet link with
`display=swap`. When it is **No**, nothing is loaded from Google; host
the font files in your theme or choose **System Fonts**.

Font values are stored with single quotes, the same as the dropdown
options. Older values saved with double quotes are converted
automatically (once by `setup:upgrade`, and again whenever they are
read or saved), so saving the page without changes keeps your fonts.

Available fonts include Inter, Roboto, Open Sans, Montserrat, Poppins,
Playfair Display, and more. **System Fonts** needs no CDN request.

After selecting a font here, also update the corresponding
`font-family-base` or `font-family-heading` value in
`theme-config.json` so the Tailwind build picks it up.

### Custom CSS

Write standard CSS in the editor. This CSS is output as an inline
`<style>` tag on every storefront page, after the main stylesheet.
Use it for quick overrides without modifying theme files. Any `<`
character in the value is printed as the CSS escape `\3c ` so the value
cannot close the `<style>` tag.

---

## 4. Header configuration

Navigate to **Stores -> Configuration -> Panth Extensions -> Header
Configuration**.

### General

- **Enable Custom Header** -- Use the Panth custom header instead of the
  default theme header. Read by the Panth Hyva child theme templates;
  the Luma blocks in this module do not read it.
- **Enable Sticky Header** -- Header stays fixed at the top when the
  visitor scrolls.
- **Show on Scroll Up** -- Hide the sticky header when scrolling down,
  reveal it when scrolling up.

### Top Bar

- **Enable Top Bar** -- Show an announcement bar above the header.
- **Left Side Text** -- Text shown on the left.
- **Right Side Text** -- Text shown on the right, with a dismiss
  button. Dismissal is remembered for 24 hours via localStorage.

Both texts accept basic HTML (`a`, `b`, `br`, `em`, `i`, `small`,
`span`, `strong`, `u`). Other tags are escaped and shown as text.

### Icons

- Toggle visibility of the search, account, and mini-cart icons. On
  Luma the module's header icons follow these switches; on Hyva the
  child theme header reads them.
- **Cart Counter Badge Style** -- Circle, Pill, or Square.
- **Icon Size** -- Default 24 px.

### Mini Cart / Free Shipping

- **Show Free Shipping Progress** -- Progress bar toward free shipping
  inside the mini-cart sidebar.
- **Free Shipping Threshold** -- Minimum order amount (default: 99). Enter 0 to show the success message for every cart; negative values are rejected.
- **Progress Message** -- Use `{amount}` as a placeholder for the
  remaining amount.
- **Success Message** -- Shown when the threshold is reached.
- **Show Continue Shopping** / **Show Cart Subtotal** -- Toggle these
  mini-cart elements.

### Layout

- **Container Width** -- Container (centred, max-width), Container Fluid
  (full width with padding), or Full Width (edge to edge).
- **Header Height** -- Default 80 px.

---

## 5. Visual styling with theme-config.json

Colours, typography tokens, spacing, border radii, and shadows are
defined in a JSON file:

```
app/design/frontend/Panth/Infotech/web/tailwind/theme-config.json
```

This file is consumed by the Node.js build script. After editing:

1. Run `node generate-theme-css.js` in the tailwind directory
2. Run `npm run build` to rebuild Tailwind CSS
3. Flush Magento cache: `bin/magento cache:flush`

---

## 6. Building the theme

### From the admin

The theme rebuild action provided by Panth_Core runs `npm run build`
through this module and then flushes the config, layout, block_html
and full_page caches.

### From the CLI

```bash
# Full build
php bin/magento theme:customizer:build

# Check requirements first
php bin/magento theme:customizer:check
```

The check command verifies Node.js, npm, the tailwind directory, and
file permissions. The build command does not flush the cache; run
`bin/magento cache:flush` after it.

---

## 7. Custom CSS editor

The admin Custom CSS field is a textarea (shown in an ACE editor only
when ACE is already loaded in the admin) with:

- **Beautify CSS** -- Reformats your CSS with proper indentation.
- **Validate CSS** -- Checks for balanced braces, unclosed comments,
  dangerous patterns (script tags, iframes, JS expressions), and
  unknown @-rules.
- **Download CSS** -- Downloads the current CSS as a `.css` file.
- **Import CSS** -- Uploads a `.css` file into the editor (validated
  before import).

The backend model also validates CSS on save, blocking script and style tags,
JavaScript URLs, event handlers, eval functions, CSS expressions, and
disallowed directives (@import, @charset, @namespace).

---

## 8. Luma compatibility

On Luma storefronts, the module injects:

- **SVG header icons** (search, account, cart) matching Hyva's Lucide
  icon style -- 24x24, stroke-width 1.5.
- **Account dropdown** with login/logout/orders links.
- **Top bar** announcement bar matching the Hyva promo bar design.
- **Cart counter badge** synced from Luma's native minicart counter.

On Hyva storefronts these Luma-specific blocks are automatically
removed via `default_hyva.xml`.

---

## 9. CLI reference

```bash
# Build the theme (runs npm build)
php bin/magento theme:customizer:build
php bin/magento theme:customizer:build --force   # accepted, has no extra effect

# Check build requirements
php bin/magento theme:customizer:check

# Deprecated -- no longer needed
php bin/magento panth:theme:export-css
```

---

## 10. Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| Build fails with "Node.js not installed" | Node.js not in PATH | Install Node.js and ensure it is available to the PHP user |
| Build fails with "package.json not found" | Tailwind directory missing | Verify `app/design/frontend/Panth/Infotech/web/tailwind/` exists with a `package.json` |
| Google Fonts not loading | Load Google Fonts is No, or a system font is selected | Set Load Google Fonts to Yes, select a Google font and refresh the caches |
| Custom CSS not appearing | Cache not refreshed | Refresh the invalidated caches or run `bin/magento cache:flush` after saving |
| Header icons not visible (Luma) | Theme is Hyva-based, or the Luma layout was customised | The Luma icons are removed on Hyva by `default_hyva.xml`; check the `header-wrapper` container exists |

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422

Free email support is provided on a best-effort basis.
