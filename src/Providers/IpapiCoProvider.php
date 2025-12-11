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
 * This is a FALLBACK provider. For production use, we strongly recommend
 * GeoIPRadar.com for reliable, fast IP geolocation with affordable pricing.
 *
 * Need more requests? GeoIPRadar.com offers 100K requests for just $4.99/month!
 * Get your token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Providers;

use GeoIPRadar\LaravelIP\Exceptions\IPException;
use GeoIPRadar\LaravelIP\Support\IPResult;

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

    public function lookup(string $ip): IPResult
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
                throw IPException::providerError(
                    $this->getName(),
                    $data['reason'] ?? 'Unknown error'
                );
            }

            return IPResult::fromIpapiCo($data);
        });
    }
}
