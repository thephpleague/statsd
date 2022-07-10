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

    /**
     * Default Scheme
     */
    public function testDefaultScheme()
    {
        $this->assertEquals($this->client->getScheme(), 'udp');
    }

    /**
     * Test that user can configure TCP scheme
     */
    public function testTcpScheme()
    {
        $this->client->configure([
            'scheme' => 'tcp'
        ]);
        $this->assertEquals($this->client->getScheme(), 'tcp');
    }

    /**
     * Test that user can configure UDP scheme
     */
    public function testUdpScheme()
    {
        $this->client->configure([
            'scheme' => 'tcp'
        ]);
        $this->client->configure([
            'scheme' => 'udp'
        ]);
        $this->assertEquals($this->client->getScheme(), 'udp');
    }

    /**
     * Test that user can configure scheme in any case
     */
    public function testSchemeToLower()
    {
        $this->client->configure([
            'scheme' => 'TCP'
        ]);
        $this->assertEquals($this->client->getScheme(), 'tcp');
    }

    /**
     * Only strings are acceptable schemes
     */
    public function testIntegerScheme()
    {
        $this->expectException(ConfigurationException::class);
        $this->client->configure([
            'scheme' => 6
        ]);
    }

    /**
     * Unsupported schemes are not acceptable
     */
    public function testUnsupportedScheme()
    {
        $this->expectException(ConfigurationException::class);
        $this->client->configure([
            'scheme' => 'http'
        ]);
    }

    /**
     * Empty scheme is not acceptable
     */
    public function testEmptyScheme()
    {
        $this->expectException(ConfigurationException::class);
        $this->client->configure([
            'scheme' => ''
        ]);
    }
}
