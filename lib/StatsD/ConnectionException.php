<?php

namespace StatsD;

class ConnectionException extends \Exception
{

    protected $instance;

    public function __construct($instance, $message)
    {
        $this->instance = $instance;
        parent::__construct($message);
    }

    /**
     * Get Instance
     * Really useful when using multiple instances and you're not sure which one is caught
     * @return StatsD\Client Client instance
     */
    public function getInstance()
    {
        return $this->instance;
    }
}
