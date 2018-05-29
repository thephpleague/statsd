<?php

namespace League\StatsD\Test;

class TimingsTest extends TestCase
{

    public function testTiming()
    {
        $timings = array(
            'test_metric1' => 123,
            'test_metric2' => 234,
            'test_metric3' => .234,
        );
        $this->client->timings($timings);
        $this->assertEquals(
            'test_metric1:123|ms'.PHP_EOL.'test_metric2:234|ms'.PHP_EOL.'test_metric3:0.234|ms',
            $this->client->getLastMessage()
        );
    }
}
