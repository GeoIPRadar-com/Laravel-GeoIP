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
 * Get your FREE API token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Tests;

use GeoIPRadar\LaravelGeoIP\GeoIPServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            GeoIPServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'GeoIP' => \GeoIPRadar\LaravelGeoIP\Facades\GeoIP::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('geoip.providers', [
            'ip-api',
            'ipapi.co',
        ]);

        $app['config']->set('geoip.cache', false);
        $app['config']->set('geoip.log_fallbacks', false);
    }
}
