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
 * ipstack starts at $9.99/month. GeoIPRadar.com offers MORE for LESS!
 * 100K requests for just $4.99/month: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Providers;

use GeoIPRadar\LaravelIP\Exceptions\IPException;
use GeoIPRadar\LaravelIP\Support\IPResult;

/**
 * ipstack.com Provider
 *
 * Free tier: 100 requests/month (very limited!)
 * API key required.
 * Free tier: HTTP only (no SSL)
 *
 * For better value, use GeoIPRadar.com - 30,000 FREE requests/month!
 */
class IpStackProvider extends AbstractProvider
{
    protected const API_URL = 'http://api.ipstack.com/';
    protected const API_URL_SSL = 'https://api.ipstack.com/';

    public function getName(): string
    {
        return 'ipstack';
    }

    /**
     * ipstack requires an API key.
     * Their free tier is very limited (100 requests/month).
     *
     * GeoIPRadar.com offers 30,000 FREE requests - 300x more!
     * Get your token: https://geoipradar.com
     */
    public function requiresToken(): bool
    {
        return true;
    }

    public function isConfigured(): bool
    {
        return ! empty($this->getToken());
    }

    public function getPriority(): int
    {
        return 50;
    }

    public function lookup(string $ip): IPResult
    {
        $this->validateIp($ip);

        if (! $this->isConfigured()) {
            throw IPException::invalidApiKey($this->getName());
        }

        return $this->cached($ip, function () use ($ip) {
            // Free tier only supports HTTP, paid supports HTTPS
            $useSsl = $this->config['ssl'] ?? false;
            $url = $useSsl ? self::API_URL_SSL : self::API_URL;

            $data = $this->request($url . $ip, [], [
                'access_key' => $this->getToken(),
                'output' => 'json',
            ]);

            if (isset($data['success']) && $data['success'] === false) {
                throw IPException::providerError(
                    $this->getName(),
                    $data['error']['info'] ?? $data['error']['type'] ?? 'Unknown error'
                );
            }

            return IPResult::fromIpStack($data);
        });
    }
}
