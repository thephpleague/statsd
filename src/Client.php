<?php

namespace League\StatsD;

use League\StatsD\Exception\ConfigurationException;
use League\StatsD\Exception\ConnectionException;

/**
 * StatsD Client Class
 *
 * @author Marc Qualie <marc@marcqualie.com>
 */
class Client implements StatsDClient
{
    /** Instance instances array */
    protected static array $instances = [];

    /** Instance ID */
    protected string $instanceId;

    /** Server Host */
    protected string $host = '127.0.0.1';

    /** Server Port */
    protected int $port = 8125;

    /** Last message sent to the server */
    protected string $message = '';

    /** Class namespace */
    protected string $namespace = '';

    /** imeout for creating the socket connection */
    protected ?float $timeout = null;

    /** Whether an exception should be thrown on failed connections */
    protected bool $throwConnectionExceptions = true;

    /** Record metric start time */
    protected array $metricTiming;

    /** @var resource|false|null Socket pointer for sending metrics */
    protected $socket;

    /** Generic tags */
    protected array $tags = [];

    /**
     * Singleton Reference
     */
    public static function instance(string $name = 'default'): StatsDClient
    {
        if (! isset(self::$instances[$name])) {
            self::$instances[$name] = new static($name);
        }

        return self::$instances[$name];
    }

    /**
     * Create a new instance
     */
    public function __construct(?string $instanceId = null)
    {
        $this->instanceId = $instanceId ?? uniqid();
        if ($this->timeout === null) {
            $this->timeout = ini_get('default_socket_timeout');
        }
    }

    public function __toString(): string
    {
        return 'StatsD\Client::[' . $this->instanceId . ']';
    }

    /**
     * Initialize Connection Details
     *
     * @param array $options Configuration options
     *
     * @return Client This instance
     * @throws ConfigurationException If port is invalid
     */
    public function configure(array $options = []): self
    {
        if (isset($options['host'])) {
            $this->host = $options['host'];
        }
        if (isset($options['port'])) {
            if (! is_numeric($options['port']) || is_float($options['port']) || $options['port'] < 0 || $options['port'] > 65535) {
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

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get Last message sent to server
     */
    public function getLastMessage(): string
    {
        return $this->message;
    }

    /**
     * Increment a metric
     *
     * @param string|array $metrics    Metric(s) to increment
     * @param int          $delta      Value to decrement the metric by
     * @param float        $sampleRate Sample rate of metric
     * @param array        $tags       A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function increment($metrics, int $delta = 1, float $sampleRate = 1, array $tags = []): void
    {
        $metrics = (array)$metrics;
        $data    = [];
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

        $this->send($data, $tags);
    }

    /**
     * Decrement a metric
     *
     * @param string|array $metrics    Metric(s) to decrement
     * @param int          $delta      Value to increment the metric by
     * @param float          $sampleRate Sample rate of metric
     * @param array        $tags       A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function decrement($metrics, int $delta = 1, float $sampleRate = 1, array $tags = []): void
    {
        $this->increment($metrics, 0 - $delta, $sampleRate, $tags);
    }

    /**
     * Start timing the given metric
     *
     * @param string $metric Metric to time
     */
    public function startTiming(string $metric): void
    {
        $this->metricTiming[$metric] = microtime(true);
    }

    /**
     * End timing the given metric and record
     *
     * @param string $metric Metric to time
     * @param array  $tags   A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function endTiming(string $metric, array $tags = []): void
    {
        $timer_start = $this->metricTiming[$metric];
        $timer_end   = microtime(true);
        $time        = round(($timer_end - $timer_start) * 1000, 4);
        $this->timing($metric, $time, $tags);
    }

    /**
     * Timing
     *
     * @param string $metric Metric to track
     * @param float  $time   Time in milliseconds
     * @param array  $tags   A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function timing(string $metric, float $time, array $tags = []): void
    {
        $this->send(
            [$metric => $time . '|ms'],
            $tags
        );
    }

    /**
     * Send multiple timing metrics at once
     *
     * @param array $metrics key value map of metric name -> timing value
     *
     * @throws ConnectionException
     */
    public function timings(array $metrics): void
    {
        // add |ms to values
        $data = [];
        foreach ($metrics as $metric => $timing) {
            $data[$metric] = $timing . '|ms';
        }

        $this->send($data);
    }

    /**
     * Time a function
     *
     * @param string   $metric Metric to time
     * @param callable $func   Function to record
     * @param array    $tags   A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function time(string $metric, $func, array $tags = []): void
    {
        $timer_start = microtime(true);
        $func();
        $timer_end = microtime(true);
        $time      = round(($timer_end - $timer_start) * 1000, 4);
        $this->timing($metric, $time, $tags);
    }


    /**
     * Gauges
     *
     * @param string $metric Metric to gauge
     * @param int|float $value  Set the value of the gauge
     * @param array  $tags   A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function gauge(string $metric, $value, array $tags = []): void
    {
        $this->send([$metric => $value . '|g'], $tags);
    }

    /**
     * Sets - count the number of unique values passed to a key
     *
     * @param string $metric
     * @param mixed $value
     * @param array $tags A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function set(string $metric, $value, array $tags = []): void
    {
        $this->send([$metric => $value . '|s'], $tags);
    }

    /**
     * @return resource
     * @throws ConnectionException
     */
    protected function getSocket()
    {
        if (! $this->socket) {
            $this->socket = @fsockopen('udp://' . $this->host, $this->port, $errno, $errstr, $this->timeout);
            if (! $this->socket) {
                throw new ConnectionException($this, '(' . $errno . ') ' . $errstr);
            }
        }

        return $this->socket;
    }

    protected function serializeTags(array $tags): string
    {
        if (! is_array($tags) || count($tags) === 0) {
            return '';
        }
        $data = [];
        foreach ($tags as $tagKey => $tagValue) {
            $data[] = isset($tagValue) ? $tagKey . ':' . $tagValue : $tagKey;
        }

        return '|#' . implode(',', $data);
    }

    /**
     * Send Data to StatsD Server
     *
     * @param array $data A list of messages to send to the server
     * @param array $tags A list of tags to send to the server
     *
     * @throws ConnectionException If there is a connection problem with the host
     */
    protected function send(array $data, array $tags = []): void
    {
        $tagsData = $this->serializeTags(array_replace($this->tags, $tags));

        try {
            $socket   = $this->getSocket();
            $messages = [];
            $prefix   = $this->namespace ? $this->namespace . '.' : '';
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
                    sprintf(
                        'StatsD server connection failed (udp://%s:%d): %s',
                        $this->host,
                        $this->port,
                        $e->getMessage()
                    ),
                    E_USER_WARNING
                );
            }
        }
    }
}
