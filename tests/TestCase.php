<?php

namespace League\StatsD\Test;
use PHPUnit_Framework_TestCase;
use League\StatsD\Client;

class TestCase extends PHPUnit_Framework_TestCase
{

    protected $client;

    public function setUp()
    {

        $this->client = new Client();
        $this->client->configure();

    }

}
