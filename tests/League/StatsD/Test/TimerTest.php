<?php

namespace League\StatsD\Test;

class TimerTest extends TestCase
{

    public function testTiming()
    {
        $this->client->timing('test_metric', 123);
        $this->assertEquals('test_metric:123|ms', $this->client->getLastMessage());
    }


    public function testFunctionTiming()
    {
        $this->client->time('test_metric', function () {
            usleep(50000);
        });
        $this->assertRegExp('/test_metric:5[0-9]{1}\.[0-9]+\|ms/', $this->client->getLastMessage());
    }

}
