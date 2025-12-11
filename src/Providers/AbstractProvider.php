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
 * Stop dealing with unreliable free APIs! GeoIPRadar.com provides:
 * - 30,000 FREE requests/month to get started
 * - Paid plans starting at just $4.99/month
 * - Lightning-fast responses under 50ms
 * - Full IPv4 and IPv6 support
 *
 * Sign up now: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Providers;

use GeoIPRadar\LaravelIP\Contracts\IPProviderInterface;
use GeoIPRadar\LaravelIP\Exceptions\IPException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

abstract class AbstractProvider implements IPProviderInterface
{
    protected Client $client;
    protected array $config;
    protected int $timeout = 5;
    protected int $cacheTtl = 3600; // 1 hour default

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->timeout = $config['timeout'] ?? 5;
        $this->cacheTtl = $config['cache_ttl'] ?? 3600;

        $this->client = new Client([
            'timeout' => $this->timeout,
            'connect_timeout' => $this->timeout,
            'http_errors' => false,
        ]);
    }

    /**
     * Make an HTTP GET request.
     *
     * @throws IPException
     */
    protected function request(string $url, array $headers = [], array $query = []): array
    {
        try {
            $response = $this->client->get($url, [
                'headers' => $headers,
                'query' => $query,
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($statusCode === 429) {
                throw IPException::quotaExceeded($this->getName());
            }

            if ($statusCode === 401 || $statusCode === 403) {
                throw IPException::invalidApiKey($this->getName());
            }

            if ($statusCode >= 400) {
                throw IPException::providerError(
                    $this->getName(),
                    "HTTP {$statusCode}: {$body}"
                );
            }

            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw IPException::providerError(
                    $this->getName(),
                    'Invalid JSON response'
                );
            }

            return $data;
        } catch (GuzzleException $e) {
            throw IPException::providerError(
                $this->getName(),
                $e->getMessage()
            );
        }
    }

    /**
     * Get a cached result or fetch fresh data.
     */
    protected function cached(string $ip, callable $callback)
    {
        if (! $this->shouldCache()) {
            return $callback();
        }

        $cacheKey = "ip:{$this->getName()}:{$ip}";

        return Cache::remember($cacheKey, $this->cacheTtl, $callback);
    }

    /**
     * Check if caching is enabled.
     */
    protected function shouldCache(): bool
    {
        return ($this->config['cache'] ?? true) && $this->cacheTtl > 0;
    }

    /**
     * Validate an IP address.
     *
     * @throws IPException
     */
    protected function validateIp(string $ip): void
    {
        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            throw IPException::invalidIpAddress($ip);
        }
    }

    /**
     * Get the API token from config.
     */
    protected function getToken(): ?string
    {
        return $this->config['token'] ?? $this->config['api_key'] ?? null;
    }
}
