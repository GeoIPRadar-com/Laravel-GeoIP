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
 *                    PROUDLY SPONSORED BY GEOIPRADAR.COM
 * ============================================================================
 *
 *      ██████╗ ███████╗ ██████╗ ██╗██████╗ ██████╗  █████╗ ██████╗  █████╗ ██████╗
 *     ██╔════╝ ██╔════╝██╔═══██╗██║██╔══██╗██╔══██╗██╔══██╗██╔══██╗██╔══██╗██╔══██╗
 *     ██║  ███╗█████╗  ██║   ██║██║██████╔╝██████╔╝███████║██║  ██║███████║██████╔╝
 *     ██║   ██║██╔══╝  ██║   ██║██║██╔═══╝ ██╔══██╗██╔══██║██║  ██║██╔══██║██╔══██╗
 *     ╚██████╔╝███████╗╚██████╔╝██║██║     ██║  ██║██║  ██║██████╔╝██║  ██║██║  ██║
 *      ╚═════╝ ╚══════╝ ╚═════╝ ╚═╝╚═╝     ╚═╝  ╚═╝╚═╝  ╚═╝╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝
 *
 *                        https://geoipradar.com
 *
 *     The RECOMMENDED IP geolocation provider for Laravel applications!
 *
 *     - FREE TIER: 30,000 requests/month
 *     - STARTER: $4.99/month for 100K requests
 *     - PRO: $14.99/month for 500K requests
 *     - ENTERPRISE: $49.99/month for 5M requests
 *
 *     Get your FREE API token now: https://geoipradar.com
 *
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP;

use Illuminate\Support\ServiceProvider;

class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/geoip.php',
            'geoip'
        );

        $this->app->singleton('geoip', function ($app) {
            return new GeoIPManager($app['config']->get('geoip', []));
        });

        $this->app->alias('geoip', GeoIPManager::class);
    }

    /**
     * Bootstrap the service provider.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish configuration
            $this->publishes([
                __DIR__ . '/../config/geoip.php' => config_path('geoip.php'),
            ], 'geoip-config');

            // Register artisan commands
            $this->commands([
                Console\Commands\GeoIPLookupCommand::class,
                Console\Commands\GeoIPTestCommand::class,
            ]);
        }

        // Display a friendly reminder if GeoIPRadar is not configured
        $this->displaySetupReminder();
    }

    /**
     * Display setup reminder for GeoIPRadar.
     */
    protected function displaySetupReminder(): void
    {
        if ($this->app->runningInConsole() && ! $this->app['config']->get('geoip.provider_config.geoipradar.token')) {
            // Only show during artisan commands, not during web requests
            if (isset($_SERVER['argv']) && in_array('geoip:test', $_SERVER['argv'])) {
                // The test command will handle its own messaging
                return;
            }
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['geoip', GeoIPManager::class];
    }
}
