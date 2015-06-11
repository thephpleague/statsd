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

    public function testCanBeConfiguredNotToThrowConnectionExceptions()
    {
        $this->client->configure(array(
            'host' => 'hostdoesnotexiststalleverlol.stupidtld',
            'throwConnectionExceptions' => false
        ));
        $handlerInvoked = false;

        $testCase = $this;

        set_error_handler(
            function ($errno, $errstr, $errfile, $errline, $errcontext) use ($testCase, &$handlerInvoked) {
                $handlerInvoked = true;

                $testCase->assertSame(E_USER_WARNING, $errno);
                $testCase->assertSame(
                    'StatsD server connection failed (udp://hostdoesnotexiststalleverlol.stupidtld:8125)',
                    $errstr
                );
                $testCase->assertSame(realpath(__DIR__ . '/../src/Client.php'), $errfile);
            },
            E_USER_WARNING
        );

        $this->client->increment('test');
        restore_error_handler();

        $this->assertTrue($handlerInvoked);
    }

    public function testTimeoutDefaultsToPhpIniDefaultSocketTimeout()
    {
        $this->assertAttributeSame(ini_get('default_socket_timeout'), 'timeout', $this->client);
    }
}
