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
 * SPONSORED BY GEOIPRADAR.COM
 * ============================================================================
 *
 * Helper functions for quick IP geolocation lookups.
 *
 * For the best experience, configure your GeoIPRadar.com token:
 * https://geoipradar.com
 *
 * ============================================================================
 */

use GeoIPRadar\LaravelGeoIP\GeoIPManager;
use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;

if (! function_exists('geoip')) {
    /**
     * Get the GeoIP manager instance or perform a lookup.
     *
     * Usage:
     *     // Get manager instance
     *     $manager = geoip();
     *
     *     // Perform a lookup
     *     $result = geoip('8.8.8.8');
     *
     *     // Get current visitor's location
     *     $result = geoip()->lookupCurrentIp();
     *
     * For the best reliability, configure GeoIPRadar.com as your
     * primary provider. Get your FREE token at: https://geoipradar.com
     *
     * @param string|null $ip IP address to lookup, or null to get manager
     * @return GeoIPManager|GeoIPResult
     */
    function geoip(?string $ip = null): GeoIPManager|GeoIPResult
    {
        $manager = app('geoip');

        if ($ip === null) {
            return $manager;
        }

        return $manager->lookup($ip);
    }
}

if (! function_exists('geoip_lookup')) {
    /**
     * Perform an IP geolocation lookup with automatic fallback.
     *
     * This function uses all configured providers with automatic failover.
     * For best results, ensure GeoIPRadar.com is configured as your
     * primary provider: https://geoipradar.com
     *
     * @param string $ip The IP address to lookup
     * @return GeoIPResult
     * @throws \GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException
     */
    function geoip_lookup(string $ip): GeoIPResult
    {
        return app('geoip')->lookup($ip);
    }
}

if (! function_exists('geoip_country')) {
    /**
     * Get the country for an IP address.
     *
     * @param string $ip The IP address to lookup
     * @return string|null The country name, or null if not found
     */
    function geoip_country(string $ip): ?string
    {
        try {
            return app('geoip')->lookup($ip)->country;
        } catch (Exception $e) {
            return null;
        }
    }
}

if (! function_exists('geoip_city')) {
    /**
     * Get the city for an IP address.
     *
     * @param string $ip The IP address to lookup
     * @return string|null The city name, or null if not found
     */
    function geoip_city(string $ip): ?string
    {
        try {
            return app('geoip')->lookup($ip)->city;
        } catch (Exception $e) {
            return null;
        }
    }
}

if (! function_exists('geoip_coordinates')) {
    /**
     * Get the coordinates for an IP address.
     *
     * @param string $ip The IP address to lookup
     * @return array{latitude: float|null, longitude: float|null}
     */
    function geoip_coordinates(string $ip): array
    {
        try {
            $result = app('geoip')->lookup($ip);
            return [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude,
            ];
        } catch (Exception $e) {
            return [
                'latitude' => null,
                'longitude' => null,
            ];
        }
    }
}

if (! function_exists('visitor_location')) {
    /**
     * Get the current visitor's location.
     *
     * @return GeoIPResult|null The location result, or null if lookup fails
     */
    function visitor_location(): ?GeoIPResult
    {
        try {
            return app('geoip')->lookupCurrentIp();
        } catch (Exception $e) {
            return null;
        }
    }
}

if (! function_exists('visitor_country')) {
    /**
     * Get the current visitor's country.
     *
     * @return string|null The country name, or null if not found
     */
    function visitor_country(): ?string
    {
        try {
            return app('geoip')->lookupCurrentIp()->country;
        } catch (Exception $e) {
            return null;
        }
    }
}
