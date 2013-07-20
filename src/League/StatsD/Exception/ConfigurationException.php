<?php

namespace League\StatsD\Exception;

use League\StatsD\Client;

/**
 * Configuration Exception Class
 */
class ConfigurationException extends \Exception
{


    /**
     * Client instance that threw the exception
     * @var Client
     */
    protected $instance;


    /**
     * Create new instance
     * @param Client $instance Client instance that threw the exception
     * @param string $message Exception message
     */
    public function __construct($instance, $message)
    {
        $this->instance = $instance;
        parent::__construct($message);
    }

    /**
     * Get Client instance that threw the exception
     * @return Client Client instance
     */
    public function getInstance()
    {
        return $this->instance;
    }
}
