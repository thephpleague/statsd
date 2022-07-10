<?php

namespace League\StatsD\Test;

class TcpTest extends TcpTestCase
{
    public function testEachMetricMustBeTerminatedByTheNewLineCharacter()
    {
        $this->client->timing('test_metric', 123);
        $this->assertEquals("test_metric:123|ms\n", $this->client->getLastMessage());
    }

    public function testErrorMessageContainsCorrectScheme()
    {
        $this->client->configure([
            'host' => 'hostdoesnotexiststalleverlol.stupidtld',
            'port' => 8125,
            'throwConnectionExceptions' => false
        ]);
        $handlerInvoked = false;

        $testCase = $this;

        set_error_handler(
            function ($errno, $errstr, $errfile) use ($testCase, &$handlerInvoked) {
                $handlerInvoked = true;
                $testCase->assertStringContainsString(
                    'StatsD server connection failed (tcp://hostdoesnotexiststalleverlol.stupidtld:8125)',
                    $errstr
                );
            },
            E_USER_WARNING
        );

        $this->client->increment('test');
        restore_error_handler();

        $this->assertTrue($handlerInvoked);
    }
}
