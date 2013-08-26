<?php

namespace League\StatsD\Provider\Silex;

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
        $app['statsd'] = $app->share(
            function () use ($app) {

                // Set Default host and port
                $options = array();
                if (isset($app['statsd.host'])) {
                    $options['host'] = $app['statsd.host'];
                }
                if (isset($app['statsd.port'])) {
                    $options['port'] = $app['statsd.port'];
                }
                if (isset($app['statsd.namespace'])) {
                    $options['namespace'] = $app['statsd.namespace'];
                }

                // Create
                $statsd = new StatsdClient();
                $statsd->configure($options);
                return $statsd;

            }
        );
    }


    /**
     * Boot Method
     * @param Aplication $app Silex application instance
     * @codeCoverageIgnore
     */
    public function boot(Application $app)
    {
    }
}
