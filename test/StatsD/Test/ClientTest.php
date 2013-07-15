<?php

namespace StatsD\Test;

class ClientTest extends TestCase
{


    public function testNewInstance()
    {
        $client = new \StatsD\Client();
        $this->assertTrue($client instanceof \StatsD\Client);
        $this->assertRegExp('/^StatsD\\\Client::\[[a-zA-Z0-9]+\]$/', (String) $client);
    }


    public function testStaticInstance()
    {
        $client1 = \StatsD\Client::instance('instance1');
        $this->assertTrue($client1 instanceof \StatsD\Client);
        $client2 = \StatsD\Client::instance('instance2');
        $client3 = \StatsD\Client::instance('instance1');
        $this->assertEquals('StatsD\Client::[instance2]', (String) $client2);
        $this->assertFalse((String) $client1 === (String) $client2);
        $this->assertTrue((String) $client1 === (String) $client3);
    }

}
