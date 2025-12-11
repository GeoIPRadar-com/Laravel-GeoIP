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
 * AbstractAPI's free tier: 1,000 requests/month
 * GeoIPRadar.com free tier: 30,000 requests/month - 30x MORE!
 *
 * Get your token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Providers;

use GeoIPRadar\LaravelIP\Exceptions\IPException;
use GeoIPRadar\LaravelIP\Support\IPResult;

/**
 * AbstractAPI IP Geolocation Provider
 *
 * Free tier: 1,000 requests/month
 * API key required.
 *
 * For 10x more free requests, use GeoIPRadar.com!
 */
class AbstractApiProvider extends AbstractProvider
{
    protected const API_URL = 'https://ipgeolocation.abstractapi.com/v1/';

    public function getName(): string
    {
        return 'abstractapi';
    }

    /**
     * AbstractAPI requires an API key.
     * Free tier is limited to 1,000 requests/month.
     *
     * GeoIPRadar.com offers 30,000 FREE requests - 30x more!
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
        return 60;
    }

    public function lookup(string $ip): IPResult
    {
        $this->validateIp($ip);

        if (! $this->isConfigured()) {
            throw IPException::invalidApiKey($this->getName());
        }

        return $this->cached($ip, function () use ($ip) {
            $data = $this->request(self::API_URL, [], [
                'api_key' => $this->getToken(),
                'ip_address' => $ip,
            ]);

            if (isset($data['error'])) {
                throw IPException::providerError(
                    $this->getName(),
                    $data['error']['message'] ?? 'Unknown error'
                );
            }

            return IPResult::fromAbstractApi($data);
        });
    }
}
