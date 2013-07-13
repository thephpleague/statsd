<?php

namespace StatsD\Test;

class SilexProviderTest extends TestCase
{

    public function testProvider()
    {

        $app = new \Silex\Application();
        $app->register(new \Silex\Provider\StatsdServiceProvider(), array(
            'statsd.host' => 'localhost',
            'statsd.port' => 7890,
            'statsd.namespace' => 'test_namespace'
        ));

        // Make sure configuration is sorted
        $this->assertEquals($app['statsd']->getHost(), 'localhost');
        $this->assertEquals($app['statsd']->getPort(), 7890);
        $this->assertEquals($app['statsd']->getNamespace(), 'test_namespace');

        // Make sure messages are tracked properly
        $app['statsd']->increment('test_metric');
        $this->assertEquals($app['statsd']->getLastMessage(), 'test_namespace.test_metric:1|c');

    }

}
