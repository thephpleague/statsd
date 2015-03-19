<?php

namespace League\StatsD\Test;

class ConnectionTest extends TestCase
{

    /**
     * Non-integer ports are not acceptable
     * @expectedException League\StatsD\Exception\ConnectionException
     */
    public function testInvalidHost()
    {
        $this->client->configure(array(
            'host' => 'hostdoesnotexiststalleverlol.stupidtld'
        ));
        $this->client->increment('test');
    }

    public function testTimeoutSettingIsUsedWhenCreatingSocketIfProvided()
    {
        $this->client->configure(array(
            'host' => 'localhost',
            'timeout' => 123
        ));

        $this->assertAttributeSame(123, 'timeout', $this->client);
    }

    public function testTimeoutDefaultsToPhpIniDefaultSocketTimeout()
    {
        $this->assertAttributeSame(ini_get('default_socket_timeout'), 'timeout', $this->client);
    }
}
