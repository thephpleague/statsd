<?php

namespace StatsD\Test;

class CounterTest extends TestCase
{

    public function testIncrement()
    {
        $this->client->increment('test_metric');
        $this->assertEquals('test_metric:1|c', $this->client->getLastMessage());
    }


    public function testIncrementDelta()
    {
        $this->client->increment('test_metric', 2);
        $this->assertEquals('test_metric:2|c', $this->client->getLastMessage());
    }


    public function testIncrementSample()
    {
        while ($this->client->getLastMessage() === '') {
            $this->client->increment('test_metric', 1,  0.75);
        }
        $this->assertEquals('test_metric:1|c|@0.75', $this->client->getLastMessage());
    }


    public function testDecrement()
    {
        $this->client->decrement('test_metric');
        $this->assertEquals('test_metric:-1|c', $this->client->getLastMessage());
    }


    public function testDecrementDelta()
    {
        $this->client->decrement('test_metric', 3);
        $this->assertEquals('test_metric:-3|c', $this->client->getLastMessage());
    }

}
