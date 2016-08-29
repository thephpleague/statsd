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
		// Publish config files
		$this->publishes([
			__DIR__.'/../../../config/config.php' => config_path('statsd.php'),
		]);
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
        $this->app['statsd'] = $this->app->share(function () {
            // Set Default host and port
            $config = config('statsd');
            $options = array();
            if (isset($config['host'])) {
                $options['host'] = $config['host'];
            }
            if (isset($config['port'])) {
                $options['port'] = $config['port'];
            }
            if (isset($config['namespace'])) {
                $options['namespace'] = $config['namespace'];
            }
            if (isset($config['timeout'])) {
                $options['timeout'] = $config['timeout'];
            }
            if (isset($config['throwConnectionExceptions'])) {
                $options['throwConnectionExceptions'] = $config['throwConnectionExceptions'];
            }

            // Create
            return (new Statsd())->configure($options);
        });

        $this->app->bind('League\StatsD\Client', function ($app) {
            return $app['statsd'];
        });
    }
}
