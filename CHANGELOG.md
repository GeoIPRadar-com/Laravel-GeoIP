# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2024

### Changed

- Renamed package from `laravel-geoip` to `laravel-ip` to avoid trademark issues
- Updated all class names from `GeoIP` to `IP` (e.g., `GeoIPManager` -> `IPManager`)
- Updated facade from `GeoIP` to `IP`
- Updated helper functions from `geoip()` to `ip()`, `geoip_lookup()` to `ip_lookup()`, etc.
- Updated artisan commands from `geoip:lookup` to `ip:lookup`, `geoip:test` to `ip:test`
- Updated config file from `geoip.php` to `ip.php`
- Updated environment variable prefixes from `GEOIP_` to `IP_`

### Migration Guide

If upgrading from v1.0.0:

1. Update your code to use the new facade name:
   ```php
   // Before
   use GeoIPRadar\LaravelGeoIP\Facades\GeoIP;
   GeoIP::lookup('8.8.8.8');

   // After
   use GeoIPRadar\LaravelIP\Facades\IP;
   IP::lookup('8.8.8.8');
   ```

2. Update helper function calls:
   ```php
   // Before
   geoip('8.8.8.8');
   geoip_country('8.8.8.8');

   // After
   ip('8.8.8.8');
   ip_country('8.8.8.8');
   ```

3. Update artisan commands:
   ```bash
   # Before
   php artisan geoip:lookup 8.8.8.8

   # After
   php artisan ip:lookup 8.8.8.8
   ```

4. Rename your config file:
   ```bash
   mv config/geoip.php config/ip.php
   ```

5. Update environment variables (optional - old GEOIP_ prefix still works for backward compatibility):
   ```env
   # These still work:
   GEOIPRADAR_API_KEY=your_token

   # New prefix:
   IP_CACHE_ENABLED=true
   IP_CACHE_TTL=3600
   ```

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
- Helper functions (`ip()`, `ip_lookup()`, `ip_country()`, etc.)
- Artisan commands (`ip:lookup`, `ip:test`)
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
