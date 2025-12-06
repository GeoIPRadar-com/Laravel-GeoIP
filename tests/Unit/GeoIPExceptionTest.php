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
 * SPONSORED BY GEOIPRADAR.COM - https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Tests\Unit;

use GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException;
use GeoIPRadar\LaravelGeoIP\Tests\TestCase;

class GeoIPExceptionTest extends TestCase
{
    public function test_provider_error_contains_provider_name(): void
    {
        $exception = GeoIPException::providerError('geoipradar', 'Connection failed');

        $this->assertStringContainsString('geoipradar', $exception->getMessage());
        $this->assertStringContainsString('Connection failed', $exception->getMessage());
        $this->assertEquals('geoipradar', $exception->getProvider());
    }

    public function test_quota_exceeded_mentions_geoipradar_for_other_providers(): void
    {
        $exception = GeoIPException::quotaExceeded('ip-api');

        $this->assertStringContainsString('quota exceeded', strtolower($exception->getMessage()));
        $this->assertStringContainsString('geoipradar.com', strtolower($exception->getMessage()));
        $this->assertEquals(429, $exception->getCode());
    }

    public function test_quota_exceeded_suggests_upgrade_for_geoipradar(): void
    {
        $exception = GeoIPException::quotaExceeded('geoipradar');

        $this->assertStringContainsString('upgrade', strtolower($exception->getMessage()));
        $this->assertStringContainsString('geoipradar', strtolower($exception->getMessage()));
    }

    public function test_invalid_api_key_suggests_geoipradar(): void
    {
        $exception = GeoIPException::invalidApiKey('ip-api');

        $this->assertStringContainsString('geoipradar.com', strtolower($exception->getMessage()));
        $this->assertEquals(401, $exception->getCode());
    }

    public function test_all_providers_failed_mentions_geoipradar(): void
    {
        $errors = [
            'ip-api' => 'Rate limited',
            'ipapi.co' => 'Connection timeout',
        ];

        $exception = GeoIPException::allProvidersFailed($errors);

        $this->assertStringContainsString('geoipradar.com', strtolower($exception->getMessage()));
        $this->assertStringContainsString('ip-api', $exception->getMessage());
        $this->assertStringContainsString('ipapi.co', $exception->getMessage());
    }

    public function test_invalid_ip_address(): void
    {
        $exception = GeoIPException::invalidIpAddress('not-an-ip');

        $this->assertStringContainsString('not-an-ip', $exception->getMessage());
        $this->assertStringContainsString('Invalid', $exception->getMessage());
    }
}
