<?php

namespace League\StatsD\Test;

use League\StatsD\Client;

class ClientTest extends TestCase
{
    public function testNewInstance()
    {
        $client = new Client();
        $this->assertInstanceOf(Client::class, $client);
        $this->assertMatchesRegularExpression('/^StatsD\\\Client::\[[a-zA-Z0-9]+\]$/', (string) $client);
    }

    public function testStaticInstance()
    {
        $client1 = Client::instance('instance1');
        $this->assertInstanceOf(Client::class, $client1);
        $client2 = Client::instance('instance2');
        $client3 = Client::instance('instance1');
        $this->assertEquals('StatsD\Client::[instance2]', (string) $client2);
        $this->assertFalse((string) $client1 === (string) $client2);
        $this->assertTrue((string) $client1 === (string) $client3);
    }
}
