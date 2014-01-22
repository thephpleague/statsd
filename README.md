# StatsD PHP Library

[![Build Status](https://travis-ci.org/thephpleague/statsd.png?branch=master)](https://travis-ci.org/thephpleague/statsd)
[![Total Downloads](https://poser.pugx.org/league/statsd/downloads.png)](https://packagist.org/packages/league/statsd)
[![Latest Stable Version](https://poser.pugx.org/league/statsd/v/stable.png)](https://packagist.org/packages/league/statsd)
[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/thephpleague/statsd/trend.png)](https://bitdeli.com/free "Bitdeli Badge")


A library for working with StatsD in PHP.


## Install

Via Composer

```json
{
    "require": {
        "league/statsd": "~1.0"
    }
}
```

To use the Statsd Service Provider, you must register the provider when bootstrapping your Laravel application.

Find the `providers` key in your `app/config/app.php` and register the Statsd Service Provider.

```php
    'providers' => array(
        // ...
        'League\Statsd\Laravel\Provider\StatsdServiceProvider',
    )
```

Find the `aliases` key in your `app/config/app.php` and add the Statsd Facade Alias.

```php
    'aliases' => array(
        // ...
        'Statsd' => 'League\Statsd\Laravel\Facade\StatsdFacade',
    )
```

## Usage

### Configuring

```php
$statsd = new League\StatsD\Client();
$statsd->configure(array(
    'host' => '127.0.0.1',
    'port' => 8125,
    'namespace' => 'example'
));
```

OR

```php
$statsd1 = StatsD\Client::instance('server1')->configure(array(...));
$statsd2 = StatsD\Client::instance('server2')->configure(array(...));
```

### Counters

```php
$statsd->increment('web.pageview');
$statsd->decrement('storage.remaining');
$statsd->increment(array(
    'first.metric',
    'second.metric'
), 2);
$statsd->increment('web.clicks', 1, 0.5);
```

### Gauges

```php
$statsd->gauge('api.logged_in_users', 123456);
```

### Timers

```php
$statsd->timing('api.response_time', 256);
```

## Timing Blocks

```php
$statsd->time('api.dbcall', function () {
    // this code execution will be timed and recorded in ms
});
```

## Testing

    phpunit


## Contributing

Please see [CONTRIBUTING](https://github.com/thephpleague/statsd/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Marc Qualie](https://github.com/marcqualie)
- [All Contributors](https://github.com/thephpleague/statsd/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/thephpleague/statsd/blob/master/LICENSE) for more information.
