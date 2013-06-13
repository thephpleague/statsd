<?php

class StatsD {


    /**
     * Static Instance Reference
     */
    private static $instances = array();
    public static function instance($name = 'default') {
        if ( ! isset(self::$instances[$name]))
        {
            self::$instances[$name] = new StatsD();
        }
        return self::$instances[$name];
    }


    private $host = '127.0.0.1';
    private $port = 8125;


    /**
     * Create new Instance
     */
    public function __construct ()
    {

    }


    /**
     * Initialize Connection Details
     */
    public function configure (array $options = array())
    {
        foreach ($options as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $this->$key = $value;
            }
        }
    }


    /**
     * Increment
     */
    public function increment ($metric)
    {

    }


    /**
     * Timing
     */
    public function timing ($metric, $value)
    {

    }


    /**
     * Send Data to StatsD Server
     */
    private function send ()
    {

    }

}
