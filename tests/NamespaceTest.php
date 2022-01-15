<?php

namespace League\StatsD\Test;

class NamespaceTest extends TestCase
{
    public function testNamespace()
    {
        $this->client->configure([
            'host' => '127.0.0.1',
            'port' => 8125,
            'namespace' => 'test_namespace'
        ]);
        $this->assertEquals($this->client->getNamespace(), 'test_namespace');
    }


    public function testNamespaceIncrement()
    {
        $this->client->configure([
            'host' => '127.0.0.1',
            'port' => 8125,
            'namespace' => 'test_namespace'
        ]);
        $this->client->increment('test_metric');
        $this->assertEquals($this->client->getLastMessage(), 'test_namespace.test_metric:1|c');
    }
}
