<?php

namespace League\StatsD\Test;

use League\StatsD\Client;
use League\StatsD\Exception\ConnectionException;

class ConnectionTest extends TestCase
{
    /**
     * Non-integer ports are not acceptable
     */
    public function testInvalidHost()
    {
        $this->expectException(ConnectionException::class);
        $this->client->configure([
            'host' => 'hostdoesnotexiststalleverlol.stupidtld'
        ]);
        $this->client->increment('test');
    }

    public function testTimeoutSettingIsUsedWhenCreatingSocketIfProvided()
    {
        $client = (new TestClient())->configure([
            'host' => 'localhost',
            'timeout' => 123
        ]);
        $this->assertSame((float)123, $client->getTimeout());
    }

    public function testCanBeConfiguredNotToThrowConnectionExceptions()
    {
        $this->client->configure([
            'host' => 'hostdoesnotexiststalleverlol.stupidtld',
            'throwConnectionExceptions' => false
        ]);
        $handlerInvoked = false;

        $testCase = $this;

        set_error_handler(
            function ($errno, $errstr, $errfile) use ($testCase, &$handlerInvoked) {
                $handlerInvoked = true;

                $testCase->assertSame(E_USER_WARNING, $errno);
                $testCase->assertStringContainsString(
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
        $this->assertSame((float)ini_get('default_socket_timeout'), (new TestClient())->configure()->getTimeout());
    }
}
