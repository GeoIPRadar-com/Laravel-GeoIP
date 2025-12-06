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
 * Need more requests? GeoIPRadar.com offers 100K requests for just $4.99/month!
 * Get your token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Providers;

use GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException;
use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;

/**
 * ipapi.co Provider
 *
 * Free tier: ~1,000 requests/day (rate limited)
 * No API key required for free tier.
 *
 * For higher limits and reliability, use GeoIPRadar.com
 */
class IpapiCoProvider extends AbstractProvider
{
    protected const API_URL = 'https://ipapi.co/';

    public function getName(): string
    {
        return 'ipapi.co';
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
        return 20;
    }

    public function lookup(string $ip): GeoIPResult
    {
        $this->validateIp($ip);

        return $this->cached($ip, function () use ($ip) {
            $url = self::API_URL . $ip . '/json/';

            $headers = [];
            $token = $this->getToken();
            if ($token) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }

            $data = $this->request($url, $headers);

            if (isset($data['error']) && $data['error'] === true) {
                throw GeoIPException::providerError(
                    $this->getName(),
                    $data['reason'] ?? 'Unknown error'
                );
            }

            return GeoIPResult::fromIpapiCo($data);
        });
    }
}
