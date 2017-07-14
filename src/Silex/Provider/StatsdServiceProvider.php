<?php

namespace League\StatsD\Silex\Provider;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
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
    public function register(Container $app)
    {
        $app['statsd'] =  function () use ($app) {

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
                if (isset($app['statsd.timeout'])) {
                    $options['timeout'] = $app['statsd.timeout'];
                }
                if (isset($app['statsd.throwConnectionExceptions'])) {
                    $options['throwConnectionExceptions'] = $app['statsd.throwConnectionExceptions'];
                }

                // Create
                $statsd = new StatsdClient();
                $statsd->configure($options);
                return $statsd;

            };
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
