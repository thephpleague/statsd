<?php

namespace League\StatsD\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for Statsd Package
 *
 * @author Aran Wilkinson <aran@aranw.net>
 * @package League\StatsD\Laravel\Facade
 */
class StatsdFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'statsd';
    }
}
