# Panth Theme Customizer

[![Magento 2.4.4 - 2.4.8](https://img.shields.io/badge/Magento-2.4.4%20--%202.4.8-orange)]()
[![PHP 8.1 - 8.4](https://img.shields.io/badge/PHP-8.1%20--%208.4-blue)]()
[![License](https://img.shields.io/badge/License-Proprietary-red)]()

**Backend-driven theme customizer** for Magento 2 with Hyva and Luma
support. Configure Google Fonts, custom CSS, header layout, sticky
header behaviour, top bar announcements, icon visibility, mini-cart
free-shipping progress, and more — all from the admin panel. Visual
styling (colours, spacing, sizing) lives in a `theme-config.json` file
processed by a Node.js / Tailwind CSS build pipeline.

---

## Features

- **Google Fonts loader** — select body and heading fonts from the admin;
  the module injects the correct `<link>` tags automatically.
- **Custom CSS editor** — ACE-powered code editor in the admin with
  beautify, validate, download, and import buttons.
- **Header configuration** — sticky header, scroll-up reveal, top bar
  announcement text, container width, header height.
- **Icon management** — toggle search, account, and mini-cart icons;
  choose counter badge style (circle / pill / square).
- **Mini-cart free-shipping progress** — configurable threshold,
  progress message with `{amount}` placeholder, success message.
- **Tailwind CSS build integration** — CLI commands and an admin AJAX
  button to trigger `npm run build` directly from Magento.
- **Luma compatibility** — SVG header icons, top bar, and account
  dropdown that match Hyva's Lucide icon style on Luma storefronts.
- **theme-config.json architecture** — visual tokens (colours, spacing,
  radii, shadows) are defined in a JSON file and consumed by the
  Node.js build script, keeping the admin UI focused on operational
  settings only.

---

## Installation

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

---

## Requirements

| | Required |
|---|---|
| Magento | 2.4.4 -- 2.4.8 (Open Source / Commerce / Cloud) |
| PHP | 8.1 / 8.2 / 8.3 / 8.4 |
| Panth_Core | ^1.0 (installed automatically via Composer) |
| Node.js | 18+ (for Tailwind CSS builds only) |

---

## Configuration

### Theme Customizer

Open **Stores -> Configuration -> Panth Extensions -> Theme Customizer**.

| Section | What it controls |
|---|---|
| **Google Fonts** | Body font, heading font (loads from Google CDN) |
| **Custom CSS** | Inline `<style>` tag injected on every page |

### Header Configuration

Open **Stores -> Configuration -> Panth Extensions -> Header Configuration**.

| Section | What it controls |
|---|---|
| **General** | Enable custom header, sticky header, scroll-up reveal |
| **Top Bar** | Enable announcement bar, left/right text (HTML supported) |
| **Icons** | Toggle search/account/cart icons, badge style, icon size |
| **Mini Cart** | Free shipping progress bar, threshold, messages |
| **Layout** | Container width, header height |

### Visual styling (theme-config.json)

Colours, typography tokens, spacing, border-radius, and shadows are
configured in:

```
app/design/frontend/Panth/Infotech/web/tailwind/theme-config.json
```

After editing, run `node generate-theme-css.js` then `npm run build` in
the tailwind directory, and flush Magento cache.

---

## CLI Commands

```bash
# Build the Hyva theme (runs npm build)
php bin/magento theme:customizer:build

# Check build requirements (Node.js, npm, directories)
php bin/magento theme:customizer:check
```

---

## Support

| Channel | Contact |
|---|---|
| Email | kishansavaliyakb@gmail.com |
| Website | https://kishansavaliya.com |
| WhatsApp | +91 84012 70422 |

---

## License

Proprietary -- see `LICENSE.txt`. Distribution is restricted to the
Adobe Commerce Marketplace.

---

## About the developer

Built and maintained by **Kishan Savaliya** -- https://kishansavaliya.com.
Builds high-quality, security-focused Magento 2 extensions and themes
for both Hyva and Luma storefronts.
