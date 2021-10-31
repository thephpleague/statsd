<?php

namespace League\StatsD\Test;

class LateStaticClientTest extends TestCase
{
    public function testStaticInstance()
    {
        $client = TestClient::instance();
        $this->assertSame($client, TestClient::instance());
    }
}
