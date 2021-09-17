<?php

namespace League\StatsD;

use League\StatsD\Exception\ConnectionException;
use League\StatsD\Exception\ConfigurationException;

/**
 * StatsD Client Class
 *
 * @author Marc Qualie <marc@marcqualie.com>
 */
class Client
{

    /**
     * Instance instances array
     * @var array
     */
    protected static $instances = array();


    /**
     * Instance ID
     * @var string
     */
    protected $instance_id;


    /**
     * Server Host
     * @var string
     */
    protected $host = '127.0.0.1';


    /**
     * Server Port
     * @var integer
     */
    protected $port = 8125;


    /**
     * Last message sent to the server
     * @var string
     */
    protected $message = '';


    /**
     * Class namespace
     * @var string
     */
    protected $namespace = '';

    /**
     * Timeout for creating the socket connection
     * @var null|float
     */
    protected $timeout;

    /**
     * Whether or not an exception should be thrown on failed connections
     * @var bool
     */
    protected $throwConnectionExceptions = true;

    /**
     * Record metric start time
     * @var array
     */
    protected $metricTiming;

    /**
     * Socket pointer for sending metrics
     * @var resource
     */
    protected $socket;

    /**
     * Generic tags
     * @var array
     */
    protected $tags = [];

    /**
     * Singleton Reference
     * @param  string $name Instance name
     * @return Client Client instance
     */
    public static function instance($name = 'default')
    {
        if (! isset(self::$instances[$name])) {
            self::$instances[$name] = new static($name);
        }
        return self::$instances[$name];
    }


