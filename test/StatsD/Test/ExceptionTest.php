<?php

namespace StatsD\Test;

class ExceptionTest extends TestCase
{


    public function testConnectionException()
    {
        try {
            throw new \StatsD\Exception\ConnectionException($this->client, 'Could not connect');
        } catch (\StatsD\Exception\ConnectionException $e) {
            $client = $e->getInstance();
            $this->assertTrue($client instanceof \StatsD\Client);
            $this->assertEquals('Could not connect', $e->getMessage());
            return;
        }
        throw new \Exception('Connection Exception not caught');
    }


    public function testConfigurationException()
    {
        try {
            throw new \StatsD\Exception\ConfigurationException($this->client, 'Configuration error');
        } catch (\StatsD\Exception\ConfigurationException $e) {
            $client = $e->getInstance();
            $this->assertTrue($client instanceof \StatsD\Client);
            $this->assertEquals('Configuration error', $e->getMessage());
            return;
        }
        throw new \Exception('Configuration Exception not caught');
    }

}
