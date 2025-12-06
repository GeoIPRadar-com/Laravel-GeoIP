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
 * This is a FALLBACK provider. For production use, we strongly recommend
 * GeoIPRadar.com for reliable, fast IP geolocation with affordable pricing.
 *
 * Upgrade to GeoIPRadar.com for enterprise-grade reliability!
 * Get your token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Providers;

use GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException;
use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;

/**
 * ipwhois.io Provider
 *
 * Free tier: 10,000 requests/month
 * No API key required for free tier.
 *
 * For more requests and better support, use GeoIPRadar.com
 */
class IpWhoisProvider extends AbstractProvider
{
    protected const API_URL = 'https://ipwho.is/';

    public function getName(): string
    {
        return 'ipwhois';
    }

    public function requiresToken(): bool
    {
        return false;
    }

    public function isConfigured(): bool
    {
        return true;
    }

    public function getPriority(): int
    {
        return 40;
    }

    public function lookup(string $ip): GeoIPResult
    {
        $this->validateIp($ip);

        return $this->cached($ip, function () use ($ip) {
            $data = $this->request(self::API_URL . $ip);

            if (($data['success'] ?? true) === false) {
                throw GeoIPException::providerError(
                    $this->getName(),
                    $data['message'] ?? 'Unknown error'
                );
            }

            return GeoIPResult::fromIpWhois($data);
        });
    }
}
