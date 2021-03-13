<?php

namespace App\Tests\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RaceRunnerControllerTest extends WebTestCase
{
    public function testAddRunnerToARace()
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

        $newRaceData = json_decode($client->getResponse()->getContent());

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

        $newRunnerData = json_decode($client->getResponse()->getContent());

        $client->request('POST', '/api/race/' . $newRaceData->race->id . '/runner/add/' . $newRunnerData->runner->id);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((json_decode($response->getContent())->race->runners));
    }
    
    public function testAddResult()
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

        $newRaceData = json_decode($client->getResponse()->getContent());

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

        $newRunnerData = json_decode($client->getResponse()->getContent());

        $client->request('POST', '/api/race/' . $newRaceData->race->id . '/runner/add/' . $newRunnerData->runner->id);
        
        $client->request(
            'POST', 
            '/api/race/' . $newRaceData->race->id . '/runner/' . $newRunnerData->runner->id . '/result/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'race_id' => $newRaceData->race->id,
                'runner_id' => $newRunnerData->runner->id,
                'startTime' => '00:00:00',
                'finishTime' => '00:03:59'
            ])
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((json_decode($response->getContent())->result));
    }
}