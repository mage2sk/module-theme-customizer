<!-- SEO Meta -->
<!--
  Title: Magento 2 Hyva Theme Customizer: Google Fonts, Custom CSS, Sticky Header, Free Shipping Bar | Panth Infotech
  Description: Panth Theme Customizer lets you configure your Hyva Magento 2 storefront from the admin panel. Control Google Fonts, inject custom CSS, enable sticky header, set a free shipping progress bar in the mini cart, and manage header icons without touching code. Works on Magento 2.4.4 to 2.4.8 and PHP 8.1 to 8.4. Built by Top Rated Plus developer Kishan Savaliya.
  Keywords: magento 2 theme customizer, hyva theme builder, google fonts magento 2, magento 2 custom css, hyva sticky header, free shipping progress bar magento, magento 2 header configuration, hyva theme configuration, magento 2 admin theme editor, magento 2 topbar announcement, hyva tailwind css
  Author: Kishan Savaliya (Panth Infotech)
  Canonical: https://kishansavaliya.com/magento-2-theme-customizer.html
-->

# Magento 2 Hyva Theme Customizer: Google Fonts, Custom CSS, Sticky Header, Free Shipping Bar

[![Magento 2.4.4 - 2.4.8](https://img.shields.io/badge/Magento-2.4.4%20--%202.4.8-orange?logo=magento&logoColor=white)](https://magento.com)
[![PHP 8.1 - 8.4](https://img.shields.io/badge/PHP-8.1%20--%208.4-blue?logo=php&logoColor=white)](https://php.net)
[![Hyva Theme](https://img.shields.io/badge/Themes-Hyva-14b8a6)](https://www.hyva.io)
[![Live Demo & Details](https://img.shields.io/badge/Live%20Demo%20%26%20Details-magento--2--theme--customizer-0D9488?style=flat)](https://kishansavaliya.com/magento-2-theme-customizer.html)
[![Packagist](https://img.shields.io/badge/Packagist-mage2kishan%2Fmodule--theme--customizer-orange?logo=packagist&logoColor=white)](https://packagist.org/packages/mage2kishan/module-theme-customizer)
[![Upwork Top Rated Plus](https://img.shields.io/badge/Upwork-Top%20Rated%20Plus-14a800?logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)
[![Website](https://img.shields.io/badge/Website-kishansavaliya.com-0D9488)](https://kishansavaliya.com)

> **Configure your Hyva storefront from the Magento admin panel.** Panth Theme Customizer lets you load Google Fonts, inject custom CSS, enable a sticky header, show a free shipping progress bar in the mini cart, control header icons, add a top bar announcement strip, and trigger a Tailwind CSS rebuild - all without touching theme files.

**Product page:** [kishansavaliya.com/magento-2-theme-customizer.html](https://kishansavaliya.com/magento-2-theme-customizer.html)

---

## Quick Answer

**What is Panth Theme Customizer?** It is a Magento 2 extension for Hyva storefronts that moves common theme configuration options into the admin panel. You control Google Fonts, custom CSS, header behavior, mini cart settings, and icon visibility without editing code or re-deploying theme files.

**What does it add to my store?**

- **Google Fonts** for body and heading text, loaded via CDN with a single admin dropdown.
- **Custom CSS injection** per store view, output as an inline style tag on every page.
- **Sticky header** with optional scroll-up reveal behavior.
- **Top bar announcement strip** with configurable left and right text blocks.
- **Free shipping progress bar** in the mini cart with a configurable threshold and custom messages.
- **Header icon controls** to show or hide search, account, and mini cart icons, and set icon size and badge style.
- **Tailwind CSS rebuild** triggered from admin via CLI commands or an AJAX button.

**Which themes are supported?** Hyva (Tailwind-based). The module also ships Luma-compatible SVG header icons for stores that mix themes. Core Tailwind token management (colors, spacing, radius) is done through `theme-config.json` in your Hyva child theme.

**What does it need?** Magento 2.4.4 to 2.4.8, PHP 8.1 to 8.4, and the free `mage2kishan/module-core` package.

---

## Need Custom Magento 2 Development?

> **Get a free quote for your project in 24 hours** for custom modules, Hyva themes, performance work, M1 to M2 migrations, and Adobe Commerce Cloud.

<p align="center">
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/Get%20a%20Free%20Quote%20%E2%86%92-Reply%20within%2024%20hours-DC2626?style=for-the-badge" alt="Get a Free Quote" />
  </a>
</p>

<table>
<tr>
<td width="50%" align="center">

### Kishan Savaliya
**Top Rated Plus on Upwork**

[![Hire on Upwork](https://img.shields.io/badge/Hire%20on%20Upwork-Top%20Rated%20Plus-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)

100% Job Success - 10+ Years Magento Experience
Adobe Certified - Hyva Specialist

</td>
<td width="50%" align="center">

### Panth Infotech Agency
**Magento Development Team**

[![Visit Agency](https://img.shields.io/badge/Visit%20Agency-Panth%20Infotech-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/agencies/1881421506131960778/)

Custom Modules - Theme Design - Migrations
Performance - SEO - Adobe Commerce Cloud

</td>
</tr>
</table>

**Visit our website:** [kishansavaliya.com](https://kishansavaliya.com) &nbsp;|&nbsp; **Get a quote:** [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote)

---

## Table of Contents

- [Who Is It For](#who-is-it-for)
- [Key Features](#key-features)
- [Compatibility](#compatibility)
- [Installation](#installation)
- [Configuration](#configuration)
- [How It Works](#how-it-works)
- [FAQ](#faq)
- [Support](#support)
- [About Panth Infotech](#about-panth-infotech)
- [Quick Links](#quick-links)

---

## Who Is It For

- **Merchants on Hyva** who want to change fonts, inject CSS, or toggle header options without asking a developer every time.
- **Designers and marketing teams** who need to adjust the storefront appearance for campaigns or seasonal promotions without a code deploy.
- **Store owners running multiple brands** who need different fonts or custom CSS per store view.
- **Developers setting up Hyva stores** who want a clean admin UI for the most common runtime theme settings, while keeping visual tokens in `theme-config.json`.

---

## Key Features

### Google Fonts

- **Body font selector** - pick any Google Font from the admin dropdown for body text, paragraphs, buttons, and inputs.
- **Heading font selector** - pick a separate font for h1-h6 elements, or leave it to follow the body font.
- **Automatic CDN link injection** - the module generates the optimized Google Fonts `<link>` tag and adds it to `<head>` for you.
- **System fonts option** - set either field to System Fonts to load nothing from CDN, which is the fastest option.

### Custom CSS Injection

- **Per-store CSS textarea** in admin with a built-in ACE editor (beautify, validate, download, import).
- **Backend validation** blocks dangerous patterns like script tags, JavaScript URLs, and event handler attributes.
- **Inline output** as a `<style>` tag after the main stylesheet, so your rules take precedence without a theme deploy.
- **Store-view scoped** - each store view can carry its own CSS block.

### Header Configuration

- **Enable custom header** toggle to switch between the default theme header and the Panth header.
- **Sticky header** with a configurable scroll-up reveal behavior (hide when scrolling down, show when scrolling up).
- **Header height** setting in pixels for the content area.
- **Container width** selector for the header content area.

### Top Bar Announcement Strip

- **Enable top bar** toggle for an announcement bar above the header.
- **Left side text** and **right side text** fields that support HTML for links, bold text, and other inline markup.
- Common use: free shipping threshold message on the left, phone number or store hours on the right.

### Mini Cart and Free Shipping Progress Bar

- **Free shipping progress bar** in the mini cart sidebar showing how close the cart total is to the threshold.
- **Configurable threshold** - set the minimum order amount per store view.
- **Progress message** with an `{amount}` placeholder. Example: `Add {amount} more for free shipping!`
- **Success message** shown when the threshold is reached.
- **Show/hide continue shopping button** in the mini cart.
- **Show/hide cart subtotal** in the mini cart.

### Header Icon Controls

- **Toggle search, account, and mini cart icons** on or off.
- **Cart counter badge style** - choose between circle, pill, or square badge shapes.
- **Icon size** field in pixels (default: 24).

### Tailwind CSS Build Integration

- **`theme:customizer:build` CLI command** triggers the Tailwind build from the shell.
- **`theme:customizer:check` CLI command** verifies the build environment.
- **`theme:customizer:export-css` CLI command** exports compiled CSS.
- **Admin AJAX build button** in the Theme Customizer configuration section.
- **`ThemeBuildExecutorInterface` implementation** so other modules (including Panth Core) can trigger builds through a shared contract.
- **Luma header compatibility** - SVG header icons, account dropdown, and top bar that match Hyva's icon style on Luma storefronts, removed automatically on Hyva via `default_hyva.xml`.

### Architecture

- **`theme-config.json`** in the child theme holds visual tokens (colors, spacing, radii, shadows) consumed by the Node.js Tailwind build script.
- **CSS custom properties** generated from `theme-config.json` for colors and typography, emitted once in `<head>`.
- **Constructor dependency injection only** - no ObjectManager usage.
- **Multi-store scoped** - all admin settings respect default, website, and store view scope.

---

## Compatibility

| Requirement | Versions Supported |
|---|---|
| Magento Open Source | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce Cloud | 2.4.4 to 2.4.8 |
| PHP | 8.1.x, 8.2.x, 8.3.x, 8.4.x |
| Hyva Theme | 1.3+ (required for Tailwind build integration) |
| Node.js | 18.x or 20.x (for Tailwind rebuild, not required at runtime) |
| Required Dependency | `mage2kishan/module-core` (free) |

---

## Installation

### Composer Installation (Recommended)

```bash
composer require mage2kishan/module-theme-customizer
bin/magento module:enable Panth_Core Panth_ThemeCustomizer
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual Installation via ZIP

1. Download the latest release from [Packagist](https://packagist.org/packages/mage2kishan/module-theme-customizer) or from the [product page](https://kishansavaliya.com/magento-2-theme-customizer.html).
2. Extract it to `app/code/Panth/ThemeCustomizer/` in your Magento install.
3. Make sure `Panth_Core` is installed too (required dependency).
4. Run the commands above starting from `bin/magento module:enable`.

### Verify Installation

```bash
bin/magento module:status Panth_ThemeCustomizer
# Expected: Module is enabled
```

After install, open:
```
Admin -> Stores -> Configuration -> Panth Extensions -> Theme Customizer
Admin -> Stores -> Configuration -> Panth Extensions -> Header Configuration
```

---

## Configuration

### Theme Customizer

Go to **Stores -> Configuration -> Panth Extensions -> Theme Customizer**.

| Setting | Group | Default | Description |
|---|---|---|---|
| Body Font | Google Fonts | System Fonts | Google Font for body text. System Fonts loads nothing from CDN. |
| Heading Font | Google Fonts | System Default | Google Font for h1-h6. Leave as System Default to follow body font. |
| Custom CSS | Custom CSS | (empty) | CSS injected as inline `<style>` on every page. Scoped per store view. |

Visual design tokens (colors, spacing, radii, shadows, typography sizes) are configured in `theme-config.json` inside your Hyva child theme's `web/tailwind/` directory. After editing that file, run `node generate-theme-css.js` and `npm run build` in that directory, then flush the cache.

### Header Configuration

Go to **Stores -> Configuration -> Panth Extensions -> Header Configuration**.

| Setting | Group | Default | Description |
|---|---|---|---|
| Enable Custom Header | General | No | Use the Panth custom header instead of the default theme header. |
| Enable Sticky Header | General | No | Header stays fixed at the top when scrolling. |
| Show on Scroll Up | General | No | Hide sticky header when scrolling down, show on scroll up. Requires sticky enabled. |
| Enable Top Bar | Top Bar | No | Show the announcement bar above the header. |
| Left Side Text | Top Bar | (empty) | Left side content. Supports HTML. |
| Right Side Text | Top Bar | (empty) | Right side content. Supports HTML. |
| Show Search Icon | Icons | Yes | Show or hide the search icon in the header. |
| Show Account Icon | Icons | Yes | Show or hide the account icon in the header. |
| Show Mini Cart Icon | Icons | Yes | Show or hide the mini cart icon. |
| Cart Counter Badge Style | Icons | Circle | Shape of the item count badge on the cart icon. |
| Icon Size (px) | Icons | 24 | Size of header icons in pixels. |
| Show Free Shipping Progress | Mini Cart | No | Progress bar toward free shipping in the mini cart sidebar. |
| Free Shipping Threshold | Mini Cart | 99 | Minimum order amount for free shipping. |
| Progress Message | Mini Cart | (template) | Message with `{amount}` placeholder while progress is incomplete. |
| Success Message | Mini Cart | (template) | Message shown when threshold is reached. |
| Show Continue Shopping Button | Mini Cart | Yes | Show or hide the continue shopping button in the mini cart. |
| Show Cart Subtotal | Mini Cart | Yes | Show or hide the cart subtotal in the mini cart. |
| Container Width | Layout | (theme default) | Width of the header content area. |
| Header Height (px) | Layout | 80 | Height of the header in pixels. |

---

## How It Works

1. **Google Fonts** - on each page request, the module reads the admin-selected fonts, builds the Google Fonts CDN URL with `display=swap`, and injects a `<link>` tag into `<head>`. No font files are hosted locally.
2. **Custom CSS** - the admin textarea value is read at render time and output as an inline `<style>` tag after the main stylesheet. Backend validation runs when you save, blocking any patterns that could introduce XSS.
3. **Header and icons** - an Alpine.js-powered header block reads the admin settings via a view model and toggles sticky behavior, scroll-up reveal, and icon visibility at runtime. No jQuery is needed.
4. **Top bar** - rendered as a separate block above the header, with left and right text fields. HTML is allowed so you can include links.
5. **Free shipping bar** - the mini cart block reads the configured threshold, compares it to the current cart subtotal, and renders a progress bar with the configured messages. It updates reactively via Alpine.js when the cart changes.
6. **Tailwind rebuild** - the `BuildExecutor` model runs `npm run build-prod` in the active Hyva child theme's `web/tailwind/` directory and flushes static content. This is available as a CLI command or an admin AJAX button.
7. **`theme-config.json`** - visual tokens (colors, spacing, radii) live here and are read by the Tailwind build script. CSS custom properties generated from this file are emitted in `<head>` so color changes without Tailwind class changes take effect without a rebuild.

---

## FAQ

### Does this work with Luma?

Mostly no. The Google Fonts loader, custom CSS, and free shipping bar work on any theme. The sticky header, top bar, and icon controls are designed for Hyva's Alpine.js architecture. The module ships separate Luma-compatible SVG header icons that are loaded on Luma and removed on Hyva automatically.

### Do I need to run a Tailwind rebuild every time I change something?

No. Google Fonts, custom CSS, sticky header, top bar, icon settings, and free shipping bar all work without a rebuild. Only changes to `theme-config.json` (colors, spacing tokens, radii) that affect Tailwind utility classes require the `npm run build` step.

### Can I have different settings per store view?

Yes. All settings in both configuration sections respect Magento's scope hierarchy: default, website, and store view. Each store view can have its own fonts, custom CSS, free shipping threshold, and header configuration.

### Is the custom CSS safe to use?

The module validates the CSS value before saving. It rejects script tags, JavaScript URLs (`javascript:`), and event handler attributes. Ordinary CSS rules save without restriction.

### Does the free shipping bar work with multi-currency?

Yes. The threshold is stored in your base currency. The comparison to the cart subtotal uses Magento's standard subtotal calculation, which already handles currency conversion for each store view.

### Can I export the Theme Customizer settings?

Yes. Use `bin/magento config:show` and `bin/magento config:set` to move settings between environments. All Theme Customizer paths start with `theme_customizer/*` and all Header Configuration paths start with `panth_header/*`.

### Does this module need Node.js installed on the server?

Only for the Tailwind rebuild commands (`theme:customizer:build`). Runtime features like Google Fonts, custom CSS injection, sticky header, and free shipping bar have no Node.js dependency. If your CI pipeline handles static content deployment, you do not need Node.js on the production server.

### Does Panth Theme Customizer need Panth Core?

Yes. `mage2kishan/module-core` is a free, required dependency. Composer installs it for you automatically.

### Is the source code on GitHub?

Yes. The full source is at [github.com/mage2sk/module-theme-customizer](https://github.com/mage2sk/module-theme-customizer).

---

## Support

| Channel | Contact |
|---|---|
| Product Page | [kishansavaliya.com/magento-2-theme-customizer.html](https://kishansavaliya.com/magento-2-theme-customizer.html) |
| Email | kishansavaliyakb@gmail.com |
| Website | [kishansavaliya.com](https://kishansavaliya.com) |
| WhatsApp | +91 84012 70422 |
| GitHub Issues | [github.com/mage2sk/module-theme-customizer/issues](https://github.com/mage2sk/module-theme-customizer/issues) |
| Upwork (Top Rated Plus) | [Hire Kishan Savaliya](https://www.upwork.com/freelancers/~016dd1767321100e21) |
| Upwork Agency | [Panth Infotech](https://www.upwork.com/agencies/1881421506131960778/) |

Response time: 1-2 business days.

### Need Custom Magento Development?

Looking for **custom Magento module development**, **Hyva theme work**, **store migrations**, or **performance tuning**? Get a free quote in 24 hours:

<p align="center">
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/%F0%9F%92%AC%20Get%20a%20Free%20Quote-kishansavaliya.com%2Fget--quote-DC2626?style=for-the-badge" alt="Get a Free Quote" />
  </a>
</p>

<p align="center">
  <a href="https://www.upwork.com/freelancers/~016dd1767321100e21">
    <img src="https://img.shields.io/badge/Hire%20Kishan-Top%20Rated%20Plus-14a800?style=for-the-badge&logo=upwork&logoColor=white" alt="Hire on Upwork" />
  </a>
  &nbsp;&nbsp;
  <a href="https://www.upwork.com/agencies/1881421506131960778/">
    <img src="https://img.shields.io/badge/Visit-Panth%20Infotech%20Agency-14a800?style=for-the-badge&logo=upwork&logoColor=white" alt="Visit Agency" />
  </a>
  &nbsp;&nbsp;
  <a href="https://kishansavaliya.com/magento-2-theme-customizer.html">
    <img src="https://img.shields.io/badge/View%20Product%20Page-magento--2--theme--customizer-0D9488?style=for-the-badge" alt="View Product Page" />
  </a>
</p>

---

## About Panth Infotech

Built and maintained by **Kishan Savaliya** ([kishansavaliya.com](https://kishansavaliya.com)), a **Top Rated Plus** Magento developer on Upwork with 10+ years of eCommerce experience.

**Panth Infotech** is a Magento 2 development agency that builds high quality, security focused extensions and themes for both Hyva and Luma storefronts. The extension suite covers SEO, performance, checkout, product presentation, customer engagement, and store management, with each module built to MEQP standards and tested across Magento 2.4.4 to 2.4.8.

Browse the full extension catalog on our [Magento extensions page](https://kishansavaliya.com/magento-extensions.html) or on [Packagist](https://packagist.org/packages/mage2kishan/).

---

## Quick Links

| Resource | Link |
|---|---|
| **Product Page** | [magento-2-theme-customizer.html](https://kishansavaliya.com/magento-2-theme-customizer.html) |
| **Packagist** | [mage2kishan/module-theme-customizer](https://packagist.org/packages/mage2kishan/module-theme-customizer) |
| **GitHub** | [mage2sk/module-theme-customizer](https://github.com/mage2sk/module-theme-customizer) |
| **Website** | [kishansavaliya.com](https://kishansavaliya.com) |
| **Free Quote** | [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote) |
| **Upwork (Top Rated Plus)** | [Hire Kishan Savaliya](https://www.upwork.com/freelancers/~016dd1767321100e21) |
| **Upwork Agency** | [Panth Infotech](https://www.upwork.com/agencies/1881421506131960778/) |
| **Email** | kishansavaliyakb@gmail.com |
| **WhatsApp** | +91 84012 70422 |

---

<p align="center">
  <strong>Ready to configure your Hyva storefront from the admin panel?</strong><br/>
  <a href="https://kishansavaliya.com/magento-2-theme-customizer.html">
    <img src="https://img.shields.io/badge/%F0%9F%9A%80%20See%20Theme%20Customizer%20%E2%86%92-Product%20Page%20%26%20Details-DC2626?style=for-the-badge" alt="See Theme Customizer" />
  </a>
</p>

---

**SEO Keywords:** magento 2 theme customizer, hyva theme customizer, magento 2 google fonts, magento 2 custom css, hyva sticky header, free shipping progress bar magento, magento 2 header configuration, hyva theme builder, magento 2 admin theme editor, magento 2 topbar announcement, hyva tailwind css admin, magento 2 mini cart free shipping bar, hyva theme configuration, magento 2 custom css extension, magento 2 google fonts extension, hyva header icons, magento 2 sticky header, magento 2 free shipping bar, magento 2 header height, magento 2 hyva theme settings, panth theme customizer, mage2kishan theme customizer, magento 2.4.8 hyva, php 8.4 hyva, tailwind css magento 2, hyva 1.3 extension, magento 2 store view css, magento 2 per store font, custom css magento storefront, kishan savaliya magento, panth infotech, hire magento developer, top rated plus upwork, magento 2 hyva development, adobe commerce hyva extension
