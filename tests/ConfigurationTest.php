<?php

namespace League\StatsD\Test;

class ConfigurationTest extends TestCase
{

    /**
     * Large ports should be out of range
     * @expectedException League\StatsD\Exception\ConfigurationException
     */
    public function testLargePort()
    {
        $this->client->configure(array(
            'port' => 65536
        ));
    }

    /**
     * Test that user can configure port 0
     */
    public function testPort0()
    {
        $this->client->configure(array(
            'port' => 0
        ));

        $this->assertEquals($this->client->getPort(), 0);
    }

    /**
     * Non-integer ports are not acceptable
     * @expectedException League\StatsD\Exception\ConfigurationException
     */
    public function testStringPort()
    {
        $this->client->configure(array(
            'port' => 'not-integer'
        ));
    }

    /**
     * Decimal ports are not acceptable
     * @expectedException League\StatsD\Exception\ConfigurationException
     */
    public function testDecimalPort()
    {
        $this->client->configure(array(
            'port' => 1.24,
        ));
    }

    /**
     * Negative ports are not acceptable
     * @expectedException League\StatsD\Exception\ConfigurationException
     */
    public function testNegativePort()
    {
        $this->client->configure(array(
            'port' => -1
        ));
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
        $this->client->configure(array(
            'port' => 1234
        ));
        $this->assertEquals($this->client->getPort(), 1234);
    }

}
