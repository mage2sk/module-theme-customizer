# Changelog

All notable changes to this extension are documented here. The format
is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.0] -- Initial release

### Added
- **Google Fonts loader** -- admin-selectable body and heading fonts
  with automatic `<link>` tag injection via Google Fonts CDN.
- **Custom CSS editor** -- ACE-powered admin editor with beautify,
  validate, download, and import functionality. Backend validation
  blocks script tags, JavaScript URLs, event handlers, and other
  dangerous patterns.
- **Header configuration** -- sticky header, scroll-up reveal, top bar
  announcement text with dismiss, container width, header height.
- **Icon management** -- toggle search, account, and mini-cart icons;
  choose counter badge style (circle / pill / square); configurable
  icon size.
- **Mini-cart free-shipping progress** -- configurable threshold,
  progress message with `{amount}` placeholder, success message,
  continue shopping button toggle.
- **Tailwind CSS build integration** -- `theme:customizer:build` and
  `theme:customizer:check` CLI commands, plus an admin AJAX build
  button.
- **Luma header compatibility** -- SVG header icons, account dropdown,
  and top bar matching Hyva's Lucide icon style on Luma storefronts.
  Automatically removed on Hyva via `default_hyva.xml`.
- **theme-config.json architecture** -- visual tokens (colours, spacing,
  radii, shadows) defined in JSON and consumed by the Node.js build
  script.
- **`ThemeBuildExecutorInterface` override** -- provides the real build
  executor for Panth_Core's admin "Rebuild Child Theme" button.

### Compatibility
- Magento Open Source / Commerce / Cloud 2.4.4 -- 2.4.8
- PHP 8.1, 8.2, 8.3, 8.4

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422
