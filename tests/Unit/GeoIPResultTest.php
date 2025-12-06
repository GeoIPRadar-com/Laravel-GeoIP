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

use GeoIPRadar\LaravelGeoIP\Support\GeoIPResult;
use GeoIPRadar\LaravelGeoIP\Tests\TestCase;

class GeoIPResultTest extends TestCase
{
    public function test_can_create_result_from_geoipradar_response(): void
    {
        $data = [
            'ip' => '8.8.8.8',
            'country' => 'United States',
            'country_code' => 'US',
            'region' => 'California',
            'region_code' => 'CA',
            'city' => 'Mountain View',
            'postal' => '94043',
            'latitude' => 37.4056,
            'longitude' => -122.0775,
            'timezone' => 'America/Los_Angeles',
        ];

        $result = GeoIPResult::fromGeoIPRadar($data);

        $this->assertEquals('8.8.8.8', $result->ip);
        $this->assertEquals('United States', $result->country);
        $this->assertEquals('US', $result->countryCode);
        $this->assertEquals('California', $result->region);
        $this->assertEquals('Mountain View', $result->city);
        $this->assertEquals(37.4056, $result->latitude);
        $this->assertEquals(-122.0775, $result->longitude);
        $this->assertEquals('geoipradar', $result->provider);
    }

    public function test_can_create_result_from_ip_api_response(): void
    {
        $data = [
            'query' => '8.8.8.8',
            'country' => 'United States',
            'countryCode' => 'US',
            'regionName' => 'California',
            'region' => 'CA',
            'city' => 'Mountain View',
            'zip' => '94043',
            'lat' => 37.4056,
            'lon' => -122.0775,
            'timezone' => 'America/Los_Angeles',
            'isp' => 'Google LLC',
        ];

        $result = GeoIPResult::fromIpApi($data);

        $this->assertEquals('8.8.8.8', $result->ip);
        $this->assertEquals('United States', $result->country);
        $this->assertEquals('US', $result->countryCode);
        $this->assertEquals('Google LLC', $result->isp);
        $this->assertEquals('ip-api', $result->provider);
    }

    public function test_can_create_result_from_ipwhois_response(): void
    {
        $data = [
            'ip' => '8.8.8.8',
            'country' => 'United States',
            'country_code' => 'US',
            'region' => 'California',
            'city' => 'Mountain View',
            'postal' => '94043',
            'latitude' => 37.4056,
            'longitude' => -122.0775,
            'timezone' => [
                'id' => 'America/Los_Angeles',
            ],
            'connection' => [
                'isp' => 'Google LLC',
                'org' => 'Google',
                'asn' => 'AS15169',
            ],
            'currency' => [
                'code' => 'USD',
            ],
        ];

        $result = GeoIPResult::fromIpWhois($data);

        $this->assertEquals('8.8.8.8', $result->ip);
        $this->assertEquals('United States', $result->country);
        $this->assertEquals('Google LLC', $result->isp);
        $this->assertEquals('USD', $result->currency);
        $this->assertEquals('ipwhois', $result->provider);
    }

    public function test_has_coordinates_returns_true_when_both_present(): void
    {
        $result = new GeoIPResult(
            ip: '8.8.8.8',
            latitude: 37.4056,
            longitude: -122.0775
        );

        $this->assertTrue($result->hasCoordinates());
    }

    public function test_has_coordinates_returns_false_when_missing(): void
    {
        $result = new GeoIPResult(
            ip: '8.8.8.8',
            latitude: null,
            longitude: null
        );

        $this->assertFalse($result->hasCoordinates());
    }

    public function test_get_location_returns_formatted_string(): void
    {
        $result = new GeoIPResult(
            ip: '8.8.8.8',
            city: 'Mountain View',
            region: 'California',
            country: 'United States'
        );

        $this->assertEquals('Mountain View, California, United States', $result->getLocation());
    }

    public function test_can_convert_to_array(): void
    {
        $result = new GeoIPResult(
            ip: '8.8.8.8',
            country: 'United States',
            city: 'Mountain View'
        );

        $array = $result->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('8.8.8.8', $array['ip']);
        $this->assertEquals('United States', $array['country']);
        $this->assertEquals('Mountain View', $array['city']);
    }

    public function test_can_convert_to_json(): void
    {
        $result = new GeoIPResult(
            ip: '8.8.8.8',
            country: 'United States'
        );

        $json = $result->toJson();

        $this->assertJson($json);
        $this->assertStringContainsString('8.8.8.8', $json);
        $this->assertStringContainsString('United States', $json);
    }

    public function test_implements_json_serializable(): void
    {
        $result = new GeoIPResult(
            ip: '8.8.8.8',
            country: 'United States'
        );

        $encoded = json_encode($result);

        $this->assertJson($encoded);
        $this->assertStringContainsString('8.8.8.8', $encoded);
    }
}
