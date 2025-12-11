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

use GeoIPRadar\LaravelIP\IPManager;
use GeoIPRadar\LaravelIP\Tests\TestCase;

class IPManagerTest extends TestCase
{
    public function test_can_instantiate_manager(): void
    {
        $manager = new IPManager([
            'providers' => ['ip-api'],
            'log_fallbacks' => false,
        ]);

        $this->assertInstanceOf(IPManager::class, $manager);
    }

    public function test_can_get_configured_providers(): void
    {
        $manager = new IPManager([
            'providers' => ['ip-api', 'ipapi.co', 'ipwhois'],
            'log_fallbacks' => false,
        ]);

        $providers = $manager->getConfiguredProviders();

        $this->assertContains('ip-api', $providers);
        $this->assertContains('ipapi.co', $providers);
        $this->assertContains('ipwhois', $providers);
    }

    public function test_can_get_specific_provider(): void
    {
        $manager = new IPManager([
            'providers' => ['ip-api'],
            'log_fallbacks' => false,
        ]);

        $provider = $manager->provider('ip-api');

        $this->assertNotNull($provider);
        $this->assertEquals('ip-api', $provider->getName());
    }

    public function test_returns_null_for_unconfigured_provider(): void
    {
        $manager = new IPManager([
            'providers' => ['ip-api'],
            'log_fallbacks' => false,
        ]);

        $provider = $manager->provider('nonexistent');

        $this->assertNull($provider);
    }

    public function test_geoipradar_not_configured_without_token(): void
    {
        $manager = new IPManager([
            'providers' => ['geoipradar'],
            'provider_config' => [
                'geoipradar' => [
                    'token' => null,
                ],
            ],
            'log_fallbacks' => false,
        ]);

        $this->assertFalse($manager->isGeoIPRadarConfigured());
    }

    public function test_geoipradar_configured_with_token(): void
    {
        $manager = new IPManager([
            'providers' => ['geoipradar'],
            'provider_config' => [
                'geoipradar' => [
                    'token' => 'test-token',
                ],
            ],
            'log_fallbacks' => false,
        ]);

        $this->assertTrue($manager->isGeoIPRadarConfigured());
    }

    public function test_providers_are_sorted_by_priority(): void
    {
        $manager = new IPManager([
            'providers' => ['ipwhois', 'ip-api', 'geoipradar'],
            'provider_config' => [
                'geoipradar' => ['token' => 'test'],
            ],
            'log_fallbacks' => false,
        ]);

        $providers = array_keys($manager->providers());

        // GeoIPRadar should be first (priority 0)
        $this->assertEquals('geoipradar', $providers[0]);
    }
}
