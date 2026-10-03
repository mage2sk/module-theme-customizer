# Changelog

All notable changes to this extension are documented here. The format
is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.1.10] - 2026-10-03

### Fixed
- A free shipping threshold of 0 is now respected (every cart qualifies) instead of falling back to 99. An empty or non-numeric value still uses 99, and negative values count as 0.
- Custom Tailwind CSS validation now checks the whole value. Before, it stopped at the first valid declaration, so a later declaration without a semicolon or with doubled semicolons was saved without an error.
