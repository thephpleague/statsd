<?php

namespace League\StatsD\Exception;

use League\StatsD\Client;
use League\StatsD\StatsDClient;

/**
 * Configuration Exception Class
 */
class ConfigurationException extends \Exception implements Exception
{
    /**
     * Client instance that threw the exception
     */
    protected StatsDClient $instance;

    /**
     * Create new instance
     * @param StatsDClient $instance Client instance that threw the exception
     * @param string $message Exception message
     */
    public function __construct(StatsDClient $instance, string $message)
    {
        $this->instance = $instance;
        parent::__construct($message);
    }

    /**
     * Get Client instance that threw the exception
     */
    public function getInstance(): StatsDClient
    {
        return $this->instance;
    }
}
