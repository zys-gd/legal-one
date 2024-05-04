<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountControllerTest extends WebTestCase
{
    public function testWithoutFilters(): void
    {
        $client = static::createClient();
        $client->request('GET', '/count');
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThanOrEqual(0, json_decode($response->getContent(), true)['counter']);
    }

    #[DataProvider(methodName: 'provideWrongQueries')]
    public function testWithWrongDataFilters(string $query): void
    {
        $client = static::createClient();
        $client->request('GET', '/count?' . $query);
        $this->assertResponseStatusCodeSame(400);
    }

    #[DataProvider(methodName: 'provideCorrectQueries')]
    public function testWithCorrectDataFilters(string $query): void
    {
        $client = static::createClient();
        $client->request('GET', '/count?' . $query);
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(200);
        $this->assertGreaterThanOrEqual(0, json_decode($response->getContent(), true)['counter']);
    }

    public static function provideWrongQueries(): Generator
    {
        yield 'statusCode' => ['statusCode=qwe'];
        yield 'startDate' => ['startDate=qwe'];
        yield 'endDate' => ['endDate=qwe'];
        yield 'serviceNames' => ['serviceNames=USER-SERVICE'];
    }

    public static function provideCorrectQueries(): Generator
    {
        yield 'statusCode' => ['statusCode=201'];
        yield 'startDate' => ['startDate=2018-08-17'];
        yield 'endDate' => ['endDate=2018-08-18'];
        yield 'serviceNames' => ['serviceNames[]=USER-SERVICE'];
    }
}
