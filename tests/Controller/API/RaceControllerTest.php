<?php

namespace App\Tests\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RaceControllerTest extends WebTestCase
{
    public function testIndexRace()
    {
        $client = static::createClient();

        $client->request('GET', 'api/race');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $this->assertEquals(10, count(json_decode($response->getContent())->races));
    }

    public function testNewRace()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/race',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'type' => 3,
                'date' => '2021-03-27'
            ])
        );

        $response = $client->getResponse();

        $raceData = json_decode($response->getContent())->race;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($raceData);

        $this->assertIsNumeric($raceData->id, "Race ID needs to be numeric");
    }

    public function testNotCreateRaceWithInvalidType()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/race',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'type' => 300,
                'date' => '2021-03-27'
            ])
        );

        $response = $client->getResponse();

        $responseContent = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('error', $responseContent->status);
        $this->assertNotEmpty($responseContent->errors);
        $this->assertCount(1, $responseContent->errors);
    }
}