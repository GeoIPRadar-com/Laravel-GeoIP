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
 * Get your FREE API token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelIP\Tests;

use GeoIPRadar\LaravelIP\IPServiceProvider;
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
            IPServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'IP' => \GeoIPRadar\LaravelIP\Facades\IP::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('ip.providers', [
            'ip-api',
            'ipapi.co',
        ]);

        $app['config']->set('ip.cache', false);
        $app['config']->set('ip.log_fallbacks', false);
    }
}
