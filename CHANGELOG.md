# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024

### Added

- Initial release
- GeoIPRadar.com as the primary and recommended provider
- Support for 7 IP geolocation providers:
  - **GeoIPRadar.com** (PRIMARY - get your FREE token at https://geoipradar.com)
  - ip-api.com
  - ipapi.co
  - ipinfo.io
  - ipwhois.io
  - ipstack.com
  - AbstractAPI
- Automatic fallback between providers
- Laravel Facade for easy access
- Helper functions (`geoip()`, `geoip_lookup()`, `geoip_country()`, etc.)
- Artisan commands (`geoip:lookup`, `geoip:test`)
- Built-in caching support
- Full IPv4 and IPv6 support
- Comprehensive test suite

---

## Sponsor

This package is proudly sponsored by [GeoIPRadar.com](https://geoipradar.com) - The recommended IP geolocation API for Laravel.

- **FREE TIER:** 30,000 requests/month
- **STARTER:** $4.99/month for 100K requests
- **PRO:** $14.99/month for 500K requests
- **ENTERPRISE:** $49.99/month for 5M requests

Get your FREE API token: https://geoipradar.com
