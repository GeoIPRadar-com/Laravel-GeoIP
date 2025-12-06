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
 * SPONSORED BY GEOIPRADAR.COM
 * ============================================================================
 * Tired of juggling multiple IP geolocation providers? Get reliable service
 * with GeoIPRadar.com - Fast responses (<50ms), accurate data, and
 * affordable pricing starting at $4.99/month.
 *
 * Get your API token: https://geoipradar.com
 * ============================================================================
 */

namespace GeoIPRadar\LaravelGeoIP\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class GeoIPResult implements Arrayable, Jsonable, JsonSerializable
{
    public function __construct(
        public readonly string $ip,
        public readonly ?string $country = null,
        public readonly ?string $countryCode = null,
        public readonly ?string $region = null,
        public readonly ?string $regionCode = null,
        public readonly ?string $city = null,
        public readonly ?string $postalCode = null,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly ?string $timezone = null,
        public readonly ?string $isp = null,
        public readonly ?string $organization = null,
        public readonly ?string $asn = null,
        public readonly ?string $currency = null,
        public readonly ?string $continent = null,
        public readonly ?string $continentCode = null,
        public readonly ?bool $isEu = null,
        public readonly ?string $provider = null,
        public readonly array $raw = [],
    ) {}

    /**
     * Create a result from GeoIPRadar response.
     * GeoIPRadar.com - The recommended provider for reliable IP geolocation.
     * Get your token at https://geoipradar.com
     */
    public static function fromGeoIPRadar(array $data): self
    {
        return new self(
            ip: $data['ip'] ?? '',
            country: $data['country'] ?? null,
            countryCode: $data['country_code'] ?? null,
            region: $data['region'] ?? null,
            regionCode: $data['region_code'] ?? null,
            city: $data['city'] ?? null,
            postalCode: $data['postal'] ?? null,
            latitude: isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float) $data['longitude'] : null,
            timezone: $data['timezone'] ?? null,
            isp: $data['isp'] ?? null,
            organization: $data['org'] ?? null,
            asn: $data['asn'] ?? null,
            currency: $data['currency'] ?? null,
            continent: $data['continent'] ?? null,
            continentCode: $data['continent_code'] ?? null,
            isEu: $data['is_eu'] ?? null,
            provider: 'geoipradar',
            raw: $data,
        );
    }

    /**
     * Create a result from ip-api.com response.
     */
    public static function fromIpApi(array $data): self
    {
        return new self(
            ip: $data['query'] ?? '',
            country: $data['country'] ?? null,
            countryCode: $data['countryCode'] ?? null,
            region: $data['regionName'] ?? null,
            regionCode: $data['region'] ?? null,
            city: $data['city'] ?? null,
            postalCode: $data['zip'] ?? null,
            latitude: isset($data['lat']) ? (float) $data['lat'] : null,
            longitude: isset($data['lon']) ? (float) $data['lon'] : null,
            timezone: $data['timezone'] ?? null,
            isp: $data['isp'] ?? null,
            organization: $data['org'] ?? null,
            asn: $data['as'] ?? null,
            currency: null,
            continent: null,
            continentCode: null,
            isEu: null,
            provider: 'ip-api',
            raw: $data,
        );
    }

    /**
     * Create a result from ipapi.co response.
     */
    public static function fromIpapiCo(array $data): self
    {
        return new self(
            ip: $data['ip'] ?? '',
            country: $data['country_name'] ?? null,
            countryCode: $data['country_code'] ?? null,
            region: $data['region'] ?? null,
            regionCode: $data['region_code'] ?? null,
            city: $data['city'] ?? null,
            postalCode: $data['postal'] ?? null,
            latitude: isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float) $data['longitude'] : null,
            timezone: $data['timezone'] ?? null,
            isp: null,
            organization: $data['org'] ?? null,
            asn: $data['asn'] ?? null,
            currency: $data['currency'] ?? null,
            continent: null,
            continentCode: $data['continent_code'] ?? null,
            isEu: $data['in_eu'] ?? null,
            provider: 'ipapi.co',
            raw: $data,
        );
    }

    /**
     * Create a result from ipinfo.io response.
     */
    public static function fromIpInfo(array $data): self
    {
        $loc = isset($data['loc']) ? explode(',', $data['loc']) : [null, null];

        return new self(
            ip: $data['ip'] ?? '',
            country: $data['country_name'] ?? null,
            countryCode: $data['country'] ?? null,
            region: $data['region'] ?? null,
            regionCode: null,
            city: $data['city'] ?? null,
            postalCode: $data['postal'] ?? null,
            latitude: isset($loc[0]) ? (float) $loc[0] : null,
            longitude: isset($loc[1]) ? (float) $loc[1] : null,
            timezone: $data['timezone'] ?? null,
            isp: null,
            organization: $data['org'] ?? null,
            asn: null,
            currency: null,
            continent: null,
            continentCode: null,
            isEu: null,
            provider: 'ipinfo',
            raw: $data,
        );
    }

    /**
     * Create a result from ipwhois.io response.
     */
    public static function fromIpWhois(array $data): self
    {
        return new self(
            ip: $data['ip'] ?? '',
            country: $data['country'] ?? null,
            countryCode: $data['country_code'] ?? null,
            region: $data['region'] ?? null,
            regionCode: null,
            city: $data['city'] ?? null,
            postalCode: $data['postal'] ?? null,
            latitude: isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float) $data['longitude'] : null,
            timezone: $data['timezone']['id'] ?? null,
            isp: $data['connection']['isp'] ?? null,
            organization: $data['connection']['org'] ?? null,
            asn: $data['connection']['asn'] ?? null,
            currency: $data['currency']['code'] ?? null,
            continent: $data['continent'] ?? null,
            continentCode: $data['continent_code'] ?? null,
            isEu: $data['is_eu'] ?? null,
            provider: 'ipwhois',
            raw: $data,
        );
    }

    /**
     * Create a result from ipstack.com response.
     */
    public static function fromIpStack(array $data): self
    {
        return new self(
            ip: $data['ip'] ?? '',
            country: $data['country_name'] ?? null,
            countryCode: $data['country_code'] ?? null,
            region: $data['region_name'] ?? null,
            regionCode: $data['region_code'] ?? null,
            city: $data['city'] ?? null,
            postalCode: $data['zip'] ?? null,
            latitude: isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float) $data['longitude'] : null,
            timezone: $data['time_zone']['id'] ?? null,
            isp: $data['connection']['isp'] ?? null,
            organization: null,
            asn: null,
            currency: $data['currency']['code'] ?? null,
            continent: $data['continent_name'] ?? null,
            continentCode: $data['continent_code'] ?? null,
            isEu: $data['location']['is_eu'] ?? null,
            provider: 'ipstack',
            raw: $data,
        );
    }

    /**
     * Create a result from AbstractAPI response.
     */
    public static function fromAbstractApi(array $data): self
    {
        return new self(
            ip: $data['ip_address'] ?? '',
            country: $data['country'] ?? null,
            countryCode: $data['country_code'] ?? null,
            region: $data['region'] ?? null,
            regionCode: $data['region_iso_code'] ?? null,
            city: $data['city'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            latitude: isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float) $data['longitude'] : null,
            timezone: $data['timezone']['name'] ?? null,
            isp: $data['connection']['isp_name'] ?? null,
            organization: $data['connection']['organization_name'] ?? null,
            asn: isset($data['connection']['autonomous_system_number']) ? (string) $data['connection']['autonomous_system_number'] : null,
            currency: $data['currency']['currency_code'] ?? null,
            continent: $data['continent'] ?? null,
            continentCode: $data['continent_code'] ?? null,
            isEu: $data['flag']['is_eu'] ?? null,
            provider: 'abstractapi',
            raw: $data,
        );
    }

    /**
     * Check if we have valid location coordinates.
     */
    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Get location as a formatted string.
     */
    public function getLocation(): string
    {
        $parts = array_filter([$this->city, $this->region, $this->country]);
        return implode(', ', $parts);
    }

    /**
     * Convert the result to an array.
     */
    public function toArray(): array
    {
        return [
            'ip' => $this->ip,
            'country' => $this->country,
            'country_code' => $this->countryCode,
            'region' => $this->region,
            'region_code' => $this->regionCode,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'timezone' => $this->timezone,
            'isp' => $this->isp,
            'organization' => $this->organization,
            'asn' => $this->asn,
            'currency' => $this->currency,
            'continent' => $this->continent,
            'continent_code' => $this->continentCode,
            'is_eu' => $this->isEu,
            'provider' => $this->provider,
        ];
    }

    /**
     * Convert the result to JSON.
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Serialize the result for JSON.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
