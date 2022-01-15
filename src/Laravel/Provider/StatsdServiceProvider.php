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
                $options = [];
                $config  = $app['config'];

                if (isset($config['statsd.host'])) {
                    $options['host'] = $config['statsd.host'];
                }

                if (isset($config['statsd.port'])) {
                    $options['port'] = $config['statsd.port'];
                }

                if (isset($config['statsd.namespace'])) {
                    $options['namespace'] = $config['statsd.namespace'];
                }

                if (isset($config['statsd.timeout'])) {
                    $options['timeout'] = $config['statsd.timeout'];
                }

                if (isset($config['statsd.throwConnectionExceptions'])) {
                    $options['throwConnectionExceptions'] = (bool) $config['statsd.throwConnectionExceptions'];
                }

                // Create
                $statsd = new Statsd();
                $statsd->configure($options);
                return $statsd;
            }
        );
    }
}
