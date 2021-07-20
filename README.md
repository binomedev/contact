# Contact

[![Latest Version on Packagist](https://img.shields.io/packagist/v/binomedev/contact.svg?style=flat-square)](https://packagist.org/packages/binomedev/contact)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/binomedev/contact/run-tests?label=tests)](https://github.com/binomedev/contact/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/binomedev/contact.svg?style=flat-square)](https://packagist.org/packages/binomedev/contact)


A package to manage contact information such as: email, phone, socials and forms.


## Installation

You can install the package via composer:

```bash
composer require binomedev/contact
```

Install nova-settings
```bash
composer require optimistdigital/nova-settings
```

Add the following line to the boot method within the NovaServiceProvider.php in order to be able to modify contact data withing Nova.

```php

// NovaServiceProvider.php 
public function boot()
{
    // ...
    if(!$this->app->runningInConsole()) 
    {
        \Binomedev\Contact\ContactSettings::boot();
        \Binomedev\Settings\MailSettings::boot();
    }

}
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Binomedev\Contact\ContactServiceProvider" --tag="contact-migrations"
php artisan migrate
```



You can publish the config file with:
```bash
php artisan vendor:publish --provider="Binomedev\Contact\ContactServiceProvider" --tag="contact-config"
```

This is the contents of the published config file:

```php
return [
    'default_to' => '',
    'save_messages' => true,
    'save_subscribers' => true,
    
    'enable_gmail_api' => env('ENABLE_GMAIL_API', false),
    'enable_legacy_support' => env('CONTACT_ENABLE_LEGACY_SUPPORT', false),
];
```



## Usage

```php
use Binomedev\Contact\ContactFacade;
// Subscribe a new email
$subscriber = ContactFacade::subscribe(
    $data['email'],
    $data['name'],
    $data['phone'],
);
```

## Using Gmail API

First you need to enable the Gmail API features.

```dotenv
ENABLE_GMAIL_API=true
```

Further, add the credentials in the `.env` file.

```dotenv
GOOGLE_PROJECT_ID=
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

Or use a JSON credentials file by adding it to the path: `storage/gmail/tokens/gmail-json.json`

Adding more options
```dotenv
GOOGLE_ALLOW_MULTIPLE_CREDENTIALS=true
GOOGLE_ALLOW_JSON_ENCRYPT=true
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Codrin Axinte](https://github.com/codrin-axinte)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
