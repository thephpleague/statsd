<?php

namespace League\StatsD;

use League\StatsD\Exception\ConnectionException;

/**
 * StatsD Client Interface
 *
 * @author Marc Qualie <marc@marcqualie.com>
 */
interface StatsDClient
{
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
    public function increment($metrics, int $delta = 1, float $sampleRate = 1, array $tags = []): void;

    /**
     * Decrement a metric
     *
     * @param string|array $metrics    Metric(s) to decrement
     * @param int          $delta      Value to increment the metric by
     * @param float        $sampleRate Sample rate of metric
     * @param array        $tags       A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function decrement($metrics, int $delta = 1, float $sampleRate = 1, array $tags = []): void;

    /**
     * Start timing the given metric
     *
     * @param string $metric Metric to time
     */
    public function startTiming(string $metric): void;

    /**
     * End timing the given metric and record
     *
     * @param string $metric Metric to time
     * @param array  $tags   A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function endTiming(string $metric, array $tags = []): void;

    /**
     * Timing
     *
     * @param string $metric Metric to track
     * @param float  $time   Time in milliseconds
     * @param array  $tags   A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function timing(string $metric, float $time, array $tags = []): void;

    /**
     * Send multiple timing metrics at once
     *
     * @param array $metrics key value map of metric name -> timing value
     *
     * @throws ConnectionException
     */
    public function timings(array $metrics): void;

    /**
     * Time a function
     *
     * @param string   $metric Metric to time
     * @param callable $func   Function to record
     * @param array    $tags   A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function time(string $metric, $func, array $tags = []): void;

    /**
     * Gauges
     *
     * @param string $metric Metric to gauge
     * @param int    $value  Set the value of the gauge
     * @param array  $tags   A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function gauge(string $metric, int $value, array $tags = []): void;

    /**
     * Sets - count the number of unique values passed to a key
     *
     * @param string $metric
     * @param mixed $value
     * @param array $tags A list of metric tags values
     *
     * @throws ConnectionException
     */
    public function set(string $metric, $value, array $tags = []): void;
}
