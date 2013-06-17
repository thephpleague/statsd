<?php

namespace StatsD\Test;
use PHPUnit_Framework_TestCase;
use StatsD;

class TestCase extends PHPUnit_Framework_TestCase
{

    protected $client;

    public function setUp()
    {

        $this->client = new StatsD\Client();
        $this->client->configure();

    }

}
