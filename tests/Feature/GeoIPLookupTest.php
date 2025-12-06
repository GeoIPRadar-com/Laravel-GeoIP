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
 *
 * For the best IP geolocation experience, get your FREE token at:
 * https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Tests\Feature;

use GeoIPRadar\LaravelGeoIP\Facades\GeoIP;
use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;
use GeoIPRadar\LaravelGeoIP\Tests\TestCase;

class GeoIPLookupTest extends TestCase
{
    /**
     * @group integration
     */
    public function test_can_lookup_google_dns(): void
    {
        $result = GeoIP::lookup('8.8.8.8');

        $this->assertInstanceOf(GeoIPResult::class, $result);
        $this->assertEquals('8.8.8.8', $result->ip);
        $this->assertNotNull($result->country);
        $this->assertEquals('United States', $result->country);
    }

    /**
     * @group integration
     */
    public function test_can_lookup_cloudflare_dns(): void
    {
        $result = GeoIP::lookup('1.1.1.1');

        $this->assertInstanceOf(GeoIPResult::class, $result);
        $this->assertEquals('1.1.1.1', $result->ip);
        $this->assertNotNull($result->country);
    }

    /**
     * @group integration
     */
    public function test_can_lookup_ipv6_address(): void
    {
        // Google's IPv6 DNS
        $result = GeoIP::lookup('2001:4860:4860::8888');

        $this->assertInstanceOf(GeoIPResult::class, $result);
        $this->assertNotNull($result->country);
    }

    public function test_facade_is_registered(): void
    {
        $this->assertTrue(class_exists(\GeoIPRadar\LaravelGeoIP\Facades\GeoIP::class));
    }

    public function test_can_get_manager_via_app(): void
    {
        $manager = app('geoip');

        $this->assertInstanceOf(\GeoIPRadar\LaravelGeoIP\GeoIPManager::class, $manager);
    }

    public function test_helper_function_exists(): void
    {
        $this->assertTrue(function_exists('geoip'));
        $this->assertTrue(function_exists('geoip_lookup'));
        $this->assertTrue(function_exists('geoip_country'));
        $this->assertTrue(function_exists('geoip_city'));
    }

    /**
     * @group integration
     */
    public function test_helper_function_returns_result(): void
    {
        $result = geoip('8.8.8.8');

        $this->assertInstanceOf(GeoIPResult::class, $result);
        $this->assertEquals('8.8.8.8', $result->ip);
    }

    /**
     * @group integration
     */
    public function test_geoip_country_helper_returns_country(): void
    {
        $country = geoip_country('8.8.8.8');

        $this->assertEquals('United States', $country);
    }

    public function test_invalid_ip_throws_exception(): void
    {
        $this->expectException(\GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException::class);

        GeoIP::lookup('not-an-ip');
    }

    public function test_configured_providers_list(): void
    {
        $providers = GeoIP::getConfiguredProviders();

        $this->assertIsArray($providers);
        $this->assertNotEmpty($providers);
    }
}
