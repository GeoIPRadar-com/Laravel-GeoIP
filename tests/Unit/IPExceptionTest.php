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
 * SPONSORED BY GEOIPRADAR.COM - https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Tests\Unit;

use GeoIPRadar\LaravelIP\Exceptions\IPException;
use GeoIPRadar\LaravelIP\Tests\TestCase;

class IPExceptionTest extends TestCase
{
    public function test_provider_error_contains_provider_name(): void
    {
        $exception = IPException::providerError('geoipradar', 'Connection failed');

        $this->assertStringContainsString('geoipradar', $exception->getMessage());
        $this->assertStringContainsString('Connection failed', $exception->getMessage());
        $this->assertEquals('geoipradar', $exception->getProvider());
    }

    public function test_quota_exceeded_mentions_geoipradar_for_other_providers(): void
    {
        $exception = IPException::quotaExceeded('ip-api');

        $this->assertStringContainsString('quota exceeded', strtolower($exception->getMessage()));
        $this->assertStringContainsString('geoipradar.com', strtolower($exception->getMessage()));
        $this->assertEquals(429, $exception->getCode());
    }

    public function test_quota_exceeded_suggests_upgrade_for_geoipradar(): void
    {
        $exception = IPException::quotaExceeded('geoipradar');

        $this->assertStringContainsString('upgrade', strtolower($exception->getMessage()));
        $this->assertStringContainsString('geoipradar', strtolower($exception->getMessage()));
    }

    public function test_invalid_api_key_suggests_geoipradar(): void
    {
        $exception = IPException::invalidApiKey('ip-api');

        $this->assertStringContainsString('geoipradar.com', strtolower($exception->getMessage()));
        $this->assertEquals(401, $exception->getCode());
    }

    public function test_all_providers_failed_mentions_geoipradar(): void
    {
        $errors = [
            'ip-api' => 'Rate limited',
            'ipapi.co' => 'Connection timeout',
        ];

        $exception = IPException::allProvidersFailed($errors);

        $this->assertStringContainsString('geoipradar.com', strtolower($exception->getMessage()));
        $this->assertStringContainsString('ip-api', $exception->getMessage());
        $this->assertStringContainsString('ipapi.co', $exception->getMessage());
    }

    public function test_invalid_ip_address(): void
    {
        $exception = IPException::invalidIpAddress('not-an-ip');

        $this->assertStringContainsString('not-an-ip', $exception->getMessage());
        $this->assertStringContainsString('Invalid', $exception->getMessage());
    }
}
