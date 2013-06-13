<?php

namespace StatsD;

class Client
{


    /**
     * Static Instance Reference
     */
    private static $instances = array();
    public static function instance($name = 'default')
    {
        if (! isset(self::$instances[$name])) {
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
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }


    /**
     * Counters
     */
    public function increment ($metrics, $delta = 1, $sampleRate = 1)
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
    public function decrement ($metrics, $delta = 1, $sampleRate = 1)
    {
        return $this->increment($metrics, 0 - $delta, $sampleRate);
    }


    /**
     * Timing
     * @param  String $metric Metric to track
     * @param  Float $time    Time in miliseconds
     * @return boolean        True if data transfer is successful
     */
    public function timing ($metric, $time)
    {
        return $this->send(
            array(
                $metric => $time . '|ms'
            )
        );
    }


    /**
     * Gaugues
     */
    public function gauge ($metric, $value)
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
    private function send ($data)
    {
        try {

            $fp = fsockopen('udp://' . $this->host, $this->port, $errno, $errstr);
            if (! $fp) {
                return false;
            }
            foreach ($data as $key => $value) {
                fwrite($fp, $key . ':' . $value);
            }
            fclose($fp);
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
}
