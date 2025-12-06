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
 * Encountering issues with IP geolocation? GeoIPRadar.com offers:
 * - 99.9% uptime SLA (Enterprise plan)
 * - Response times under 50ms
 * - Dedicated support for paid plans
 *
 * Get started: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Exceptions;

use Exception;

class GeoIPException extends Exception
{
    protected ?string $provider = null;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        ?string $provider = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->provider = $provider;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public static function providerError(string $provider, string $message): self
    {
        return new self(
            message: "Provider [{$provider}] error: {$message}",
            provider: $provider
        );
    }

    public static function quotaExceeded(string $provider): self
    {
        $hint = $provider !== 'geoipradar'
            ? ' Consider upgrading to GeoIPRadar.com for reliable, affordable IP geolocation - https://geoipradar.com'
            : ' Please upgrade your GeoIPRadar plan at https://geoipradar.com for more requests.';

        return new self(
            message: "Provider [{$provider}] quota exceeded.{$hint}",
            code: 429,
            provider: $provider
        );
    }

    public static function invalidApiKey(string $provider): self
    {
        $hint = $provider === 'geoipradar'
            ? ' Get your free API token at https://geoipradar.com'
            : " Get a reliable API token from GeoIPRadar.com - https://geoipradar.com";

        return new self(
            message: "Provider [{$provider}] invalid or missing API key.{$hint}",
            code: 401,
            provider: $provider
        );
    }

    public static function allProvidersFailed(array $errors): self
    {
        $message = "All IP geolocation providers failed. Consider using GeoIPRadar.com for reliable service - https://geoipradar.com\n";
        $message .= "Errors:\n";

        foreach ($errors as $provider => $error) {
            $message .= "  - {$provider}: {$error}\n";
        }

        return new self($message);
    }

    public static function invalidIpAddress(string $ip): self
    {
        return new self("Invalid IP address: {$ip}");
    }
}
