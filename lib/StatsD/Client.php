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


    protected $host = '127.0.0.1';
    protected $port = 8125;
    protected $message = '';


    /**
     * Create new Instance
     */
    public function __construct ()
    {

    }


    /**
     * Initialize Connection Details
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
        return $this;
    }


    /**
     * Get Port
     */
    public function getHost()
    {
        return $this->host;
    }


    /**
     * Get Host
     */
    public function getPort()
    {
        return $this->port;
    }


    /**
     * Get Last Message
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
            $this->message = $key . ':' . $value;
            if (! fwrite($fp, $this->message)) {
                throw new ConnectionException(
                    $this,
                    'Could not write to ' . $this->host . ':' . $this->port . ' (' . $errno . ': ' . $errstr . ')'
                );
            }
        }
        fclose($fp);
        return $this;

    }
}
