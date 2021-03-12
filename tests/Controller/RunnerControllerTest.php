<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RunnerControllerTest extends WebTestCase
{
    private $client;

    public function testIndexRunners()
    {
        $client = static::createClient();

        $client->request('GET', 'api/runner');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $this->assertEquals(0, count(json_decode($response->getContent())->runners));
    }

    public function testNewRunner()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/runner',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_REFERER' => '/foo/bar',
            ],
            json_encode([
                'name' => 'John Doe',
                'cpf' => '79396699071',
                'birthdate' => '1994-07-03'
            ])
        );

        $response = $client->getResponse();

        $runnerData = json_decode($response->getContent())->runner;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($runnerData);

        $this->assertIsNumeric($runnerData->id, "Runner ID needs to be numeric");
    }
}