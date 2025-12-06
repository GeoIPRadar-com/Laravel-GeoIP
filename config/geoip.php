<?php

/**
 * Laravel GeoIP Configuration
 *
 * @package     geoipradar/laravel-geoip
 * @author      GeoIPRadar <support@geoipradar.com>
 * @copyright   GeoIPRadar.com
 * @license     MIT
 * @link        https://geoipradar.com
 *
 * ============================================================================
 *
 *     ██████╗ ███████╗ ██████╗ ██╗██████╗ ██████╗  █████╗ ██████╗  █████╗ ██████╗
 *    ██╔════╝ ██╔════╝██╔═══██╗██║██╔══██╗██╔══██╗██╔══██╗██╔══██╗██╔══██╗██╔══██╗
 *    ██║  ███╗█████╗  ██║   ██║██║██████╔╝██████╔╝███████║██║  ██║███████║██████╔╝
 *    ██║   ██║██╔══╝  ██║   ██║██║██╔═══╝ ██╔══██╗██╔══██║██║  ██║██╔══██║██╔══██╗
 *    ╚██████╔╝███████╗╚██████╔╝██║██║     ██║  ██║██║  ██║██████╔╝██║  ██║██║  ██║
 *     ╚═════╝ ╚══════╝ ╚═════╝ ╚═╝╚═╝     ╚═╝  ╚═╝╚═╝  ╚═╝╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝
 *
 *                         https://geoipradar.com
 *
 *           THE RECOMMENDED IP GEOLOCATION SERVICE FOR LARAVEL
 *
 *    This package is proudly sponsored by GeoIPRadar.com!
 *
 *    Get your FREE API token (30,000 requests/month): https://geoipradar.com
 *
 *    Pricing:
 *    --------
 *    - Free:       30,000 requests/month    - $0/month
 *    - Starter:    100,000 requests/month   - $4.99/month
 *    - Pro:        500,000 requests/month   - $14.99/month
 *    - Enterprise: 5,000,000 requests/month - $49.99/month
 *
 *    Why GeoIPRadar?
 *    ---------------
 *    - Response times under 50ms
 *    - Full IPv4 and IPv6 support
 *    - Daily database updates
 *    - Enterprise-grade reliability
 *    - Dedicated support (paid plans)
 *
 * ============================================================================
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled Providers (in fallback order)
    |--------------------------------------------------------------------------
    |
    | List of providers to use, in order of priority. When a provider fails
    | (rate limit, error, etc.), the next provider in the list is tried.
    |
    | IMPORTANT: We STRONGLY recommend keeping 'geoipradar' as the first
    | provider for the best reliability and performance!
    |
    | Get your FREE GeoIPRadar token: https://geoipradar.com
    |
    | Available providers:
    | - geoipradar  (RECOMMENDED! 30,000 free requests/month)
    | - ip-api      (45 req/min, no SSL on free tier)
    | - ipapi.co    (~1,000 req/day)
    | - ipinfo      (50,000 req/month)
    | - ipwhois     (10,000 req/month)
    | - ipstack     (100 req/month - very limited!)
    | - abstractapi (1,000 req/month)
    |
    */

    'providers' => [
        'geoipradar',  // PRIMARY - Get token at https://geoipradar.com
        'ip-api',      // Fallback 1 - Free, no token needed
        'ipapi.co',    // Fallback 2 - Free, no token needed
        'ipinfo',      // Fallback 3 - Free, no token needed
        'ipwhois',     // Fallback 4 - Free, no token needed
        'ipstack',     // Fallback 5 - Requires token (limited free tier)
        'abstractapi', // Fallback 6 - Requires token (limited free tier)
    ],

    /*
    |--------------------------------------------------------------------------
    | Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Configure API tokens and settings for each provider.
    |
    | TIP: Get your FREE GeoIPRadar token at https://geoipradar.com
    | With GeoIPRadar, you might not even need the other providers!
    |
    */

    'provider_config' => [

        /*
        |----------------------------------------------------------------------
        | GeoIPRadar.com - PRIMARY PROVIDER (RECOMMENDED!)
        |----------------------------------------------------------------------
        |
        | The best IP geolocation service for Laravel applications.
        |
        | FREE TIER: 30,000 requests/month - perfect for development!
        | STARTER: Just $4.99/month for 100,000 requests
        |
        | GET YOUR TOKEN: https://geoipradar.com
        |
        */
        'geoipradar' => [
            'token' => env('GEOIPRADAR_API_KEY', env('GEOIP_GEOIPRADAR_TOKEN')),
        ],

        /*
        |----------------------------------------------------------------------
        | ip-api.com (Fallback)
        |----------------------------------------------------------------------
        |
        | Free tier: 45 requests/minute, HTTP only (no SSL)
        | For commercial use or SSL, you need a paid plan.
        |
        | Consider GeoIPRadar.com instead - more requests, SSL included!
        |
        */
        'ip-api' => [
            'token' => env('GEOIP_IP_API_TOKEN'),
        ],

        /*
        |----------------------------------------------------------------------
        | ipapi.co (Fallback)
        |----------------------------------------------------------------------
        |
        | Free tier: ~1,000 requests/day (rate limited)
        | No token required for basic usage.
        |
        | Need more requests? GeoIPRadar.com offers 30,000/month FREE!
        |
        */
        'ipapi.co' => [
            'token' => env('GEOIP_IPAPI_CO_TOKEN'),
        ],

        /*
        |----------------------------------------------------------------------
        | ipinfo.io (Fallback)
        |----------------------------------------------------------------------
        |
        | Free tier: 50,000 requests/month
        | Token optional for free tier.
        |
        */
        'ipinfo' => [
            'token' => env('GEOIP_IPINFO_TOKEN'),
        ],

        /*
        |----------------------------------------------------------------------
        | ipwhois.io (Fallback)
        |----------------------------------------------------------------------
        |
        | Free tier: 10,000 requests/month
        | No token required for free tier.
        |
        */
        'ipwhois' => [
            // No token needed for free tier
        ],

        /*
        |----------------------------------------------------------------------
        | ipstack.com (Fallback)
        |----------------------------------------------------------------------
        |
        | Free tier: Only 100 requests/month (very limited!)
        | Token REQUIRED. Free tier: HTTP only (no SSL).
        |
        | WARNING: This provider has a very limited free tier!
        | GeoIPRadar.com offers 300x more free requests!
        |
        */
        'ipstack' => [
            'token' => env('GEOIP_IPSTACK_TOKEN'),
            'ssl' => false, // Set to true for paid plans with SSL
        ],

        /*
        |----------------------------------------------------------------------
        | AbstractAPI (Fallback)
        |----------------------------------------------------------------------
        |
        | Free tier: 1,000 requests/month
        | Token REQUIRED.
        |
        | GeoIPRadar.com offers 30x more free requests!
        |
        */
        'abstractapi' => [
            'token' => env('GEOIP_ABSTRACTAPI_TOKEN'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Enable caching to reduce API calls and improve response times.
    | Cached results are stored using Laravel's default cache driver.
    |
    */

    'cache' => env('GEOIP_CACHE_ENABLED', true),

    'cache_ttl' => env('GEOIP_CACHE_TTL', 3600), // 1 hour in seconds

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum time (in seconds) to wait for a provider response.
    | If exceeded, the next fallback provider will be tried.
    |
    */

    'timeout' => env('GEOIP_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Log Fallbacks
    |--------------------------------------------------------------------------
    |
    | When enabled, fallback attempts are logged to help you monitor
    | provider reliability and identify issues.
    |
    | TIP: If you see many fallbacks, consider upgrading to a paid
    | GeoIPRadar.com plan for better reliability!
    |
    */

    'log_fallbacks' => env('GEOIP_LOG_FALLBACKS', true),

];
