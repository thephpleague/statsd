<?php

namespace League\StatsD\Laravel\Provider;

use Illuminate\Support\ServiceProvider;
use League\StatsD\Client as Statsd;

/**
 * StatsD Service provider for Laravel
 *
 * @author Aran Wilkinson <aran@aranw.net>
 */
class StatsdServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('league/statsd', 'statsd');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerStatsD();
    }

    /**
     * Register Statsd
     *
     * @return void
     */
    protected function registerStatsD()
    {
        $this->app['statsd'] = $this->app->share(
            function ($app) {
                // Set Default host and port
                $options = array();
                if (isset($app['config']['statsd.host'])) {
                    $options['host'] = $app['config']['statsd.host'];
                }
                if (isset($app['config']['statsd.port'])) {
                    $options['port'] = $app['config']['statsd.port'];
                }
                if (isset($app['config']['statsd.namespace'])) {
                    $options['namespace'] = $app['config']['statsd.namespace'];
                }
                if (isset($app['config']['statsd.throwConnectionExceptions'])) {
                    $options['throwConnectionExceptions'] = $app['config']['statsd.throwConnectionExceptions'];
                }

                // Create
                $statsd = new Statsd();
                $statsd->configure($options);
                return $statsd;
            }
        );
    }
}
