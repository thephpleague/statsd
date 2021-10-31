<?php

namespace League\StatsD\Test;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use League\StatsD\Laravel\Provider\StatsdServiceProvider;

class LaravelTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (!class_exists(Application::class)) {
            $this->markTestSkipped("Can't test Laravel integration without Illuminate");
        }
    }

    public function setupApplication($config = true)
    {
        $app = new Application();
        $app->instance('path', 'foobar');
        $app->instance('files', new Filesystem());
        $app->instance('config', new Repository($app->getConfigLoader(), 'foobar'));

        if ($config) {
            $app['config']->set('statsd', [
                'host' => "localhost",
                'port' => 7890,
                'namespace' => 'test_namespace'
            ]);
        }

        return $app;
    }

    public function setupServiceProvider(Application $app)
    {
        $provider = new StatsdServiceProvider($app);
        $app->register($provider);

        $provider->boot();

        return $provider;
    }
}
