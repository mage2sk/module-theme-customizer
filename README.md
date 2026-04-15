<!-- SEO Meta -->
<!--
  Title: Panth Theme Customizer - Hyva Theme Builder for Magento 2 | Panth Infotech
  Description: Panth Theme Customizer is a backend-driven Hyva theme configuration module for Magento 2. Manage colors, Google Fonts typography, Tailwind CSS tokens, custom CSS injection, header layouts, sticky header, free shipping progress bar, and icon controls - all without touching code. Compatible with Magento 2.4.4 - 2.4.8 and PHP 8.1 - 8.4. Built by Top Rated Plus Magento developer Kishan Savaliya.
  Keywords: magento 2 theme customizer, hyva theme builder, css custom properties, google fonts magento, tailwind magento, theme tokens, hyva theme configuration, magento 2 color palette, magento 2 typography, hyva sticky header, free shipping progress bar magento
  Author: Kishan Savaliya (Panth Infotech)
  Canonical: https://github.com/mage2sk/module-theme-customizer
-->

# Hyva Theme Customizer — Backend-Driven Theme Builder for Magento 2 | Panth Infotech

[![Magento 2.4.4 - 2.4.8](https://img.shields.io/badge/Magento-2.4.4%20--%202.4.8-orange?logo=magento&logoColor=white)](https://magento.com)
[![PHP 8.1 - 8.4](https://img.shields.io/badge/PHP-8.1%20--%208.4-blue?logo=php&logoColor=white)](https://php.net)
[![Hyva Theme](https://img.shields.io/badge/Hyva-Theme%20Ready-7C3AED?logo=tailwindcss&logoColor=white)](https://hyva.io)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-06B6D4?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Packagist](https://img.shields.io/badge/Packagist-mage2kishan%2Fmodule--theme--customizer-orange?logo=packagist&logoColor=white)](https://packagist.org/packages/mage2kishan/module-theme-customizer)
[![Upwork Top Rated Plus](https://img.shields.io/badge/Upwork-Top%20Rated%20Plus-14a800?logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)
[![Panth Infotech Agency](https://img.shields.io/badge/Agency-Panth%20Infotech-14a800?logo=upwork&logoColor=white)](https://www.upwork.com/agencies/1881421506131960778/)
[![Website](https://img.shields.io/badge/Website-kishansavaliya.com-0D9488)](https://kishansavaliya.com)

> **Backend-driven theme configuration** for Hyva-powered Magento 2 storefronts. Manage your entire design system — colors, typography, Google Fonts, Tailwind CSS tokens, custom CSS, header layout, sticky header behavior, free shipping progress bar, and icon controls — directly from the Magento admin panel without touching a single line of code or running a Tailwind rebuild manually.

**Panth Theme Customizer** turns your Hyva theme into a fully configurable design system. It exposes every brand-critical token — primary color, secondary color, accent color, body font, heading font, border radius, spacing, header height, icon set — as admin settings, then compiles them into CSS custom properties and Tailwind config that your frontend consumes live. Change a color in admin, click save, and watch your storefront update instantly.

Under the hood, the module leverages CSS custom properties (CSS variables) so the entire color palette and typography system can be swapped without rebuilding your theme for most changes. For Tailwind-driven updates, a one-click "Rebuild Theme" button triggers the Hyva build pipeline from admin. Typography integrates directly with Google Fonts — pick any font from the Google Fonts library, and the module handles preloading, font-display optimization, and layered fallbacks automatically.

Beyond tokens, Theme Customizer ships ready-to-use storefront components: a configurable sticky header with scroll thresholds, a free shipping progress bar that shows shoppers how close they are to free delivery, header layout variants (centered logo, split navigation, minimal), and an icon library switcher (Heroicons outline/solid, Lucide, custom SVG). Everything is multi-store aware, so each store view can have its own brand identity.

---

## 🚀 Need Custom Hyva Theme Development?

> **Get a free quote for your project in 24 hours** — custom Hyva themes, theme customizer extensions, Tailwind design systems, performance optimization, and Adobe Commerce Cloud deployments.

<p align="center">
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/Get%20a%20Free%20Quote%20%E2%86%92-Reply%20within%2024%20hours-DC2626?style=for-the-badge" alt="Get a Free Quote" />
  </a>
</p>

<table>
<tr>
<td width="50%" align="center">

### 🏆 Kishan Savaliya
**Top Rated Plus on Upwork**

[![Hire on Upwork](https://img.shields.io/badge/Hire%20on%20Upwork-Top%20Rated%20Plus-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)

100% Job Success • 10+ Years Magento Experience
Adobe Certified • Hyva Specialist

</td>
<td width="50%" align="center">

### 🏢 Panth Infotech Agency
**Magento Development Team**

[![Visit Agency](https://img.shields.io/badge/Visit%20Agency-Panth%20Infotech-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/agencies/1881421506131960778/)

Hyva Themes • Theme Customization • Tailwind Systems
Performance • SEO • Adobe Commerce Cloud

</td>
</tr>
</table>

**Visit our website:** [kishansavaliya.com](https://kishansavaliya.com) &nbsp;|&nbsp; **Get a quote:** [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote)

---

## Table of Contents

- [Why Theme Customizer](#why-theme-customizer)
- [Key Features](#key-features)
- [Compatibility](#compatibility)
- [Installation](#installation)
- [Configuration](#configuration)
- [Color Management](#color-management)
- [Typography & Google Fonts](#typography--google-fonts)
- [Tailwind CSS Integration](#tailwind-css-integration)
- [Custom CSS Injection](#custom-css-injection)
- [Header Configuration](#header-configuration)
- [Sticky Header](#sticky-header)
- [Free Shipping Progress Bar](#free-shipping-progress-bar)
- [Icon Controls](#icon-controls)
- [FAQ](#faq)
- [Support](#support)
- [About Panth Infotech](#about-panth-infotech)
- [Quick Links](#quick-links)

---

## Why Theme Customizer

Hyva is the fastest Magento 2 storefront frontend in existence — but customizing it traditionally requires editing `tailwind.config.js`, running `npm run build-prod`, and deploying static content every time you want to change a color or swap a font. That workflow is fine for developers, but it's a blocker for merchants, designers, and marketing teams.

**Theme Customizer bridges that gap.** Every design decision that used to require a developer now lives in `Stores → Configuration → Panth Extensions → Theme Customizer`:

- Want to change the primary brand color for Black Friday? Open admin, pick a color, save.
- Want to try a new Google Font for headings? Type the font name, save, done.
- Want to toggle the sticky header on mobile only? Check a box.
- Want to inject custom CSS for a one-off campaign? Paste it into the admin textarea.

All changes are **multi-store scoped** — a different color palette per brand, per store view, per website.

---

## Key Features

### Design Token Management

- **Color palette** — primary, secondary, accent, success, warning, error, info, neutral grays (11-step scale) — all editable via color picker
- **CSS custom properties** — every token is emitted as a CSS variable (`--color-primary`, `--font-body`, `--radius-default`) for instant theming without rebuilds
- **Border radius tokens** — small, default, large, full — applied across buttons, cards, inputs, modals
- **Spacing tokens** — consistent padding and margin scales across the storefront

### Typography

- **Google Fonts integration** — pick any Google Font for body text, headings, and UI elements
- **Font preloading** — automatic `<link rel="preload">` for critical fonts to eliminate FOUT
- **Font-display optimization** — `font-display: swap` applied by default for performance
- **Fallback stacks** — graceful fallbacks to system fonts if Google Fonts fail to load
- **Font weight controls** — select which weights to load per font (400, 500, 600, 700, 800)

### Tailwind CSS Integration

- **Theme config JSON** — `etc/theme-config.json` exposes all tokens to the Hyva Tailwind build
- **One-click rebuild** — `Rebuild Theme` button in admin triggers the Tailwind build from the UI
- **`ThemeBuildExecutorInterface`** — implements the Panth Core contract so other modules can trigger builds too
- **Automatic static content deploy** — after build, static assets are flushed so changes go live immediately

### Custom CSS Injection

- **Per-store CSS textarea** — inject custom CSS that loads after the main stylesheet
- **Critical CSS option** — mark CSS as critical to inline in `<head>` for above-the-fold styling
- **Store-view scoped** — each store view can have its own custom CSS

### Header & Layout

- **Header layout variants** — centered logo, split navigation, minimal, classic left-logo
- **Header height control** — set desktop and mobile header heights independently
- **Sticky header** — enable sticky behavior with configurable scroll threshold
- **Shrink-on-scroll** — optional header shrink animation when user scrolls
- **Transparent header on homepage** — optional transparent overlay for hero sections

### Free Shipping Progress Bar

- **Dynamic progress bar** — displayed in cart and minicart showing progress toward free shipping threshold
- **Configurable threshold** — set the free shipping minimum amount per store
- **Custom messaging** — templated messages like "Add $25 more for free shipping!" and "🎉 You qualify for free shipping!"
- **Color-aware** — uses your theme's primary and success colors automatically

### Icon Controls

- **Icon library switcher** — choose between Heroicons (outline/solid), Lucide, or upload custom SVG sprite
- **Icon size tokens** — small (16px), medium (20px), large (24px), xl (32px)
- **Icon color inheritance** — icons inherit text color by default, with override per instance

### Security & Performance

- **MEQP compliant** — passes Adobe's Magento Extension Quality Program
- **Cached output** — compiled CSS tokens are cached and invalidated only on config change
- **Zero runtime overhead** — CSS variables are emitted once per page, no JS required for theming
- **CSP-friendly** — custom CSS is nonce-tagged if Content Security Policy is active

---

## Compatibility

| Requirement | Versions Supported |
|---|---|
| Magento Open Source | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce Cloud | 2.4.4 — 2.4.8 |
| PHP | 8.1.x, 8.2.x, 8.3.x, 8.4.x |
| Hyva Theme | 1.3.0+ (required) |
| Tailwind CSS | 3.x (bundled with Hyva) |
| Node.js | 18.x, 20.x (for Tailwind build) |
| Dependency | `mage2kishan/module-core` ^1.0 |

Tested on:
- Magento 2.4.8-p4 with Hyva 1.3.5 and PHP 8.4
- Magento 2.4.7 with Hyva 1.3.0 and PHP 8.3
- Magento 2.4.6 with Hyva 1.2.8 and PHP 8.2

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

### Rebuild Hyva Theme

After installation, rebuild your Hyva theme to pick up the new Tailwind config:

```bash
cd app/design/frontend/<Vendor>/<theme>/web/tailwind
npm install
npm run build-prod
```

Or click the **Rebuild Theme** button in:
```
Admin → Stores → Configuration → Panth Extensions → Theme Customizer → Advanced
```

### Manual Installation via ZIP

1. Download the latest release ZIP from [Packagist](https://packagist.org/packages/mage2kishan/module-theme-customizer)
2. Extract the contents to `app/code/Panth/ThemeCustomizer/`
3. Run the commands above starting from `bin/magento module:enable`

### Verify Installation

```bash
bin/magento module:status Panth_ThemeCustomizer
# Expected output: Module is enabled
```

---

## Configuration

All settings live at `Stores → Configuration → Panth Extensions → Theme Customizer`. The configuration is split into logical groups:

| Section | What It Controls |
|---|---|
| General | Master enable toggle, store scope, cache behavior |
| Colors | Full color palette (primary, secondary, accent, semantic colors, neutrals) |
| Typography | Body font, heading font, font weights, Google Fonts loader |
| Layout | Container widths, spacing scale, border radius tokens |
| Header | Layout variant, height, sticky behavior, transparent overlay |
| Free Shipping Bar | Enable, threshold amount, messages |
| Icons | Icon library, sizes, custom SVG sprite |
| Custom CSS | Per-store CSS injection, critical CSS toggle |
| Advanced | Rebuild theme button, clear cache, debug mode |

---

## Color Management

The module defines a complete semantic color system following Tailwind conventions:

| Token | Purpose | Default |
|---|---|---|
| `primary` | Main brand color (buttons, links, focus rings) | `#0D9488` |
| `secondary` | Secondary brand color | `#1E40AF` |
| `accent` | Accent for callouts, badges | `#F59E0B` |
| `success` | Success states, checkmarks, free shipping earned | `#16A34A` |
| `warning` | Warnings, low stock | `#F59E0B` |
| `error` | Errors, validation, out of stock | `#DC2626` |
| `info` | Info banners | `#2563EB` |
| `neutral-50` → `neutral-900` | 11-step gray scale | Tailwind defaults |

Each color is editable via a native HTML5 color picker in admin and emitted as a CSS custom property:

```css
:root {
  --color-primary: 13 148 136;
  --color-secondary: 30 64 175;
  --color-accent: 245 158 11;
  /* ...etc */
}
```

---

## Typography & Google Fonts

Typography is configured in the **Typography** section. You can choose any font from the Google Fonts library for:

- **Body font** — applied to `<body>` and inherited by default
- **Heading font** — applied to `h1`–`h6`
- **UI font** — optional separate font for buttons, navigation, form inputs

For each font, you select which weights to load. The module generates the optimized Google Fonts stylesheet URL with:

- `display=swap` for FOUT prevention
- Multiple families in a single request
- Preconnect hints to `fonts.gstatic.com`
- Preload for critical weights

Example output in `<head>`:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap">
```

---

## Tailwind CSS Integration

Theme Customizer writes its tokens to `etc/theme-config.json`, which is read by the Hyva theme's `tailwind.config.js` via the `ThemeConfig` view model. This means:

1. **CSS variable changes** (colors, fonts, radius) take effect instantly with no rebuild
2. **Tailwind-class-driven changes** (spacing scale, breakpoints, max-widths) require a Tailwind rebuild

The **Rebuild Theme** button in admin calls `Panth\ThemeCustomizer\Model\BuildExecutor`, which runs `npm run build-prod` in the active Hyva theme's `web/tailwind` directory and flushes static content. Progress and errors are streamed back to the admin UI.

---

## Custom CSS Injection

The **Custom CSS** textarea lets you inject arbitrary CSS per store view. Common use cases:

- Brand-specific overrides that don't warrant a full theme
- Short-lived campaign styling (Black Friday banner, holiday promo)
- Fixing a single selector without a theme deploy
- A/B testing CSS changes

Injected CSS loads after the main stylesheet, so your rules take precedence. If Content Security Policy is active, the module automatically nonces the `<style>` tag.

For critical above-the-fold CSS, enable the **Inline Critical** toggle to emit the CSS directly in `<head>` instead of a linked stylesheet.

---

## Header Configuration

Pre-built header layouts are selectable from a dropdown:

| Layout | Description |
|---|---|
| Classic | Logo left, search center, mini-cart right (Hyva default) |
| Centered | Logo centered, nav below |
| Split Nav | Nav split around centered logo |
| Minimal | Just logo and mini-cart, no search bar visible |
| Transparent Overlay | Floating over hero image on homepage only |

Each layout supports independent **desktop height** and **mobile height** settings in pixels.

---

## Sticky Header

The sticky header is fully configurable:

| Setting | Default | Description |
|---|---|---|
| Enable Sticky | Yes | Master toggle |
| Scroll Threshold | 100px | Pixels scrolled before sticky activates |
| Enable on Mobile | Yes | Toggle for small screens |
| Shrink on Scroll | No | Animate header to smaller height when sticky |
| Hide on Scroll Down | No | Hide sticky header when scrolling down, show on scroll up |

Implementation uses Alpine.js with `IntersectionObserver` — no jQuery, no scroll listeners — for buttery-smooth performance.

---

## Free Shipping Progress Bar

The Free Shipping Progress Bar appears in the cart drawer and full cart page. It calculates the difference between the current subtotal and the configured free shipping threshold, then renders a visual progress bar with a live message.

**Example messages:**

- `Add $25.00 more for free shipping!` (in progress)
- `🎉 You qualify for free shipping!` (earned)
- `You saved $0 on shipping!` (below threshold with inline savings highlight)

Colors automatically match your theme palette (primary for fill, success for earned state), so the progress bar looks native to your brand with zero styling effort.

---

## Icon Controls

Select your icon library from a dropdown:

- **Heroicons Outline** — clean line icons (default)
- **Heroicons Solid** — filled icons for emphasis
- **Lucide** — modern open-source icon set
- **Custom SVG Sprite** — upload your own SVG sprite and map icon names

Icon sizes are standardized as Tailwind utilities:
- `icon-sm` (16×16)
- `icon-md` (20×20)
- `icon-lg` (24×24)
- `icon-xl` (32×32)

Colors inherit from `currentColor` by default, meaning icons match the surrounding text color automatically.

---

## FAQ

### Do changes require a Tailwind rebuild every time?

No. Color, font, and radius changes use CSS custom properties and take effect instantly. Only changes to Tailwind-class-driven tokens (custom spacing scales, new breakpoints) require a rebuild via the **Rebuild Theme** button.

### Does this work with Luma?

No. Theme Customizer is built specifically for Hyva's Tailwind-based architecture. For Luma stores, use Magento's built-in Design Configuration.

### Can I have different colors per store view?

Yes. All Theme Customizer settings respect Magento's scope hierarchy — default, website, and store view — so each brand can have its own palette.

### Will my custom Hyva child theme break?

No. Theme Customizer reads your active Hyva child theme and writes tokens into its Tailwind config. If your child theme already overrides tokens, you can disable Theme Customizer for those specific tokens.

### Does the free shipping bar work with multi-currency?

Yes. The threshold is stored in base currency and converted per store view automatically using Magento's standard currency conversion.

### Can I export and import my theme settings?

Yes. Use Magento's standard config export (`bin/magento config:show` / `config:set`) to move settings between environments. All Theme Customizer paths start with `panth_theme_customizer/*`.

### Is the source code available?

Yes. The full source is on GitHub at [github.com/mage2sk/module-theme-customizer](https://github.com/mage2sk/module-theme-customizer).

### Does the module slow down the frontend?

No. All CSS variables are emitted once in `<head>`, Google Fonts are preloaded, and the progress bar uses Alpine.js (already loaded by Hyva). Zero additional JS libraries are added to your storefront.

---

## Support

| Channel | Contact |
|---|---|
| Email | kishansavaliyakb@gmail.com |
| Website | [kishansavaliya.com](https://kishansavaliya.com) |
| WhatsApp | +91 84012 70422 |
| GitHub Issues | [github.com/mage2sk/module-theme-customizer/issues](https://github.com/mage2sk/module-theme-customizer/issues) |
| Upwork (Top Rated Plus) | [Hire Kishan Savaliya](https://www.upwork.com/freelancers/~016dd1767321100e21) |
| Upwork Agency | [Panth Infotech](https://www.upwork.com/agencies/1881421506131960778/) |

Response time: 1-2 business days.

### 💼 Need Custom Hyva Development?

Looking for **custom Hyva theme development**, **Tailwind design systems**, **theme customizer extensions**, or **storefront performance optimization**? Get a free quote in 24 hours:

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
  <a href="https://kishansavaliya.com">
    <img src="https://img.shields.io/badge/Visit%20Website-kishansavaliya.com-0D9488?style=for-the-badge" alt="Visit Website" />
  </a>
</p>

---

## License

Panth Theme Customizer is distributed under a proprietary license — see `LICENSE.txt`. A single license permits installation on one Magento production domain and its associated staging/development environments.

---

## About Panth Infotech

Built and maintained by **Kishan Savaliya** — [kishansavaliya.com](https://kishansavaliya.com) — a **Top Rated Plus** Magento developer on Upwork with 10+ years of eCommerce experience and a deep specialization in Hyva themes and Tailwind design systems.

**Panth Infotech** is a Magento 2 development agency focused on high-performance Hyva storefronts, custom module development, and merchant-empowering admin tools. Our extension suite spans SEO, performance, checkout, product presentation, customer engagement, and store management — over 34 modules built to MEQP standards and tested across Magento 2.4.4 to 2.4.8.

Browse the full extension catalog on the [Adobe Commerce Marketplace](https://commercemarketplace.adobe.com) or [Packagist](https://packagist.org/packages/mage2kishan/).

### Quick Links

- 🌐 **Website:** [kishansavaliya.com](https://kishansavaliya.com)
- 💬 **Get a Quote:** [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote)
- 👨‍💻 **Upwork Profile (Top Rated Plus):** [upwork.com/freelancers/~016dd1767321100e21](https://www.upwork.com/freelancers/~016dd1767321100e21)
- 🏢 **Upwork Agency:** [upwork.com/agencies/1881421506131960778](https://www.upwork.com/agencies/1881421506131960778/)
- 📦 **Packagist:** [packagist.org/packages/mage2kishan/module-theme-customizer](https://packagist.org/packages/mage2kishan/module-theme-customizer)
- 🐙 **GitHub:** [github.com/mage2sk/module-theme-customizer](https://github.com/mage2sk/module-theme-customizer)
- 🛒 **Adobe Marketplace:** [commercemarketplace.adobe.com](https://commercemarketplace.adobe.com)
- 📧 **Email:** kishansavaliyakb@gmail.com
- 📱 **WhatsApp:** +91 84012 70422

---

<p align="center">
  <strong>Ready to transform your Hyva storefront design workflow?</strong><br/>
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/%F0%9F%9A%80%20Get%20Started%20%E2%86%92-Free%20Quote%20in%2024h-DC2626?style=for-the-badge" alt="Get Started" />
  </a>
</p>

---

**SEO Keywords:** magento 2 theme customizer, hyva theme builder, css custom properties, google fonts magento, tailwind magento, theme tokens, hyva theme configuration, magento 2 color palette, magento 2 typography, hyva sticky header, free shipping progress bar magento, magento 2 custom css, hyva tailwind config, magento 2 header layouts, hyva icon library, heroicons magento, lucide icons magento, magento 2 design system, hyva child theme customization, tailwind design tokens, magento 2 admin theme editor, backend driven theme, hyva theme customization, magento 2 brand manager, multi-store theme, store view color palette, panth theme customizer, panth infotech, hire magento developer upwork, top rated plus magento freelancer, kishan savaliya magento, hyva performance optimization, magento 2.4.8 hyva, php 8.4 hyva, mage2kishan, mage2sk, magento marketplace developer, custom hyva development india, magento 2 hyva development, magento 2 performance optimization, adobe commerce cloud expert, tailwind css magento
