<?php

namespace League\StatsD\Test;
use Silex\Application;
use League\StatsD\Provider\Silex\StatsdServiceProvider;
use League\StatsD\Client;

class SilexProviderTest extends TestCase
{

    public function testProvider()
    {

        $app = new Application();
        $app->register(new StatsdServiceProvider(), array(
            'statsd.host' => 'localhost',
            'statsd.port' => 7890,
            'statsd.namespace' => 'test_namespace'
        ));

        // Make sure is the right instance type
        $this->assertTrue($app['statsd'] instanceof Client);

        // Make sure configuration is sorted
        $this->assertEquals('localhost', $app['statsd']->getHost());
        $this->assertEquals(7890, $app['statsd']->getPort());
        $this->assertEquals('test_namespace', $app['statsd']->getNamespace());

        // Make sure messages are tracked properly
        $app['statsd']->increment('test_metric');
        $this->assertEquals('test_namespace.test_metric:1|c', $app['statsd']->getLastMessage());

    }


    public function testProviderDefaults()
    {

        $app = new Application();
        $app->register(new StatsdServiceProvider());

        // Make sure configuration is sorted
        $this->assertEquals('127.0.0.1', $app['statsd']->getHost());
        $this->assertEquals(8125, $app['statsd']->getPort(), 8015);
        $this->assertEquals('', $app['statsd']->getNamespace());

        // Make sure messages are tracked properly
        $app['statsd']->increment('test_metric', 2);
        $this->assertEquals('test_metric:2|c', $app['statsd']->getLastMessage());

    }

}
