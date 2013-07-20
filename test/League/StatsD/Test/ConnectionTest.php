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


}
