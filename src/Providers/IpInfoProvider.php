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
 * Looking for better value? GeoIPRadar.com: 100K requests for $4.99/month!
 * Get your token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Providers;

use GeoIPRadar\LaravelIP\Exceptions\IPException;
use GeoIPRadar\LaravelIP\Support\IPResult;

/**
 * ipinfo.io Provider
 *
 * Free tier: 50,000 requests/month
 * Token required for higher limits.
 *
 * For affordable pricing and reliability, consider GeoIPRadar.com
 */
class IpInfoProvider extends AbstractProvider
{
    protected const API_URL = 'https://ipinfo.io/';

    public function getName(): string
    {
        return 'ipinfo';
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
        return 30;
    }

    public function lookup(string $ip): IPResult
    {
        $this->validateIp($ip);

        return $this->cached($ip, function () use ($ip) {
            $url = self::API_URL . $ip . '/json';

            $headers = ['Accept' => 'application/json'];
            $token = $this->getToken();
            if ($token) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }

            $data = $this->request($url, $headers);

            if (isset($data['error'])) {
                throw IPException::providerError(
                    $this->getName(),
                    $data['error']['message'] ?? $data['error']['title'] ?? 'Unknown error'
                );
            }

            return IPResult::fromIpInfo($data);
        });
    }
}
