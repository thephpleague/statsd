<?php

namespace StatsD\Test;

class GaugeTest extends TestCase
{

    public function testGauge()
    {
        $this->client->gauge('test_metric', 456);
        $this->assertEquals('test_metric:456|g', $this->client->getLastMessage());
    }

}
