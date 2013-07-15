<?php

namespace StatsD\Test;

class MetricsTest extends TestCase
{

    public function testIncrement()
    {
        $this->client->increment('test_metric');
        $this->assertEquals($this->client->getLastMessage(), 'test_metric:1|c');
    }


    public function testIncrementDelta()
    {
        $this->client->increment('test_metric', 2);
        $this->assertEquals($this->client->getLastMessage(), 'test_metric:2|c');
    }


    public function testIncrementSample()
    {
        while ($this->client->getLastMessage() === '') {
            $this->client->increment('test_metric', 1,  0.75);
        }
        $this->assertEquals($this->client->getLastMessage(), 'test_metric:1|c|@0.75');
    }


    public function testDecrement()
    {
        $this->client->decrement('test_metric');
        $this->assertEquals($this->client->getLastMessage(), 'test_metric:-1|c');
    }

}
