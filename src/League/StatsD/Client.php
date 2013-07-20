<?php

namespace League\StatsD;

use League\StatsD\Exception\ConnectionException;
use League\StatsD\Exception\ConfigurationException;

class Client
{

    protected static $instances = array();
    protected $instance_id;
    protected $host = '127.0.0.1';
    protected $port = 8125;
    protected $message = '';
    protected $namespace = '';


    /**
     * Singleton Reference
     * @param  string $name Instance name
     * @return League\StatsD\Client Client instance
     */
    public static function instance($name = 'default')
    {
        if (! isset(self::$instances[$name])) {
            $client = new Client($name);
            $client->setInstanceId($name);
            self::$instances[$name] = $client;
        }
        return self::$instances[$name];
    }


    /**
     * Create a new instance
     */
    public function __construct()
    {
        $this->instance_id = uniqid();
    }


    /**
     * Set Instance ID
     * @param  string $id Set the ID for this instance
     * @return string The new instance ID
     */
    protected function setInstanceId($id)
    {
        $this->instance_id = $id;
        return $id;
    }


    /**
     * Get string value of instance
     * @return string String representation of this instance
     */
    public function __toString()
    {
        return 'StatsD\Client::[' . $this->instance_id . ']';
    }


    /**
     * Initialize Connection Details
     * @param array $options Configuration options
     * @return League\StatsD\Client This instance
     */
    public function configure(array $options = array())
    {
        if (isset($options['host'])) {
            $this->host = $options['host'];
        }
        if (isset($options['port'])) {
            $port = (int) $options['port'];
            if (! $port || !is_numeric($port) || $port > 65535) {
                throw new ConfigurationException($this, 'Port is out of range');
            }
            $this->port = $port;
        }
        if (isset($options['namespace'])) {
            $this->namespace = $options['namespace'];
        }
        return $this;
    }


    /**
     * Get Host
     * @return string Host
     */
    public function getHost()
    {
        return $this->host;
    }


    /**
     * Get Port
     * @return string Port
     */
    public function getPort()
    {
        return $this->port;
    }


    /**
     * Get Namespace
     * @return string Namespace
     */
    public function getNamespace()
    {
        return $this->namespace;
    }


    /**
     * Get Last Message
     * @return string Last message sent to server
     */
    public function getLastMessage()
    {
        return $this->message;
    }


    /**
     * Counters
     */
    public function increment($metrics, $delta = 1, $sampleRate = 1)
    {
        if (! is_array($metrics)) {
            $metrics = array($metrics);
        }
        $data = array();
        foreach ($metrics as $metric) {
            if ($sampleRate < 1) {
                if ((mt_rand() / mt_getrandmax()) <= $sampleRate) {
                    $data[$metric] = $delta . '|c|@' . $sampleRate;
                }
            } else {
                $data[$metric] = $delta . '|c';
            }
        }
        return $this->send($data);
    }
    public function decrement($metrics, $delta = 1, $sampleRate = 1)
    {
        return $this->increment($metrics, 0 - $delta, $sampleRate);
    }


    /**
     * Timing
     * @param  String $metric Metric to track
     * @param  Float $time    Time in miliseconds
     * @return boolean        True if data transfer is successful
     */
    public function timing($metric, $time)
    {
        return $this->send(
            array(
                $metric => $time . '|ms'
            )
        );
    }


    /**
     * Time a function
     */
    public function time($metric, $func)
    {
        $timer_start = microtime(true);
        $func();
        $timer_end = microtime(true);
        $time = round(($timer_end - $timer_start) * 1000, 4);
        return $this->timing($metric, $time);
    }


    /**
     * Gaugues
     */
    public function gauge($metric, $value)
    {
        return $this->send(
            array(
                $metric => $value . '|g'
            )
        );
    }


    /**
     * Send Data to StatsD Server
     */
    private function send($data)
    {

        $fp = @fsockopen('udp://' . $this->host, $this->port, $errno, $errstr);
        if (! $fp) {
            throw new ConnectionException($this, $errstr);
        }
        foreach ($data as $key => $value) {
            $this->message = ($this->namespace ? $this->namespace . '.' : '') . $key . ':' . $value;
            @fwrite($fp, $this->message);
        }
        fclose($fp);
        return $this;

    }
}
