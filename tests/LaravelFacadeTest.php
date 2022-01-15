<?php

namespace League\StatsD\Test;

use League\StatsD\Laravel\Facade\StatsdFacade as Statsd;
use League\StatsD\Client;

class LaravelFacadeTest extends LaravelTestCase
{
    public function testFacadeCanBeResolvedToServiceInstance()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);

        // Mount facades
        Statsd::setFacadeApplication($app);

        // Get an instance of a client (S3) via its facade
        $statsd = Statsd::instance();
        $this->assertInstanceOf(Client::class, $statsd);
    }
}
