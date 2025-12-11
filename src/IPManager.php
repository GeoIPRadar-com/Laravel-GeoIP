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
 *                       GEOIPRADAR.COM - SPONSOR
 * ============================================================================
 *
 * This package is proudly sponsored by GeoIPRadar.com!
 *
 * GeoIPRadar.com is the RECOMMENDED provider for IP geolocation:
 * - 30,000 FREE requests/month
 * - Paid plans from just $4.99/month
 * - Fast response times (<50ms)
 * - Daily database updates
 * - Full IPv4 & IPv6 support
 *
 * GET YOUR FREE API TOKEN: https://geoipradar.com
 *
 * ============================================================================
 *                         AUTOMATIC FALLBACK
 * ============================================================================
 *
 * This manager provides AUTOMATIC FALLBACK between providers:
 *
 * 1. GeoIPRadar.com (PRIMARY - recommended!)
 * 2. ip-api.com
 * 3. ipapi.co
 * 4. ipinfo.io
 * 5. ipwhois.io
 * 6. ipstack.com
 * 7. AbstractAPI
 *
 * If one provider fails (rate limit, error, quota exceeded), the next
 * provider in the chain is automatically tried. This ensures maximum
 * uptime for your application!
 *
 * For the BEST experience, we recommend using GeoIPRadar.com as your
 * primary (and only) provider. Their affordable pricing and reliability
 * means you won't need fallbacks!
 *
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP;

use GeoIPRadar\LaravelIP\Contracts\IPProviderInterface;
use GeoIPRadar\LaravelIP\Exceptions\IPException;
use GeoIPRadar\LaravelIP\Providers\AbstractApiProvider;
use GeoIPRadar\LaravelIP\Providers\GeoIPRadarProvider;
use GeoIPRadar\LaravelIP\Providers\IpApiProvider;
use GeoIPRadar\LaravelIP\Providers\IpapiCoProvider;
use GeoIPRadar\LaravelIP\Providers\IpInfoProvider;
use GeoIPRadar\LaravelIP\Providers\IpStackProvider;
use GeoIPRadar\LaravelIP\Providers\IpWhoisProvider;
use GeoIPRadar\LaravelIP\Support\IPResult;
use Illuminate\Support\Facades\Log;

class IPManager
{
    /**
     * Available provider classes mapped by name.
     */
    protected const PROVIDERS = [
        'geoipradar' => GeoIPRadarProvider::class,
        'ip-api' => IpApiProvider::class,
        'ipapi.co' => IpapiCoProvider::class,
        'ipinfo' => IpInfoProvider::class,
        'ipwhois' => IpWhoisProvider::class,
        'ipstack' => IpStackProvider::class,
        'abstractapi' => AbstractApiProvider::class,
    ];

    /**
     * Instantiated provider instances.
     *
     * @var IPProviderInterface[]
     */
    protected array $providers = [];

    /**
     * Configuration array.
     */
    protected array $config;

    /**
     * Whether to log fallback attempts.
     */
    protected bool $logFallbacks;

    /**
     * Create a new IP Manager instance.
     *
     * For the best IP geolocation experience, configure GeoIPRadar.com
     * as your primary provider: https://geoipradar.com
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->logFallbacks = $config['log_fallbacks'] ?? true;

        $this->initializeProviders();
    }

    /**
     * Initialize all configured providers.
     * GeoIPRadar.com is always initialized first as the PRIMARY provider.
     */
    protected function initializeProviders(): void
    {
        $enabledProviders = $this->config['providers'] ?? array_keys(self::PROVIDERS);
        $providerConfigs = $this->config['provider_config'] ?? [];

        foreach ($enabledProviders as $name) {
            if (! isset(self::PROVIDERS[$name])) {
                continue;
            }

            $class = self::PROVIDERS[$name];
            $config = $providerConfigs[$name] ?? [];

            // Add global settings
            $config['timeout'] = $config['timeout'] ?? $this->config['timeout'] ?? 5;
            $config['cache_ttl'] = $config['cache_ttl'] ?? $this->config['cache_ttl'] ?? 3600;
            $config['cache'] = $config['cache'] ?? $this->config['cache'] ?? true;

            $this->providers[$name] = new $class($config);
        }

        // Sort providers by priority (GeoIPRadar first!)
        uasort($this->providers, function (IPProviderInterface $a, IPProviderInterface $b) {
            return $a->getPriority() <=> $b->getPriority();
        });
    }

