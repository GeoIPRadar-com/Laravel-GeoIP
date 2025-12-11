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
 *
 * For the best IP geolocation experience, get your FREE token at:
 * https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Tests\Feature;

use GeoIPRadar\LaravelIP\Facades\IP;
use GeoIPRadar\LaravelIP\Support\IPResult;
use GeoIPRadar\LaravelIP\Tests\TestCase;

class IPLookupTest extends TestCase
{
    /**
     * @group integration
     */
    public function test_can_lookup_google_dns(): void
    {
        $result = IP::lookup('8.8.8.8');

        $this->assertInstanceOf(IPResult::class, $result);
        $this->assertEquals('8.8.8.8', $result->ip);
        $this->assertNotNull($result->country);
        $this->assertEquals('United States', $result->country);
    }

    /**
     * @group integration
     */
    public function test_can_lookup_cloudflare_dns(): void
    {
        $result = IP::lookup('1.1.1.1');

        $this->assertInstanceOf(IPResult::class, $result);
        $this->assertEquals('1.1.1.1', $result->ip);
        $this->assertNotNull($result->country);
    }

    /**
     * @group integration
     */
    public function test_can_lookup_ipv6_address(): void
    {
        // Google's IPv6 DNS
        $result = IP::lookup('2001:4860:4860::8888');

        $this->assertInstanceOf(IPResult::class, $result);
        $this->assertNotNull($result->country);
    }

    public function test_facade_is_registered(): void
    {
        $this->assertTrue(class_exists(\GeoIPRadar\LaravelIP\Facades\IP::class));
    }

    public function test_can_get_manager_via_app(): void
    {
        $manager = app('ip');

        $this->assertInstanceOf(\GeoIPRadar\LaravelIP\IPManager::class, $manager);
    }

    public function test_helper_function_exists(): void
    {
        $this->assertTrue(function_exists('ip'));
        $this->assertTrue(function_exists('ip_lookup'));
        $this->assertTrue(function_exists('ip_country'));
        $this->assertTrue(function_exists('ip_city'));
    }

    /**
     * @group integration
     */
    public function test_helper_function_returns_result(): void
    {
        $result = ip('8.8.8.8');

        $this->assertInstanceOf(IPResult::class, $result);
        $this->assertEquals('8.8.8.8', $result->ip);
    }

    /**
     * @group integration
     */
    public function test_ip_country_helper_returns_country(): void
    {
        $country = ip_country('8.8.8.8');

        $this->assertEquals('United States', $country);
    }

    public function test_invalid_ip_throws_exception(): void
    {
        $this->expectException(\GeoIPRadar\LaravelIP\Exceptions\IPException::class);

        IP::lookup('not-an-ip');
    }

    public function test_configured_providers_list(): void
    {
        $providers = IP::getConfiguredProviders();

        $this->assertIsArray($providers);
        $this->assertNotEmpty($providers);
    }
}
