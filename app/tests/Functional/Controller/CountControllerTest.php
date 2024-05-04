<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/count');
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThanOrEqual(0, json_decode($response->getContent(), true)['counter']);
    }
}
