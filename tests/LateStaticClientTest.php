<?php

namespace League\StatsD\Test;

class LateStaticClientTest extends TestCase
{

    public function testStaticInstance()
    {
        $client = new LateStaticClient();
        $instance = LateStaticClient::instance();
        $this->assertInstanceOf(LateStaticClient::class, $instance);
    }

}
