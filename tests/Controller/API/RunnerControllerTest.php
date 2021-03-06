<?php

namespace App\Tests\Controller\API;

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

        $this->assertEquals(50, count(json_decode($response->getContent())->runners));
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

    public function testNewRunnersBatch()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/runner/batch',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            file_get_contents(__DIR__ . '/../../mocks/runners.json')
        );

        $response = $client->getResponse();

        $runnersData = json_decode($response->getContent())->runners;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($runnersData);

        $this->assertCount(5, $runnersData);
    }

    public function testNotCreateRunnerWithMissingInfo()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/runner',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'name' => 'John Doe',
                'cpf' => '',
                'birthdate' => '1994-07-03'
            ])
        );

        $response = $client->getResponse();

        $responseContent = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('error', $responseContent->status);
        $this->assertNotEmpty($responseContent->errors);
        $this->assertCount(2,$responseContent->errors);
    }

    public function testNewRunnerCannotHaveLessThan18Years()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/runner',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'name' => 'John Doe',
                'cpf' => '79396699071',
                'birthdate' => '2005-07-03'
            ])
        );

        $response = $client->getResponse();

        $responseContent = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('error', $responseContent->status);
        $this->assertNotEmpty($responseContent->errors);
    }
}