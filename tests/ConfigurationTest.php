<?php

namespace League\StatsD\Test;

use League\StatsD\Exception\ConfigurationException;

class ConfigurationTest extends TestCase
{
    /**
     * Large ports should be out of range
     */
    public function testLargePort()
    {
        $this->expectException(ConfigurationException::class);
        $this->client->configure(['port' => 65536]);
    }

    /**
     * Test that user can configure port 0
     */
    public function testPort0()
    {
        $this->client->configure(['port' => 0]);

        $this->assertEquals($this->client->getPort(), 0);
    }

    /**
     * Non-integer ports are not acceptable
     */
    public function testStringPort()
    {
        $this->expectException(ConfigurationException::class);
        $this->client->configure([
            'port' => 'not-integer'
        ]);
    }

    /**
     * Decimal ports are not acceptable
     */
    public function testDecimalPort()
    {
        $this->expectException(ConfigurationException::class);
        $this->client->configure([
            'port' => 1.24,
        ]);
    }

    /**
     * Negative ports are not acceptable
     */
    public function testNegativePort()
    {
        $this->expectException(ConfigurationException::class);
        $this->client->configure([
            'port' => -1
        ]);
    }

    /**
     * Default Port
     */
    public function testDefaultPort()
    {
        $this->assertEquals($this->client->getPort(), 8125);
    }


    /**
     * Valid Port
     */
    public function testValidPort()
    {
        $this->client->configure([
            'port' => 1234
        ]);
        $this->assertEquals($this->client->getPort(), 1234);
    }
}
