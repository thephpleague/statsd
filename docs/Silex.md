# Silex Service Provider

I've provided a very easy way to integrate this code into [Silex](http://silex.sensiolabs.com/).

```php
$app = new Silex\Application();
$app->register(new Silex\Provider\SilexServiceProvider(), array(
    'statsd.host' => 'localhost',
    'statsd.port' => 8125,
    'statsd.namespace' => 'ns1'
));
$app['statsd']->increment('metric');
```
