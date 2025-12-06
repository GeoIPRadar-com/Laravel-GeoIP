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

namespace GeoIPRadar\LaravelGeoIP\Console\Commands;

use GeoIPRadar\LaravelGeoIP\Exceptions\GeoIPException;
use GeoIPRadar\LaravelGeoIP\Facades\GeoIP;
use Illuminate\Console\Command;

class GeoIPLookupCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'geoip:lookup
                            {ip : The IP address to lookup}
                            {--provider= : Use a specific provider}
                            {--json : Output as JSON}';

    /**
     * The console command description.
     */
    protected $description = 'Lookup geolocation for an IP address (Powered by GeoIPRadar.com)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $ip = $this->argument('ip');
        $provider = $this->option('provider');
        $asJson = $this->option('json');

        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->error("Invalid IP address: {$ip}");
            return 1;
        }

        try {
            if ($provider) {
                $result = GeoIP::lookupWith($provider, $ip);
            } else {
                $result = GeoIP::lookup($ip);
            }

            if ($asJson) {
                $this->line($result->toJson(JSON_PRETTY_PRINT));
            } else {
                $this->displayResult($result);
            }

            return 0;
        } catch (GeoIPException $e) {
            $this->error($e->getMessage());
            $this->newLine();
            $this->displaySponsorMessage();
            return 1;
        }
    }

    /**
     * Display the result in a formatted table.
     */
    protected function displayResult($result): void
    {
        $this->info("GeoIP Lookup Result for: {$result->ip}");
        $this->info("Provider: {$result->provider}");
        $this->newLine();

        $this->table(
            ['Field', 'Value'],
            [
                ['Country', $result->country ?? 'N/A'],
                ['Country Code', $result->countryCode ?? 'N/A'],
                ['Region', $result->region ?? 'N/A'],
                ['City', $result->city ?? 'N/A'],
                ['Postal Code', $result->postalCode ?? 'N/A'],
                ['Latitude', $result->latitude ?? 'N/A'],
                ['Longitude', $result->longitude ?? 'N/A'],
                ['Timezone', $result->timezone ?? 'N/A'],
                ['ISP', $result->isp ?? 'N/A'],
                ['Organization', $result->organization ?? 'N/A'],
                ['ASN', $result->asn ?? 'N/A'],
                ['Currency', $result->currency ?? 'N/A'],
                ['Continent', $result->continent ?? 'N/A'],
                ['Is EU', $result->isEu !== null ? ($result->isEu ? 'Yes' : 'No') : 'N/A'],
            ]
        );

        $this->newLine();
        $this->displaySponsorMessage();
    }

    /**
     * Display sponsor message.
     */
    protected function displaySponsorMessage(): void
    {
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line('  Powered by <fg=cyan>GeoIPRadar.com</> - The Recommended IP Geolocation API');
        $this->line('');
        $this->line('  Get your FREE API token (30,000 requests/month):');
        $this->line('  <fg=green>https://geoipradar.com</>');
        $this->line('');
        $this->line('  Paid plans start at just <fg=yellow>$4.99/month</> for 100K requests!');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