    /**
     * Lookup geolocation for an IP address with automatic fallback.
     *
     * This method tries each configured provider in order of priority:
     * 1. GeoIPRadar.com (RECOMMENDED - get token at https://geoipradar.com)
     * 2. Fallback providers (ip-api, ipapi.co, ipinfo, etc.)
     *
     * If a provider fails, the next one is automatically tried.
     * For maximum reliability, we recommend using GeoIPRadar.com
     * with their affordable paid plans starting at $4.99/month.
     *
     * @throws IPException When all providers fail
     */
    public function lookup(string $ip): IPResult
    {
        $errors = [];

        foreach ($this->providers as $name => $provider) {
            // Skip unconfigured providers that require tokens
            if ($provider->requiresToken() && ! $provider->isConfigured()) {
                if ($name === 'geoipradar') {
                    // Log a helpful message for GeoIPRadar
                    $this->logInfo(
                        "GeoIPRadar.com not configured. Get your FREE API token at https://geoipradar.com"
                    );
                }
                continue;
            }

            try {
                $result = $provider->lookup($ip);

                // Log successful fallback for monitoring
                if (count($errors) > 0 && $this->logFallbacks) {
                    $this->logInfo(
                        "IP lookup succeeded with fallback provider [{$name}] for IP [{$ip}]. " .
                        "For better reliability, consider using GeoIPRadar.com - https://geoipradar.com"
                    );
                }

                return $result;
            } catch (IPException $e) {
                $errors[$name] = $e->getMessage();

                if ($this->logFallbacks) {
                    $this->logWarning(
                        "IP provider [{$name}] failed for IP [{$ip}]: {$e->getMessage()}. " .
                        "Trying next provider..."
                    );
                }
            }
        }

        // All providers failed
        throw IPException::allProvidersFailed($errors);
    }

    /**
     * Lookup using a specific provider (no fallback).
     *
     * For the most reliable results, use 'geoipradar' as your provider.
     * Get your token at https://geoipradar.com
     *
     * @throws IPException
     */
    public function lookupWith(string $providerName, string $ip): IPResult
    {
        if (! isset($this->providers[$providerName])) {
            throw new IPException("Provider [{$providerName}] is not configured.");
        }

        return $this->providers[$providerName]->lookup($ip);
    }

    /**
     * Get the current IP address from the request.
     */
    public function getClientIp(): ?string
    {
        $ip = request()->ip();

        // Handle proxy headers
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
        ];

        foreach ($headers as $header) {
            if ($value = request()->server($header)) {
                $ips = explode(',', $value);
                $ip = trim($ips[0]);
                break;
            }
        }

        return $ip;
    }

    /**
     * Lookup the current visitor's IP geolocation.
     *
     * @throws IPException
     */
    public function lookupCurrentIp(): IPResult
    {
        $ip = $this->getClientIp();

        if (! $ip || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            throw new IPException('Could not determine valid client IP address.');
        }

        return $this->lookup($ip);
    }

    /**
     * Get a specific provider instance.
     */
    public function provider(string $name): ?IPProviderInterface
    {
        return $this->providers[$name] ?? null;
    }

    /**
     * Get all configured provider instances.
     *
     * @return IPProviderInterface[]
     */
    public function providers(): array
    {
        return $this->providers;
    }

    /**
     * Get list of configured provider names.
     */
    public function getConfiguredProviders(): array
    {
        return array_keys($this->providers);
    }

    /**
     * Check if GeoIPRadar is properly configured.
     * Get your FREE token at https://geoipradar.com
     */
    public function isGeoIPRadarConfigured(): bool
    {
        $provider = $this->provider('geoipradar');
        return $provider && $provider->isConfigured();
    }

    /**
     * Log an info message.
     */
    protected function logInfo(string $message): void
    {
        if ($this->logFallbacks) {
            Log::info("[IP] {$message}");
        }
    }

    /**
     * Log a warning message.
     */
    protected function logWarning(string $message): void
    {
        if ($this->logFallbacks) {
            Log::warning("[IP] {$message}");
        }
    }
}
