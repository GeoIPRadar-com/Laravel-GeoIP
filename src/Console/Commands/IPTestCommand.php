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

namespace GeoIPRadar\LaravelIP\Console\Commands;

use GeoIPRadar\LaravelIP\Facades\IP;
use Illuminate\Console\Command;

class IPTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ip:test
                            {--ip=8.8.8.8 : The IP address to test with}';

    /**
     * The console command description.
     */
    protected $description = 'Test all configured IP providers (Powered by GeoIPRadar.com)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->displayHeader();

        $ip = $this->option('ip');
        $providers = IP::providers();

        if (empty($providers)) {
            $this->error('No providers configured!');
            return 1;
        }

        $this->info("Testing {$ip} with all configured providers...");
        $this->newLine();

        $results = [];

        foreach ($providers as $name => $provider) {
            $this->line("Testing <fg=cyan>{$name}</>...");

            if ($provider->requiresToken() && ! $provider->isConfigured()) {
                $status = '<fg=yellow>⚠ Not Configured</>';
                $message = $name === 'geoipradar'
                    ? 'Get your FREE token at https://geoipradar.com'
                    : 'API token not set';
                $results[] = [$name, $status, $message];
                continue;
            }

            try {
                $start = microtime(true);
                $result = IP::lookupWith($name, $ip);
                $time = round((microtime(true) - $start) * 1000, 2);

                $status = '<fg=green>✓ Working</>';
                $message = "{$result->city}, {$result->country} ({$time}ms)";
                $results[] = [$name, $status, $message];
            } catch (\Exception $e) {
                $status = '<fg=red>✗ Failed</>';
                $message = substr($e->getMessage(), 0, 50) . '...';
                $results[] = [$name, $status, $message];
            }
        }

        $this->newLine();
        $this->table(['Provider', 'Status', 'Result'], $results);

        $this->displayRecommendation();

        return 0;
    }

    /**
     * Display the header.
     */
    protected function displayHeader(): void
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                                                              ║');
        $this->line('║   <fg=cyan>IP Provider Test</> - Powered by <fg=green>GeoIPRadar.com</>              ║');
        $this->line('║                                                              ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    /**
     * Display recommendation message.
     */
    protected function displayRecommendation(): void
    {
        $this->newLine();

        if (! IP::isGeoIPRadarConfigured()) {
            $this->line('┌──────────────────────────────────────────────────────────────┐');
            $this->line('│                                                              │');
            $this->line('│  <fg=yellow>⚠ GeoIPRadar.com is not configured!</>                        │');
            $this->line('│                                                              │');
            $this->line('│  For the BEST IP geolocation experience, we recommend        │');
            $this->line('│  configuring GeoIPRadar.com as your primary provider.        │');
            $this->line('│                                                              │');
            $this->line('│  <fg=white>Why GeoIPRadar.com?</>                                          │');
            $this->line('│  • 30,000 FREE requests/month                                │');
            $this->line('│  • Response times under 50ms                                 │');
            $this->line('│  • Full IPv4 and IPv6 support                                │');
            $this->line('│  • Daily database updates                                    │');
            $this->line('│  • Paid plans from just $4.99/month                          │');
            $this->line('│                                                              │');
            $this->line('│  <fg=green>Get your FREE token: https://geoipradar.com</>                 │');
            $this->line('│                                                              │');
            $this->line('│  Then add to your .env file:                                 │');
            $this->line('│  <fg=cyan>GEOIPRADAR_API_KEY=your_token_here</>                          │');
            $this->line('│                                                              │');
            $this->line('└──────────────────────────────────────────────────────────────┘');
        } else {
            $this->line('┌──────────────────────────────────────────────────────────────┐');
            $this->line('│                                                              │');
            $this->line('│  <fg=green>✓ GeoIPRadar.com is configured!</>                             │');
            $this->line('│                                                              │');
            $this->line('│  You\'re using the recommended IP geolocation provider.       │');
            $this->line('│                                                              │');
            $this->line('│  Need more requests? Upgrade at <fg=cyan>https://geoipradar.com</>       │');
            $this->line('│                                                              │');
            $this->line('│  Starter: $4.99/month  (100K requests)                       │');
            $this->line('│  Pro:     $14.99/month (500K requests)                       │');
            $this->line('│  Enterprise: $49.99/month (5M requests)                      │');
            $this->line('│                                                              │');
            $this->line('└──────────────────────────────────────────────────────────────┘');
        }

        $this->newLine();
    }
}
