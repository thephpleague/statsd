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
