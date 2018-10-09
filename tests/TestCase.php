<?php

namespace League\StatsD\Test;
use League\StatsD\Client;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var Client */
    protected $client;

    public function setUp()
    {

        $this->client = new Client();
        $this->client->configure();

    }

}
