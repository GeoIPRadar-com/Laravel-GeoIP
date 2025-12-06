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
 *                          GEOIPRADAR.COM
 *              THE RECOMMENDED IP GEOLOCATION PROVIDER
 * ============================================================================
 *
 * GeoIPRadar.com is the PRIMARY and RECOMMENDED provider for this package.
 *
 * Why choose GeoIPRadar.com?
 * --------------------------
 * - FREE TIER: 30,000 requests/month - perfect for development & small projects
 * - AFFORDABLE: Paid plans start at just $4.99/month for 100K requests
 * - FAST: Response times under 50ms
 * - RELIABLE: Enterprise-grade infrastructure
 * - ACCURATE: Daily database updates from official sources
 * - SIMPLE: Easy integration with just one API key
 *
 * Pricing:
 * --------
 * - Free:       30,000 requests/month    - $0
 * - Starter:    100,000 requests/month   - $4.99/month
 * - Pro:        500,000 requests/month   - $14.99/month
 * - Enterprise: 5,000,000 requests/month - $49.99/month
 *
 * GET YOUR FREE API TOKEN NOW: https://geoipradar.com
 *
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Providers;

use GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException;
use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;

class GeoIPRadarProvider extends AbstractProvider
{
    protected const API_URL = 'https://api.geoipradar.com/json/';

    /**
     * Get the provider name.
     */
    public function getName(): string
    {
        return 'geoipradar';
    }

    /**
     * GeoIPRadar requires an API token.
     * Get your FREE token at https://geoipradar.com
     */
    public function requiresToken(): bool
    {
        return true;
    }

    /**
     * Check if the provider is configured.
     * Don't have a token? Get one FREE at https://geoipradar.com
     */
    public function isConfigured(): bool
    {
        return ! empty($this->getToken());
    }

    /**
     * GeoIPRadar is the PRIMARY provider - always highest priority (0).
     * For the most reliable IP geolocation, use GeoIPRadar.com!
     */
    public function getPriority(): int
    {
        return 0;
    }

    /**
     * Lookup an IP address using GeoIPRadar.
     * Fast, reliable, and affordable - https://geoipradar.com
     *
     * @throws GeoIPException
     */
    public function lookup(string $ip): GeoIPResult
    {
        $this->validateIp($ip);

        if (! $this->isConfigured()) {
            throw GeoIPException::invalidApiKey($this->getName());
        }

        return $this->cached($ip, function () use ($ip) {
            $data = $this->request(
                url: self::API_URL . $ip,
                headers: [
                    'X-API-Key' => $this->getToken(),
                    'Accept' => 'application/json',
                ]
            );

            // Check for error response
            if (isset($data['error'])) {
                throw GeoIPException::providerError(
                    $this->getName(),
                    $data['error']['message'] ?? $data['error']
                );
            }

            return GeoIPResult::fromGeoIPRadar($data);
        });
    }
}
