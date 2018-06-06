<?php

namespace League\StatsD\Test;
use League\StatsD\Exception\ConnectionException;
use League\StatsD\Exception\ConfigurationException;
use League\StatsD\Client;

class ExceptionTest extends TestCase
{


    public function testConnectionException()
    {
        try {
            throw new ConnectionException($this->client, 'Could not connect');
        } catch (ConnectionException $e) {
            $client = $e->getInstance();
            $this->assertInstanceOf(Client::class, $client);
            $this->assertEquals('Could not connect', $e->getMessage());
            return;
        }
        throw new \Exception('Connection Exception not caught');
    }


    public function testConfigurationException()
    {
        try {
            throw new ConfigurationException($this->client, 'Configuration error');
        } catch (ConfigurationException $e) {
            $client = $e->getInstance();
            $this->assertInstanceOf(Client::class, $client);
            $this->assertEquals('Configuration error', $e->getMessage());
            return;
        }
        throw new \Exception('Configuration Exception not caught');
    }

}
