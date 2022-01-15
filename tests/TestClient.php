<?php

namespace League\StatsD\Test;

class TestClient extends \League\StatsD\Client
{
    public function getTimeout()
    {
        return $this->timeout;
    }
}
