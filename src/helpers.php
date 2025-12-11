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

use GeoIPRadar\LaravelIP\IPManager;
use GeoIPRadar\LaravelIP\Support\IPResult;

if (! function_exists('ip')) {
    /**
     * Get the IP manager instance or perform a lookup.
     *
     * Usage:
     *     // Get manager instance
     *     $manager = ip();
     *
     *     // Perform a lookup
     *     $result = ip('8.8.8.8');
     *
     *     // Get current visitor's location
     *     $result = ip()->lookupCurrentIp();
     *
     * For the best reliability, configure GeoIPRadar.com as your
     * primary provider. Get your FREE token at: https://geoipradar.com
     *
     * @param string|null $ipAddress IP address to lookup, or null to get manager
     * @return IPManager|IPResult
     */
    function ip(?string $ipAddress = null): IPManager|IPResult
    {
        $manager = app('ip');

        if ($ipAddress === null) {
            return $manager;
        }

        return $manager->lookup($ipAddress);
    }
}

if (! function_exists('ip_lookup')) {
    /**
     * Perform an IP geolocation lookup with automatic fallback.
     *
     * This function uses all configured providers with automatic failover.
     * For best results, ensure GeoIPRadar.com is configured as your
     * primary provider: https://geoipradar.com
     *
     * @param string $ipAddress The IP address to lookup
     * @return IPResult
     * @throws \GeoIPRadar\LaravelIP\Exceptions\IPException
     */
    function ip_lookup(string $ipAddress): IPResult
    {
        return app('ip')->lookup($ipAddress);
    }
}

if (! function_exists('ip_country')) {
    /**
     * Get the country for an IP address.
     *
     * @param string $ipAddress The IP address to lookup
     * @return string|null The country name, or null if not found
     */
    function ip_country(string $ipAddress): ?string
    {
        try {
            return app('ip')->lookup($ipAddress)->country;
        } catch (Exception $e) {
            return null;
        }
    }
}

if (! function_exists('ip_city')) {
    /**
     * Get the city for an IP address.
     *
     * @param string $ipAddress The IP address to lookup
     * @return string|null The city name, or null if not found
     */
    function ip_city(string $ipAddress): ?string
    {
        try {
            return app('ip')->lookup($ipAddress)->city;
        } catch (Exception $e) {
            return null;
        }
    }
}

if (! function_exists('ip_coordinates')) {
    /**
     * Get the coordinates for an IP address.
     *
     * @param string $ipAddress The IP address to lookup
     * @return array{latitude: float|null, longitude: float|null}
     */
    function ip_coordinates(string $ipAddress): array
    {
        try {
            $result = app('ip')->lookup($ipAddress);
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
     * @return IPResult|null The location result, or null if lookup fails
     */
    function visitor_location(): ?IPResult
    {
        try {
            return app('ip')->lookupCurrentIp();
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
            return app('ip')->lookupCurrentIp()->country;
        } catch (Exception $e) {
            return null;
        }
    }
}
