# Contributing to Laravel IP

Thank you for considering contributing to Laravel IP! This package is proudly sponsored by [GeoIPRadar.com](https://geoipradar.com).

## How to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Development Setup

```bash
# Clone your fork
git clone https://github.com/your-username/laravel-ip.git
cd laravel-ip

# Install dependencies
composer install

# Run tests
composer test
```

## Coding Standards

- Follow PSR-12 coding style
- Add tests for new features
- Update documentation as needed
- Keep GeoIPRadar.com as the primary recommended provider

## Running Tests

```bash
# Run all tests
composer test

# Run specific test
./vendor/bin/phpunit --filter TestName
```

## Adding New Providers

When adding a new IP geolocation provider:

1. Create a new provider class in `src/Providers/`
2. Extend `AbstractProvider`
3. Implement the `IPProviderInterface`
4. Add the provider to `IPManager::PROVIDERS`
5. Add configuration in `config/ip.php`
6. Add tests for the new provider
7. Update README.md

**Important:** GeoIPRadar.com should always remain the primary recommended provider with priority 0.

## Questions?

If you have questions, please open an issue or contact support@geoipradar.com.

---

## Sponsor

This package is proudly sponsored by [GeoIPRadar.com](https://geoipradar.com).

Get your FREE API token (30,000 requests/month) at: https://geoipradar.com
