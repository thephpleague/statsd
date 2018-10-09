<?php

namespace League\StatsD\Test;

class TagsTest extends TestCase
{
    public function testGeneralTags()
    {
        $this->client->configure(['tags' => ['general_tag' => 'general_value']]);
        $this->client->gauge('test_metric', 123, ['test_tag' => 'test_value']);
        $this->assertEquals(
            'test_metric:123|g|#general_tag:general_value,test_tag:test_value',
            $this->client->getLastMessage()
        );
    }

    public function testCounter()
    {
        $this->client->increment('test_metric', 456, 1, ['test_tag' => 'test_value']);
        $this->assertEquals('test_metric:456|c|#test_tag:test_value', $this->client->getLastMessage());
    }

    public function testGauge()
    {
        $this->client->gauge('test_metric', 456, ['test_tag' => 'test_value']);
        $this->assertEquals('test_metric:456|g|#test_tag:test_value', $this->client->getLastMessage());
    }

    public function testTimer()
    {
        $this->client->timing('test_metric', 456, ['test_tag' => 'test_value']);
        $this->assertEquals('test_metric:456|ms|#test_tag:test_value', $this->client->getLastMessage());
    }

    public function testSet()
    {
        $this->client->set('test_metric', 456, ['test_tag' => 'test_value']);
        $this->assertEquals('test_metric:456|s|#test_tag:test_value', $this->client->getLastMessage());
    }
}