    /**
     * Create a new instance
     * @param string $instance_id
     * @return void
     */
    public function __construct($instance_id = null)
    {
        $this->instance_id = $instance_id ?: uniqid();

        if (empty($this->timeout)) {
            $this->timeout = ini_get('default_socket_timeout');
        }
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
     * @return Client This instance
     * @throws ConfigurationException If port is invalid
     */
    public function configure(array $options = array())
    {
        if (isset($options['host'])) {
            $this->host = $options['host'];
        }
        if (isset($options['port'])) {
            if (!is_numeric($options['port']) || is_float($options['port']) || $options['port'] < 0 || $options['port'] > 65535) {
                throw new ConfigurationException($this, 'Port is out of range');
            }
            $this->port = $options['port'];
        }

        if (isset($options['namespace'])) {
            $this->namespace = $options['namespace'];
        }

        if (isset($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }

        if (isset($options['throwConnectionExceptions'])) {
            $this->throwConnectionExceptions = $options['throwConnectionExceptions'];
        }

        if (isset($options['tags'])) {
            $this->tags = $options['tags'];
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
     * Increment a metric
     * @param  string|array $metrics Metric(s) to increment
     * @param  int $delta Value to decrement the metric by
     * @param  float|int $sampleRate Sample rate of metric
     * @param  array $tags A list of metric tags values
     * @return $this
     * @throws ConnectionException
     */
    public function increment($metrics, $delta = 1, $sampleRate = 1, array $tags = [])
    {
        $metrics = (array) $metrics;
        $data = array();
        if ($sampleRate < 1) {
            foreach ($metrics as $metric) {
                if ((mt_rand() / mt_getrandmax()) <= $sampleRate) {
                    $data[$metric] = $delta . '|c|@' . $sampleRate;
                }
            }
        } else {
            foreach ($metrics as $metric) {
                $data[$metric] = $delta . '|c';
            }
        }
        return $this->send($data, $tags);
    }


    /**
     * Decrement a metric
     * @param  string|array $metrics Metric(s) to decrement
     * @param  int $delta Value to increment the metric by
     * @param  float|int $sampleRate Sample rate of metric
     * @param  array $tags A list of metric tags values
     * @return $this
     * @throws ConnectionException
     */
    public function decrement($metrics, $delta = 1, $sampleRate = 1, array $tags = [])
    {
        return $this->increment($metrics, 0 - $delta, $sampleRate, $tags);
    }

    /**
     * Start timing the given metric
     * @param  string $metric Metric to time
     * @return $this
     */
    public function startTiming($metric)
    {
        $this->metricTiming[$metric] = microtime(true);
        return $this;
    }

    /**
     * End timing the given metric and record
     * @param  string $metric Metric to time
     * @param  array $tags A list of metric tags values
     * @return $this
     * @throws ConnectionException
     */
    public function endTiming($metric, array $tags = array())
    {
        $timer_start = $this->metricTiming[$metric];
        $timer_end = microtime(true);
        $time = round(($timer_end - $timer_start) * 1000, 4);
        return $this->timing($metric, $time, $tags);
    }

    /**
     * Timing
     * @param  string $metric Metric to track
     * @param  float $time Time in milliseconds
     * @param  array $tags A list of metric tags values
     * @return $this
     * @throws ConnectionException
     */
    public function timing($metric, $time, array $tags = array())
    {
        return $this->send(
            array(
                $metric => $time . '|ms'
            ),
            $tags
        );
    }

    /**
     * Send multiple timing metrics at once
     * @param array $metrics key value map of metric name -> timing value
     * @return Client
     * @throws ConnectionException
     */
    public function timings($metrics)
    {
        // add |ms to values
        $data = [];
        foreach ($metrics as $metric => $timing) {
            $data[$metric] = $timing.'|ms';
        }

        return $this->send($data);
    }

    /**
     * Time a function
     * @param  string $metric Metric to time
     * @param  callable $func Function to record
     * @param  array $tags A list of metric tags values
     * @return $this
     * @throws ConnectionException
     */
    public function time($metric, $func, array $tags = array())
    {
        $timer_start = microtime(true);
        $func();
        $timer_end = microtime(true);
        $time = round(($timer_end - $timer_start) * 1000, 4);
        return $this->timing($metric, $time, $tags);
    }


    /**
     * Gauges
     * @param  string $metric Metric to gauge
     * @param  int $value Set the value of the gauge
     * @param  array $tags A list of metric tags values
     * @return $this
     * @throws ConnectionException
     */
    public function gauge($metric, $value, array $tags = array())
    {
        return $this->send(
            array(
                $metric => $value . '|g'
            ),
            $tags
        );
    }


    /**
     * Sets - count the number of unique values passed to a key
     * @param $metric
     * @param mixed $value
     * @param array $tags A list of metric tags values
     * @return $this
     * @throws ConnectionException
     */
    public function set($metric, $value, array $tags = array())
    {
        return $this->send(
            array(
                $metric => $value . '|s'
            ),
            $tags
        );
    }


    /**
     * @throws ConnectionException
     * @return resource
     */
    protected function getSocket()
    {
        if (!$this->socket) {
            $this->socket = @fsockopen('udp://' . $this->host, $this->port, $errno, $errstr, $this->timeout);
            if (!$this->socket) {
                throw new ConnectionException($this, '(' . $errno . ') ' . $errstr);
            }
        }

        return $this->socket;
    }

    /**
     * @param array $tags
     * @return string
     */
    protected function serializeTags(array $tags)
    {
        if (!is_array($tags) || count($tags) === 0) {
            return '';
        }
        $data = array();
        foreach ($tags as $tagKey => $tagValue) {
            $data[] = isset($tagValue) ? $tagKey . ':' . $tagValue : $tagKey;
        }
        return '|#' . implode(',', $data);
    }


    /**
     * Send Data to StatsD Server
     * @param  array $data A list of messages to send to the server
     * @param  array $tags A list of tags to send to the server
     * @return $this
     * @throws ConnectionException If there is a connection problem with the host
     */
    protected function send(array $data, array $tags = array())
    {
        $tagsData = $this->serializeTags(array_replace($this->tags, $tags));

        try {
            $socket = $this->getSocket();
            $messages = array();
            $prefix = $this->namespace ? $this->namespace . '.' : '';
            foreach ($data as $key => $value) {
                $messages[] = $prefix . $key . ':' . $value . $tagsData;
            }
            $this->message = implode("\n", $messages);
            @fwrite($socket, $this->message);
            fflush($socket);
        } catch (ConnectionException $e) {
            if ($this->throwConnectionExceptions) {
                throw $e;
            } else {
                trigger_error(
                    sprintf('StatsD server connection failed (udp://%s:%d)', $this->host, $this->port),
                    E_USER_WARNING
                );
            }
        }

        return $this;
    }
}
