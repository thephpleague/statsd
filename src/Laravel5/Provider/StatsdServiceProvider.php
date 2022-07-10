<?php

namespace League\StatsD\Laravel5\Provider;

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
        if (false !== strpos($this->app->version(), 'Lumen')) {
            $this->app->configure('statsd');
        } else {
            // Publish config files
            $this->publishes([
                __DIR__ . '/../../../config/statsd.php' => config_path('statsd.php'),
            ], 'config');
        }
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
        $this->app->singleton(
            'statsd',
            function ($app) {
                // Set Default host and port
                $options = [];
                $config  = $app['config'];

                if (!empty($config['statsd.scheme'])) {
                    $options['scheme'] = $config['statsd.scheme'];
                }

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

        $this->app->bind('League\StatsD\Client', function ($app) {
            return $app['statsd'];
        });
    }
}
