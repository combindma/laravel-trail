# A simple package for tracking user activities on your Laravel website.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/combindma/laravel-trail.svg?style=flat-square)](https://packagist.org/packages/combindma/laravel-trail)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/combindma/laravel-trail/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/combindma/laravel-trail/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/combindma/laravel-trail/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/combindma/laravel-trail/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/combindma/laravel-trail.svg?style=flat-square)](https://packagist.org/packages/combindma/laravel-trail)

Laravel Trail is a powerful and easy-to-use package for tracking user activities on your Laravel website. Developed by Combind, this package automatically logs important information such as UTM tags, referrers, user actions, and more, giving you valuable insights into your users behavior and their journey through your website.

## But WHY ?

The Laravel Trail is designed to help developers effortlessly capture and store user activity data on their websites. This package enables you to track vital information such as UTM tags, referrers, and user actions, which can be used to gain valuable insights into user behavior and website performance.

By storing this information, developers can easily integrate it with their preferred tracking tools, such as Google Analytics or other analytics platforms, to further analyze user interactions and optimize their websites for improved user experience, conversion rates and improve their marketing targeting.

## Features

- **Effortless Tracking**: Automatically track user activities without any additional code
- **Capture Important Data**: Log UTM tags, referrers, and user actions for valuable insights
- **Cookies Integration**: Store tracked data in cookies
- **Customizability**: Easily extend and modify the package to track additional data or events
- **Documentation & Support**: Benefit from comprehensive documentation and support

## About Combind Agency

[Combine Agency](https://combind.ma?utm_source=github&utm_medium=banner&utm_campaign=laravel_trail) is a leading web development agency specializing in building innovative and high-performance web applications using modern technologies. Our experienced team of developers, designers, and project managers is dedicated to providing top-notch services tailored to the unique needs of our clients.

If you need assistance with your next project or would like to discuss a custom solution, please feel free to [contact us](mailto:hello@combind.ma) or visit our [website](https://combind.ma?utm_source=github&utm_medium=banner&utm_campaign=laravel_trail) for more information about our services. Let's build something amazing together!


## Installation

You can install the package via composer:

```bash
composer require combindma/laravel-trail
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-trail-config"
```

This is the contents of the published config file:

```php
return [
    /*
     * The prefix key under which data is saved to the cookies.
     */
    'prefix' => env('TAIL_COOKIE_PREFIX', config('app.name', 'laravel').'_'),
];
```

## Usage

```php
$trail = new Combindma\Trail();
echo $trail->echoPhrase('Hello, Combindma!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Combind](https://github.com/combindma)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
