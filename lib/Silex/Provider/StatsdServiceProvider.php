<?php

namespace Silex\Provider;
use Silex\Application;
use Silex\ServiceProviderInterface;
use StatsD\Client as StatsdClient;

class StatsdServiceProvider implements ServiceProviderInterface
{

    /**
     * Register Service Provider
     * @param  Application $app Silex application instance
     * @return StatsD\Client StatsD Client instance
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

                // Create
                $statsd = new StatsdClient();
                $statsd->configure($options);
                return $statsd;

            }
        );
    }

    public function boot(Application $app)
    {
    }

}
