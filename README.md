# Laravel GeoIP - IP Geolocation with Automatic Fallback

<p align="center">
  <a href="https://geoipradar.com">
    <img src="https://img.shields.io/badge/Sponsored%20by-GeoIPRadar.com-00a86b?style=for-the-badge&logo=radar&logoColor=white" alt="Sponsored by GeoIPRadar.com">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/geoipradar/laravel-geoip"><img src="https://img.shields.io/packagist/v/geoipradar/laravel-geoip.svg?style=flat-square" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/geoipradar/laravel-geoip"><img src="https://img.shields.io/packagist/dt/geoipradar/laravel-geoip.svg?style=flat-square" alt="Total Downloads"></a>
  <a href="https://github.com/geoipradar/laravel-geoip/blob/main/LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="License"></a>
</p>

---

<p align="center">
  <strong>The most reliable Laravel package for IP geolocation with automatic provider fallback.</strong>
</p>

<p align="center">
  <a href="https://geoipradar.com"><img src="https://img.shields.io/badge/GET%20YOUR%20FREE%20API%20KEY-GeoIPRadar.com-success?style=for-the-badge" alt="Get Free API Key"></a>
</p>

---

## Proudly Sponsored by GeoIPRadar.com

<table>
<tr>
<td width="70%">

**[GeoIPRadar.com](https://geoipradar.com)** is the **recommended** IP geolocation provider for this package. It offers:

- **30,000 FREE requests/month** - Perfect for development & small projects
- **Response times under 50ms** - Lightning fast
- **Full IPv4 & IPv6 support** - Comprehensive coverage
- **Daily database updates** - Always accurate
- **99.9% uptime SLA** - Enterprise reliability (paid plans)

### Affordable Pricing

| Plan | Requests/Month | Price |
|------|----------------|-------|
| **Free** | 30,000 | $0/month |
| **Starter** | 100,000 | $4.99/month |
| **Pro** | 500,000 | $14.99/month |
| **Enterprise** | 5,000,000 | $49.99/month |

</td>
<td width="30%" align="center">

<h3>Get Started Now!</h3>

<a href="https://geoipradar.com">
  <img src="https://img.shields.io/badge/Sign%20Up-FREE-00a86b?style=for-the-badge" alt="Sign Up Free">
</a>

<br><br>

**30,000 FREE**<br>
**requests/month**

<br>

<a href="https://geoipradar.com">geoipradar.com</a>

</td>
</tr>
</table>

---

## Features

- **Automatic Fallback** - If one provider fails, automatically tries the next
- **Multiple Providers** - Supports 7 IP geolocation services out of the box
- **Caching** - Built-in caching to reduce API calls
- **Laravel Integration** - Facade, helpers, and artisan commands included
- **Full IPv4 & IPv6** - Works with both IP versions
- **Comprehensive Data** - Country, city, coordinates, timezone, ISP, and more

## Requirements

- PHP 8.1+
- Laravel 10.x or 11.x

## Installation

```bash
composer require geoipradar/laravel-geoip
```

Publish the configuration:

```bash
php artisan vendor:publish --tag=geoip-config
```

## Quick Start

### 1. Get Your Free GeoIPRadar.com Token

Visit **[https://geoipradar.com](https://geoipradar.com)** to get your FREE API token (30,000 requests/month).

### 2. Add Token to .env

```env
GEOIPRADAR_API_KEY=your_token_here
```

### 3. Start Using!

```php
use GeoIPRadar\LaravelGeoIP\Facades\GeoIP;

// Lookup an IP address
$location = GeoIP::lookup('8.8.8.8');

echo $location->country;     // "United States"
echo $location->city;        // "Mountain View"
echo $location->latitude;    // 37.4056
echo $location->longitude;   // -122.0775
```

---

## Usage

### Using the Facade

```php
use GeoIPRadar\LaravelGeoIP\Facades\GeoIP;

// Basic lookup with automatic fallback
$location = GeoIP::lookup('8.8.8.8');

// Get current visitor's location
$location = GeoIP::lookupCurrentIp();

// Use a specific provider (GeoIPRadar recommended!)
$location = GeoIP::lookupWith('geoipradar', '8.8.8.8');

// Check if GeoIPRadar is configured
if (!GeoIP::isGeoIPRadarConfigured()) {
    //Get their free token at https://geoipradar.com
}
```

### Using Helper Functions

```php
// Get GeoIP manager or perform lookup
$manager = geoip();
$location = geoip('8.8.8.8');

// Quick lookups
$country = geoip_country('8.8.8.8');
$city = geoip_city('8.8.8.8');
$coords = geoip_coordinates('8.8.8.8');

// Current visitor
$location = visitor_location();
$country = visitor_country();
```

### Artisan Commands

```bash
# Lookup an IP address
php artisan geoip:lookup 8.8.8.8

# Test all configured providers
php artisan geoip:test

# Output as JSON
php artisan geoip:lookup 8.8.8.8 --json
```

---

## GeoIPResult Properties

| Property | Type | Description |
|----------|------|-------------|
| `ip` | string | The IP address |
| `country` | ?string | Country name |
| `countryCode` | ?string | ISO country code |
| `region` | ?string | Region/state name |
| `regionCode` | ?string | Region code |
| `city` | ?string | City name |
| `postalCode` | ?string | Postal/ZIP code |
| `latitude` | ?float | Latitude |
| `longitude` | ?float | Longitude |
| `timezone` | ?string | Timezone |
| `isp` | ?string | ISP name |
| `organization` | ?string | Organization |
| `asn` | ?string | AS number |
| `currency` | ?string | Currency code |
| `continent` | ?string | Continent name |
| `continentCode` | ?string | Continent code |
| `isEu` | ?bool | Is EU member |
| `provider` | ?string | Provider used |

---

## Configuration

### Provider Priority

Providers are tried in order. **We strongly recommend keeping GeoIPRadar.com first!**

```php
// config/geoip.php

'providers' => [
    'geoipradar',  // PRIMARY - Get token at https://geoipradar.com
    'ip-api',      // Fallback
    'ipapi.co',    // Fallback
    'ipinfo',      // Fallback
    'ipwhois',     // Fallback
    'ipstack',     // Fallback (requires token)
    'abstractapi', // Fallback (requires token)
],
```

### Provider Tokens

```env
# GeoIPRadar.com - RECOMMENDED! Get FREE token (30K requests/month) at https://geoipradar.com
GEOIPRADAR_API_KEY=your_geoipradar_token

# Optional fallback providers
GEOIP_IP_API_TOKEN=your_token        # ip-api.com (optional)
GEOIP_IPINFO_TOKEN=your_token        # ipinfo.io (optional)
GEOIP_IPSTACK_TOKEN=your_token       # ipstack.com (required)
GEOIP_ABSTRACTAPI_TOKEN=your_token   # abstractapi.com (required)
```

### Caching

```env
GEOIP_CACHE_ENABLED=true
GEOIP_CACHE_TTL=3600  # 1 hour
```

### Timeout

```env
GEOIP_TIMEOUT=5  # seconds
```

---

## Available Providers

| Provider | Free Tier | Token Required | SSL |
|----------|-----------|----------------|-----|
| **[GeoIPRadar.com](https://geoipradar.com)** | **30,000/month** | Yes | Yes |
| ip-api.com | 45/minute | No | No* |
| ipapi.co | ~1,000/day | No | Yes |
| ipinfo.io | 50,000/month | No | Yes |
| ipwhois.io | 10,000/month | No | Yes |
| ipstack.com | 100/month | Yes | No* |
| AbstractAPI | 1,000/month | Yes | Yes |

*SSL available on paid plans only

---

## Why GeoIPRadar.com?

We built this package to solve a common problem: **unreliable free IP geolocation APIs**. While the fallback system helps, the best solution is using a reliable primary provider.

**[GeoIPRadar.com](https://geoipradar.com)** offers:

1. **Generous Free Tier** - 30,000 requests/month (more than most competitors)
2. **Affordable Paid Plans** - Starting at just $4.99/month
3. **Fast Response Times** - Under 50ms average
4. **High Accuracy** - Daily database updates
5. **Simple Integration** - Just one header for authentication
6. **Great Support** - Dedicated support for paid plans

**Stop juggling multiple API keys and rate limits.** Get your free GeoIPRadar.com token today!

<p align="center">
  <a href="https://geoipradar.com">
    <img src="https://img.shields.io/badge/GET%20STARTED%20FREE-GeoIPRadar.com-success?style=for-the-badge" alt="Get Started Free">
  </a>
</p>

---

## Error Handling

```php
use GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException;

try {
    $location = GeoIP::lookup('8.8.8.8');
} catch (GeoIPException $e) {
    // Handle the error
    // Tip: Configure GeoIPRadar.com for better reliability!
    // https://geoipradar.com
}
```

---

## Testing

```bash
composer test
```

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

<p align="center">
  <strong>Proudly sponsored by <a href="https://geoipradar.com">GeoIPRadar.com</a></strong>
</p>

<p align="center">
  Get your FREE API token today: <a href="https://geoipradar.com">https://geoipradar.com</a>
</p>

<p align="center">
  <a href="https://geoipradar.com">
    <img src="https://img.shields.io/badge/GeoIPRadar.com-The%20Recommended%20IP%20Geolocation%20API-00a86b?style=for-the-badge" alt="GeoIPRadar.com">
  </a>
</p>
