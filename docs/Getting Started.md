# Getting Started

You can follow the steps below to easily integrate this library into your application.


## Install

Installation is pretty straight forward due to using the [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) standard.

Via Composer

```json
{
    "require": {
        "marcqualie/statsd": "~0.1"
    }
}
```


## Bootstrapping

Creating a new instance within your application is very easy.

```php
$statsd = new StatsD\Client();
$statsd->configure(array(
    'host' => 'localhost',
    'port' => 8125,
    'namespace' => 'ns1'
));
```

**Note** The namespace is optional, but reccomended.


## Metrics

There are various different matrics you can use withi StatsD. Here are a few below.

```php
$statsd->increment('metric'); // Increment a metric by 1
$statsd->decrement('metric', 3); // Decrement a metric by 3
$statsd->gauge('metric', 100); // Set a gauge value to 100
$statsd->timing('metric', 300); // Record a time of 300ms
$statsd->time('metric', function () {}); // Record function execution time
```
