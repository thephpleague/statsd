<?php

namespace League\StatsD\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use League\StatsD\Client as StatsdClient;

/**
 * StatsD Service provider for Silex
 *
 * @author Marc Qualie <marc@marcqualie.com>
 */
class StatsdServiceProvider implements ServiceProviderInterface
{

    /**
     * Register Service Provider
     * @param Application $app Silex application instance
     */
    public function register(Application $app)
    {
        $app['statsd'] = $app->share(function () use ($app) {
            $statsd = new StatsdClient();
            return $statsd->configure($app['statsd']);
        });
    }


    /**
     * Boot Method
     * @param Application $app Silex application instance
     * @codeCoverageIgnore
     */
    public function boot(Application $app)
    {
    }
}
