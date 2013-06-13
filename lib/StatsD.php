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
        return $this;
    }


    /**
     * Increment
     */
    public function increment ($metrics, $delta = 1)
    {
        if ( ! is_array($metrics))
        {
            $metrics = array($metrics);
        }
        $data = array();
        foreach ($metrics as $metric)
        {
            $data[$metric] = $delta . '|c';
        }
        return $this->send($data);
    }


    /**
     * Decrement
     */
    public function decrement ($metrics, $delta = 1)
    {
        return $this->increment($metrics, 0 - $delta);
    }


    /**
     * Timing
     */
    public function timing ($metric, $time)
    {
        return $this->send(array(
            $metric => $time . '|ms'
        ));
    }


    /**
     * Send Data to StatsD Server
     */
    private function send ($data)
    {
        try {

            $fp = fsockopen('udp://' . $this->host, $this->port, $errno, $errstr);
            if ( ! $fp)
            {
                return false;
            }
            foreach ($data as $key => $value)
            {
                fwrite($fp, $key . ':' . $value);
            }
            fclose($fp);
            return true;

        } catch (Exception $e) {
            return false;
        }
    }

}
