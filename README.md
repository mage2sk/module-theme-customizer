# Magento 2 Theme Customizer

Theme Customizer (`Panth_ThemeCustomizer`) adds admin settings for the storefront look of a Panth Hyva child theme: body and heading Google Fonts, a store-scoped custom CSS block, and a set of header options (top bar text, sticky header, header icons, mini cart free shipping progress, header width and height). It also runs the child theme's Tailwind CSS build from the command line or from the admin. On Luma-based themes it adds a top bar, SVG header icons and a LESS stylesheet that restyles the Luma header, breadcrumbs, mini cart and common page elements. It is aimed at merchants and developers running the Panth Hyva child theme (`Panth/Infotech`), with Luma support for mixed setups.

Product page: [Magento 2 Theme Customizer](https://kishansavaliya.com/magento-2-theme-customizer.html)

## Features

- Body Font and Heading Font selectors with 20 Google Fonts plus "System Fonts" and "System Default" options, and a Load Google Fonts switch (default No) that controls whether the selected Google fonts are requested from fonts.googleapis.com.
- Custom CSS textarea, output as an inline `<style>` tag in the head of every storefront page (after the theme stylesheet), configurable per website and store view.
- Server-side validation of the Custom CSS value on save (balanced braces and parentheses, no `@import`, `@charset` or `@namespace`, no script or style tags, `javascript:` URLs, iframes, `onerror`/`onclick`/`onload` handlers, `eval(` or `expression(`).
- Custom CSS editor toolbar in the admin with Beautify CSS, Download CSS and Import CSS buttons.
- Header Configuration section (`panth_header`) with General, Top Bar, Icons, Mini Cart / Free Shipping and Layout groups, read through `Panth\ThemeCustomizer\Helper\HeaderConfig`.
- Luma top bar with left and right text (basic HTML allowed) and a close button that hides the bar for 24 hours in the visitor's browser.
- Luma SVG header icons for search, account (with a sign-in/account dropdown) and cart (opens the Luma mini cart as a right-side drawer with an overlay and mirrors its item count). Below 1024px and on touch screens the icons, the top bar close button and the account menu links are 44px tall; on phones the top bar close button is hidden.
- Luma LESS overrides (`_module.less`, `_variables-override.less`, `_global-overrides.less`) for the header, breadcrumbs, mini cart drawer, typography, buttons, forms, messages, product listing and detail pages, cart, customer account, tables, layered navigation, modals, footer and more. On touch devices (640px and wider with `hover: none` or `pointer: coarse`) listing actions (Add to Cart, Quick View) are always shown under the price with 44px targets; mouse devices keep the Luma hover reveal.
- Console commands to check the Node.js build environment and run the Tailwind build.
- Implementation of `Panth\Core\Api\ThemeBuildExecutorInterface`, so the theme rebuild action in Panth_Core runs a real build when this module is enabled.

## Compatibility

| Component | Supported versions |
|---|---|
| Magento Open Source | 2.4.4 to 2.4.8 |
| Adobe Commerce | 2.4.4 to 2.4.8 |
| PHP | 8.1, 8.2, 8.3, 8.4 |
| Themes | Hyva (Panth `Panth/Infotech` child theme for the header settings and the Tailwind build), Luma-based themes (top bar, header icons, LESS styling) |

Composer constraints: `magento/framework` ^103.0, `magento/module-backend` ^102.0, `magento/module-config` ^101.2, `magento/module-customer` ^103.0, `magento/module-store` ^101.1, `magento/module-theme` ^101.1.

## Requirements

- Magento 2.4.4 to 2.4.8 and PHP 8.1 to 8.4.
- `mage2kishan/module-core` ^1.0 (`Panth_Core`), installed by Composer.
- For the Tailwind build only: Node.js and npm on the server, and a Hyva child theme with a `web/tailwind` directory containing a `package.json` with a `build` script. Google Fonts, Custom CSS and the header settings do not need Node.js.

## Installation

```bash
composer require mage2kishan/module-theme-customizer
bin/magento module:enable Panth_Core Panth_ThemeCustomizer
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
bin/magento setup:static-content:deploy -f
```

`setup:di:compile` is only needed in production mode. `setup:static-content:deploy -f` publishes the module's admin and Luma assets from `view/*/web`.

Check that the module is enabled:

```bash
bin/magento module:status Panth_ThemeCustomizer
```

## Configuration

The module adds two sections under the "Panth Extensions" tab, both protected by the `Panth_ThemeCustomizer::config` ACL resource. Every field below can be set at default, website and store view scope.

### Theme Customizer

Admin path: **Stores > Configuration > Panth Extensions > Theme Customizer**

The "Theme Configuration" group (default scope only) shows a read-only note. It explains that colours, typography sizes, spacing and sizing live in `app/design/frontend/Panth/Infotech/web/tailwind/theme-config.json`, and that after editing that file you run `npm run build` in the tailwind directory (it regenerates the theme variables first), then `bin/magento cache:flush`.

**Google Fonts** (`theme_customizer/typography/*`)

| Setting | Default | What it does |
|---|---|---|
| Load Google Fonts | No | Yes adds preconnect hints and a Google Fonts `<link>` (`display=swap`) to the page head for the selected Body Font and Heading Font when they are Google fonts. No loads nothing from Google. Path `theme_customizer/typography/load_google_fonts`. |
| Body Font | Inter (Modern, Clean) | Font for body text, paragraphs, buttons and inputs. Path `theme_customizer/typography/font_family_base`. The admin note asks you to set `typography.font-family-base` in theme-config.json to match. |
| Heading Font | -- System Default -- (empty) | Font for h1 to h6. Path `theme_customizer/typography/font_family_heading`. The admin note asks you to set `typography.font-family-heading` in theme-config.json to match. |

**Custom CSS** (`theme_customizer/custom_css/*`)

| Setting | Default | What it does |
|---|---|---|
| Custom CSS | (empty) | Standard CSS output inline on every storefront page. Path `theme_customizer/custom_css/custom_tailwind_css`. Validated on save; saving invalidates the layout, block_html and full_page caches. |

### Header Configuration

Admin path: **Stores > Configuration > Panth Extensions > Header Configuration**

**General** (`panth_header/general/*`)

| Setting | Default | What it does |
|---|---|---|
| Enable Custom Header | Yes | Use the Panth custom header instead of the default theme header. Path `panth_header/general/enabled`. |
| Enable Sticky Header | Yes | Header stays fixed at the top when scrolling. Path `panth_header/general/sticky_enabled`. |
| Show on Scroll Up | Yes | Hide the sticky header when scrolling down and show it when scrolling up. Shown only when Enable Sticky Header is Yes. Path `panth_header/general/show_on_scroll`. |

**Top Bar** (`panth_header/topbar/*`)

| Setting | Default | What it does |
|---|---|---|
| Enable Top Bar | Yes | Announcement bar above the header. Path `panth_header/topbar/enabled`. |
| Left Side Text | (empty) | Left text. Basic HTML is allowed (`a`, `b`, `br`, `em`, `i`, `small`, `span`, `strong` and `u`); other tags are escaped. Shown only when Enable Top Bar is Yes. Path `panth_header/topbar/left_text`. |
| Right Side Text | (empty) | Right text. Basic HTML is allowed (`a`, `b`, `br`, `em`, `i`, `small`, `span`, `strong` and `u`); other tags are escaped. Shown only when Enable Top Bar is Yes. Path `panth_header/topbar/right_text`. |

**Icons** (`panth_header/icons/*`)

| Setting | Default | What it does |
|---|---|---|
| Show Search Icon | Yes | Search icon visibility. Path `panth_header/icons/search_enabled`. |
| Show Account Icon | Yes | Account icon visibility. Path `panth_header/icons/account_enabled`. |
| Show Mini Cart Icon | Yes | Mini cart icon visibility. Path `panth_header/icons/minicart_enabled`. |
| Cart Counter Badge Style | Pill | Shape of the item count badge on the cart icon: Circle, Pill or Square. Path `panth_header/icons/counter_style`. |
| Icon Size (px) | 24 | Icon size, digits only. Path `panth_header/icons/icon_size`. |

**Mini Cart / Free Shipping** (`panth_header/minicart/*`)

| Setting | Default | What it does |
|---|---|---|
| Show Free Shipping Progress | Yes | Progress bar toward free shipping in the mini cart sidebar. Path `panth_header/minicart/free_shipping_enabled`. |
| Free Shipping Threshold | 99 | Minimum order amount for free shipping, a number of 0 or more (0 shows the success message for every cart). Shown only when Show Free Shipping Progress is Yes. Path `panth_header/minicart/free_shipping_threshold`. |
| Progress Message | Add {amount} more for free shipping! | `{amount}` is replaced by the remaining amount. Shown only when Show Free Shipping Progress is Yes. Path `panth_header/minicart/free_shipping_message`. |
| Success Message | You've qualified for free shipping! | Shown when the threshold is reached. Shown only when Show Free Shipping Progress is Yes. Path `panth_header/minicart/free_shipping_success_message`. |
| Show Continue Shopping Button | Yes | Continue shopping button in the mini cart. Path `panth_header/minicart/show_continue_shopping`. |
| Show Cart Subtotal | Yes | Cart subtotal in the mini cart. Path `panth_header/minicart/show_subtotal`. |

**Layout** (`panth_header/layout/*`)

| Setting | Default | What it does |
|---|---|---|
| Container Width | Container (Centered, Max Width) | Width of the header content area: Container (Centered, Max Width), Container Fluid (Full Width with Padding) or Full Width (Edge to Edge). Path `panth_header/layout/container_width`. |
| Header Height (px) | 80 | Header height, digits only. Path `panth_header/layout/height`. |

The admin menu also gets a "Theme Customizer" entry (with "Configuration" and "Header Configuration" links to the two sections) inside the Panth_Core menu group.

## Usage

**Google Fonts.** `Panth\ThemeCustomizer\Block\GoogleFonts` (template `google-fonts.phtml`) is added to the `head.additional` block on every storefront page. When Load Google Fonts is Yes, it reads the Body Font and Heading Font settings and, for each value whose first family is one of the 20 listed Google fonts, outputs `<link rel="preconnect">` tags for fonts.googleapis.com and fonts.gstatic.com plus one Google Fonts stylesheet `<link>` with weights 300 to 800 and `display=swap`. "System Fonts", "System Default" and any other value load nothing. When Load Google Fonts is No (the default) nothing is output, so no visitor data is sent to Google by this module. It only loads the font files; the `font-family` used by the Hyva theme comes from theme-config.json and the Tailwind build.

**Font values.** Font values are stored with single quotes, matching the dropdown options (for example `'Inter', sans-serif`). Values saved with double quotes (for example `"Inter", sans-serif`) are converted to single quotes when read, when the admin form is shown and when saved, so saving the section without changes keeps the selected fonts. The data patch `Setup\Patch\Data\NormalizeFontFamilyQuotes` converts existing double-quoted rows once during `setup:upgrade`; rows that already use single quotes are not touched.

**Custom CSS.** `Panth\ThemeCustomizer\Block\CustomCss` (template `custom-css.phtml`) is added to `after.body.start` and prints the saved CSS inside a `<style>` tag when the value is not empty. Any `<` character in the value is printed as the CSS escape `\3c ` so the value cannot close the `<style>` tag. No rebuild is needed; the save invalidates the layout, block_html and full_page caches, which you then refresh.

**Header settings on Hyva.** On Hyva, this module stores the `panth_header` values and exposes them through `Panth\ThemeCustomizer\Helper\HeaderConfig` (store scope). The Hyva header, cart drawer and free shipping progress markup are templates in the child theme, not in this module; the settings take effect where those templates read the helper. Colours for the header, top bar, icons, counter badge and breadcrumbs are not admin settings; they come from theme-config.json and need the Tailwind build. The module also ships `etc/theme-config.json` listing the header and breadcrumb keys with sample values; no code in the module reads that file.

**Luma.** `view/frontend/layout/default.xml` adds the top bar (`luma/topbar.phtml`) to `header.panel` and the header icons (`luma/header-icons.phtml`, view model `Panth\ThemeCustomizer\ViewModel\HeaderIcons`) to `header-wrapper`. `default_hyva.xml` removes both blocks on Hyva. The Luma top bar uses Enable Top Bar, Left Side Text and Right Side Text. The Luma header icons template shows the search icon (and its search bar), the account icon and the cart icon only when Show Search Icon, Show Account Icon and Show Mini Cart Icon are Yes. The Luma styles in `view/frontend/web/css/source/` are compiled by Magento's LESS pipeline during static content deployment; a few header colours use CSS variables such as `--header-topbar-bg` with hard-coded fallbacks.

**Luma design tokens.** `Panth\ThemeCustomizer\Block\LumaTokens` (template `luma-tokens.phtml`) is added to `head.additional` after the Google Fonts block and before the Custom CSS block. It prints a `<link>` to `view/frontend/web/css/luma-tokens.css` only when the active theme code is exactly `Magento/luma`; for every other theme it prints nothing, and `default_hyva.xml` also removes the block. The file is plain CSS with custom properties (`--pt-brand`, `--pt-h1` and so on), so it needs no LESS compile and works with the core Luma theme, which cannot be edited. It applies the same design system as the Panth/Infotech Hyva theme: DM Sans, H1 36px (28px below 768px), H2 28px (24px), H3 22px, H4 18px, body 16px with line height 1.5, brand teal #0F766E links and primary buttons (44px, 48px on phones, 8px radius), white secondary buttons with a 1px #D4D4D4 border, 46px white inputs with a 2px teal focus ring, status colours for messages, a 24px product price, white product cards with a 1px border and 12px radius, and matching layered navigation, minicart, footer, breadcrumbs and product tabs. It also sets the module colour variables printed by Panth_Core (for example `--color-primary` and `--contact-input-bg`) to the same palette. It keeps the page stable while Luma scripts load: the top menu looks the same before and after the menu script runs (chevrons drawn by CSS from 768px), the empty swatch area and the product breadcrumbs reserve their height, product tabs wrap from 768px, and the store switcher strip is a neutral 32px bar with 12px text. To change a value, override the `--pt-*` variables in Custom CSS, which is printed after this file.

**Tailwind build.** `Panth\ThemeCustomizer\Model\BuildExecutor` runs `npm run build` in `app/design/frontend/<current theme path>/web/tailwind` if that directory exists, otherwise in `app/design/frontend/Panth/Infotech/web/tailwind`. It looks for npm in nvm directories, `which npm`, common system paths and `PATH`. When the build is triggered from the admin (the Panth_Core rebuild action, which calls this class through `Panth\Core\Api\ThemeBuildExecutorInterface`, or a POST to `themecustomizer/build/ajax`) it then flushes the config, layout, block_html and full_page caches. After a build from the command line, flush the cache yourself.

```bash
bin/magento theme:customizer:check
bin/magento theme:customizer:build
bin/magento cache:flush
```

**Overridable templates.** Copy any of these into your theme under `Panth_ThemeCustomizer/templates/`:

- `google-fonts.phtml`
- `custom-css.phtml`
- `luma/topbar.phtml`
- `luma/header-icons.phtml`

## Developer Notes

- Module name: `Panth_ThemeCustomizer` (sequence after `Panth_Core`, `Magento_Theme`, `Magento_Backend`, `Magento_Config`, `Magento_Store`).
- Package: `mage2kishan/module-theme-customizer`, version 1.1.0.
- Namespace: `Panth\ThemeCustomizer`.
- Key classes: `Helper\Data` (theme_customizer values), `Helper\HeaderConfig` (panth_header values), `Block\GoogleFonts`, `Block\CustomCss`, `ViewModel\HeaderIcons`, `Model\BuildExecutor`, `Model\Config\Backend\TailwindCss` (Custom CSS validation), `Model\Config\Backend\FontFamily` and `Model\Config\FontFamilyNormalizer` (font value quoting), `Block\Adminhtml\System\Config\TailwindCss` (Custom CSS editor toolbar), source models `GoogleFonts`, `CounterStyle`, `ContainerWidth`.
- DI: `etc/di.xml` registers the console commands and sets `Model\BuildExecutor` as the preference for `Panth\Core\Api\ThemeBuildExecutorInterface`. `etc/frontend/di.xml` registers the module with `Panth\Core\ViewModel\ThemeConfig`.
- Console commands: `theme:customizer:check` (checks node, npm, tailwind directory, package.json and write access), `theme:customizer:build` (runs the Tailwind build), `panth:theme:export-css` (deprecated; prints a notice only).
- Admin routes (front name `themecustomizer`): `build/ajax` (POST only; runs the build and returns JSON), `build/exportcss` (POST only; deprecated, returns a notice), `documentation/index` (renders this README.md in the admin).
- ACL: `Panth_ThemeCustomizer::customizer` > `Panth_ThemeCustomizer::config`, under Stores > Settings > Configuration.
- Database: no tables. Settings are stored in `core_config_data`. Data patch `Setup\Patch\Data\NormalizeFontFamilyQuotes` converts double-quoted font values to single quotes.
- No frontend observers, plugins or cron jobs are registered.

## Uninstallation

```bash
bin/magento module:disable Panth_ThemeCustomizer
composer remove mage2kishan/module-theme-customizer
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

Saved settings stay in `core_config_data` under the paths `theme_customizer/%` and `panth_header/%`; delete those rows if you no longer need them. Leave `Panth_Core` installed if other Panth modules use it.

## Support

- Product page: [Magento 2 Theme Customizer](https://kishansavaliya.com/magento-2-theme-customizer.html)
- Contact: [kishansavaliya.com/contact](https://kishansavaliya.com/contact)
- Email: kishansavaliyakb@gmail.com
- GitHub issues: [mage2sk/module-theme-customizer/issues](https://github.com/mage2sk/module-theme-customizer/issues)

## Documentation

See [USER_GUIDE.md](USER_GUIDE.md).

## License

Commercial software license. See [LICENSE.txt](LICENSE.txt) in this repository.

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Links

- Website: [kishansavaliya.com](https://kishansavaliya.com)
- All extensions catalogue: [kishansavaliya.com/magento-extensions.html](https://kishansavaliya.com/magento-extensions.html)
- GitHub: [mage2sk/module-theme-customizer](https://github.com/mage2sk/module-theme-customizer)
- Packagist: [mage2kishan/module-theme-customizer](https://packagist.org/packages/mage2kishan/module-theme-customizer)
