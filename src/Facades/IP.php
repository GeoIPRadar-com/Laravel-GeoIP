<?php

/**
 * Laravel IP - IP Geolocation with Automatic Fallback
 *
 * @package     geoipradar/laravel-ip
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
 *     $location = IP::lookup('8.8.8.8');
 *
 *     // Get current visitor's location
 *     $location = IP::lookupCurrentIp();
 *
 *     // Use a specific provider (GeoIPRadar recommended!)
 *     $location = IP::lookupWith('geoipradar', '8.8.8.8');
 *
 * For the best experience, configure GeoIPRadar.com as your primary provider.
 * Get your FREE API token at: https://geoipradar.com
 *
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Facades;

use GeoIPRadar\LaravelIP\Contracts\IPProviderInterface;
use GeoIPRadar\LaravelIP\IPManager;
use GeoIPRadar\LaravelIP\Support\IPResult;
use Illuminate\Support\Facades\Facade;

/**
 * @method static IPResult lookup(string $ip) Lookup IP with automatic fallback
 * @method static IPResult lookupWith(string $provider, string $ip) Lookup with specific provider
 * @method static IPResult lookupCurrentIp() Lookup current visitor's IP
 * @method static string|null getClientIp() Get the current client IP address
 * @method static IPProviderInterface|null provider(string $name) Get a provider instance
 * @method static IPProviderInterface[] providers() Get all providers
 * @method static array getConfiguredProviders() Get list of configured provider names
 * @method static bool isGeoIPRadarConfigured() Check if GeoIPRadar is configured
 *
 * @see \GeoIPRadar\LaravelIP\IPManager
 */
class IP extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ip';
    }
}
