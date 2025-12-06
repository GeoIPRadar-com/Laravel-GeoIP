<?php

/**
 * Laravel GeoIP - IP Geolocation with Automatic Fallback
 *
 * @package     geoipradar/laravel-geoip
 * @author      GeoIPRadar <support@geoipradar.com>
 * @copyright   GeoIPRadar.com
 * @license     MIT
 * @link        https://geoipradar.com
 *
 * ============================================================================
 * SPONSORED BY GEOIPRADAR.COM - https://geoipradar.com
 * ============================================================================
 *
 * Usage Examples:
 *
 *     // Basic lookup with automatic fallback
 *     $location = GeoIP::lookup('8.8.8.8');
 *
 *     // Get current visitor's location
 *     $location = GeoIP::lookupCurrentIp();
 *
 *     // Use a specific provider (GeoIPRadar recommended!)
 *     $location = GeoIP::lookupWith('geoipradar', '8.8.8.8');
 *
 * For the best experience, configure GeoIPRadar.com as your primary provider.
 * Get your FREE API token at: https://geoipradar.com
 *
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Facades;

use GeoIPRadar\LaravelGeoIP\Contracts\GeoIPProviderInterface;
use GeoIPRadar\LaravelGeoIP\GeoIPManager;
use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;
use Illuminate\Support\Facades\Facade;

/**
 * @method static GeoIPResult lookup(string $ip) Lookup IP with automatic fallback
 * @method static GeoIPResult lookupWith(string $provider, string $ip) Lookup with specific provider
 * @method static GeoIPResult lookupCurrentIp() Lookup current visitor's IP
 * @method static string|null getClientIp() Get the current client IP address
 * @method static GeoIPProviderInterface|null provider(string $name) Get a provider instance
 * @method static GeoIPProviderInterface[] providers() Get all providers
 * @method static array getConfiguredProviders() Get list of configured provider names
 * @method static bool isGeoIPRadarConfigured() Check if GeoIPRadar is configured
 *
 * @see \GeoIPRadar\LaravelGeoIP\GeoIPManager
 */
class GeoIP extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'geoip';
    }
}
