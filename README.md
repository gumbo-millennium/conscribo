# Easy-to-use Conscribo API usage for membership sync

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gumbo-millennium/conscribo.svg?style=flat-square)](https://packagist.org/packages/gumbo-millennium/conscribo)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/gumbo-millennium/conscribo/run-tests?label=tests)](https://github.com/gumbo-millennium/conscribo/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/gumbo-millennium/conscribo/Check%20&%20fix%20styling?label=code%20style)](https://github.com/gumbo-millennium/conscribo/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/gumbo-millennium/conscribo.svg?style=flat-square)](https://packagist.org/packages/gumbo-millennium/conscribo)

Easily connect your Laravel website with your Conscribo administration. Import your users, divide them in groups to support different access levels, and apply committees as roles (using spatie/laravel-permission).

## Installation

The Conscribo API is available on [Packagist](https://packagist.org/packages/gumbo-millennium/conscribo).
Installation via Composer is easy:

```bash
composer require gumbo-millennium/conscribo
```

After installation, publish the config file:

```bash
php artisan vendor:publish --tag="conscribo-config"
```

Next, add these three lines to your `.env.example` file, and configure
them properly in your local `.env`:

```
CONSCRIBO_ACCOUNT=
CONSCRIBO_USERNAME=
CONSCRIBO_PASSPHRASE=
```

You can now test the connection using the `conscribo:authenticate` command.

## Configuration

To configure the API, you need to fetch the entity types from Conscribo and update your `config/conscribo.php` file.

You can get a list of all entity types using `conscribo:list-entities`.
Next, update the `resource` keys in all `objects` entries in your config to match one of the existing entity types.

Next, fetch the field names for all entity types you want to use. This is done by calling `conscribo:entity-fields <entity-type>`.

Update the `fields` in all `objects` entries in your config to match the field names you got and want to receive in your application.

Now your connection is optimized and ready to use.

## Usage

The easiest use is using the `Conscribo` facade. This facade can list and search for users (optionally with groups) and for committees.

```php
use Gumbo\Conscribo\Facades\Conscribo;

Conscribo::findUser($user->id);
Conscribo::searchUser([
    'email' => $user->email,
]);

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
