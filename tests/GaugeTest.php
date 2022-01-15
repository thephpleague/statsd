<?php

namespace League\StatsD\Test;

class GaugeTest extends TestCase
{
    public function testGauge()
    {
        $this->client->gauge('test_metric', 456);
        $this->assertEquals('test_metric:456|g', $this->client->getLastMessage());
    }

    public function testGaugeWithFloat()
    {
        $this->client->gauge('test_metric', 3.14);
        $this->assertEquals('test_metric:3.14|g', $this->client->getLastMessage());
    }
}
