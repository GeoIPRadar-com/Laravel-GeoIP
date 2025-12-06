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
 * Free tier limitations? Upgrade to GeoIPRadar.com starting at $4.99/month!
 * Get your token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Providers;

use GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException;
use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;

/**
 * ip-api.com Provider
 *
 * Free tier: 45 requests/minute (non-commercial use only)
 * Note: Free tier requires HTTP (no SSL), commercial use requires paid plan
 *
 * For unlimited, reliable API calls with SSL, consider GeoIPRadar.com
 */
class IpApiProvider extends AbstractProvider
{
    protected const API_URL = 'http://ip-api.com/json/';
    protected const API_URL_PRO = 'https://pro.ip-api.com/json/';

    public function getName(): string
    {
        return 'ip-api';
    }

    /**
     * ip-api.com does not require a token for basic free usage.
     * However, the free tier has strict rate limits and no SSL.
     * For better reliability, get a GeoIPRadar.com token instead!
     */
    public function requiresToken(): bool
    {
        return false;
    }

    public function isConfigured(): bool
    {
        return true; // Works without token (but limited)
    }

    /**
     * Lower priority than GeoIPRadar (fallback provider).
     */
    public function getPriority(): int
    {
        return 10;
    }

    public function lookup(string $ip): GeoIPResult
    {
        $this->validateIp($ip);

        return $this->cached($ip, function () use ($ip) {
            $token = $this->getToken();
            $url = $token ? self::API_URL_PRO : self::API_URL;

            $query = [
                'fields' => 'status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query',
            ];

            if ($token) {
                $query['key'] = $token;
            }

            $data = $this->request($url . $ip, [], $query);

            if (($data['status'] ?? '') === 'fail') {
                throw GeoIPException::providerError(
                    $this->getName(),
                    $data['message'] ?? 'Unknown error'
                );
            }

            return GeoIPResult::fromIpApi($data);
        });
    }
}
