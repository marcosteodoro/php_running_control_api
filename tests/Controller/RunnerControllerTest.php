<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RunnerControllerTest extends WebTestCase
{
    public function testIndexRunners()
    {
        $client = static::createClient();

        $client->request('GET', 'api/runner');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $this->assertEquals(0, count(json_decode($response->getContent())->runners));
    }
}