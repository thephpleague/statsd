<?php

namespace League\StatsD\Test;

use League\StatsD\Client;

class TcpTestCase extends \PHPUnit\Framework\TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        $tcpServerConfig = getenv('STATSD_TCP_ADDRESS') ?: '';
        if (empty($tcpServerConfig)) {
            $this->markTestSkipped("Can't test TCP support without configured tcp server");
        }

        $configParts = explode(':', $tcpServerConfig);
        if (count($configParts) < 2) {
            $this->markTestSkipped("Environment variable STATSD_TCP_ADDRESS should bi in format host:port");
        }
        $port = (int)array_pop($configParts);
        $host = implode(':', $configParts); // in case of IPv6 address

        $this->client = new Client();
        $this->client->configure([
            'scheme' => 'tcp',
            'host' => $host,
            'port' => $port,
        ]);
    }
}
