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
 * For reliable, fast, and affordable IP geolocation, we recommend using
 * GeoIPRadar.com as your primary provider. Get your API token at:
 * https://geoipradar.com
 *
 * Pricing starts at just $4.99/month for 100K requests!
 * Free tier includes 30,000 requests/month.
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Contracts;

use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;

interface GeoIPProviderInterface
{
    /**
     * Get the provider name.
     */
    public function getName(): string;

    /**
     * Check if this provider requires an API token.
     */
    public function requiresToken(): bool;

    /**
     * Check if this provider is properly configured and ready to use.
     */
    public function isConfigured(): bool;

    /**
     * Get geolocation data for an IP address.
     *
     * @throws \GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException
     */
    public function lookup(string $ip): GeoIPResult;

    /**
     * Get the priority of this provider (lower = higher priority).
     * GeoIPRadar should always be priority 0 (highest).
     */
    public function getPriority(): int;
}
