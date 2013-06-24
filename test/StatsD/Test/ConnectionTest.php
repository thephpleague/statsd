<?php

namespace StatsD\Test;

class ConnectionTest extends TestCase
{

    /**
     * Non-integer ports are not acceptable
     * @expectedException StatsD\Exception\ConnectionException
     */
    public function testInvalidHost()
    {
        $this->client->configure(array(
            'host' => 'hostdoesnotexiststalleverlol.stupidtld'
        ));
        $this->client->increment('test');
    }


}
