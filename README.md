# Easy-to-use Conscribo API usage for membership sync

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gumbo-millennium/conscribo-api.svg?style=flat-square)](https://packagist.org/packages/gumbo-millennium/conscribo-api)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/gumbo-millennium/conscribo-api/run-tests?label=tests)](https://github.com/gumbo-millennium/conscribo-api/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/gumbo-millennium/conscribo-api/Check%20&%20fix%20styling?label=code%20style)](https://github.com/gumbo-millennium/conscribo-api/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/gumbo-millennium/conscribo-api.svg?style=flat-square)](https://packagist.org/packages/gumbo-millennium/conscribo-api)

Easily connect your Laravel website with your Conscribo administration. Import your users, divide them in groups to support different access levels, and apply committees as roles (using spatie/laravel-permission).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/conscribo-api.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/conscribo-api)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require gumbo-millennium/conscribo-api
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="conscribo-api-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="conscribo-api-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$conscriboApi = new Gumbo\ConscriboApi();

$users = $conscriboApi->getUsers();
$groups = $conscriboApi->getGroups();
$roles = $conscriboApi->getRoles();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/roelofr/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Roelof Roos](https://github.com/roelofr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
