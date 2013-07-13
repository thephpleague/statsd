<?php

namespace StatsD\Test;

class MetricsTest extends TestCase
{

    public function testIncrement()
    {
        $this->client->configure(array(
            'host' => '127.0.0.1',
            'port' => 8125,
        ));
        $this->client->increment('test_metric');
        $this->assertEquals($this->client->getLastMessage(), 'test_metric:1|c');
    }


    public function testDecrement()
    {
        $this->client->configure(array(
            'host' => '127.0.0.1',
            'port' => 8125,
        ));
        $this->client->decrement('test_metric');
        $this->assertEquals($this->client->getLastMessage(), 'test_metric:-1|c');
    }

}
