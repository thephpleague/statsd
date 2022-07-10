<?php

namespace League\StatsD\Test;

use League\StatsD\Client;

class ClientTest extends TestCase
{
    /**
     * Creates instances of Client
     */
    public function testNewInstance()
    {
        $client = new Client();
        $this->assertInstanceOf(Client::class, $client);
        $this->assertMatchesRegularExpression('/^StatsD\\\Client::\[[a-zA-Z0-9]+]$/', (string) $client);
    }

    /**
     * Returns the same instance for the same name
     */
    public function testStaticInstance()
    {
        $client1 = Client::instance('instance1');
        $this->assertInstanceOf(Client::class, $client1);
        $client2 = Client::instance('instance2');
        $client3 = Client::instance('instance1');
        $this->assertEquals('StatsD\Client::[instance2]', (string) $client2);
        $this->assertFalse($client1 === $client2);
        $this->assertTrue($client1 === $client3);
    }

    /**
     * Can forget only specified instance
     */
    public function testForgetStaticInstance()
    {
        $client1 = Client::instance('instance1');
        $client2 = Client::instance('instance2');
        Client::forget('instance1');
        $client3 = Client::instance('instance1');
        $client4 = Client::instance('instance2');
        $this->assertFalse($client1 === $client3);
        $this->assertTrue($client2 === $client4);
    }
}
