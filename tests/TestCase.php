<?php

namespace League\StatsD\Test;

use League\StatsD\Client;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->client->configure();
    }
}
