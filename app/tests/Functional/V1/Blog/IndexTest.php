<?php

declare(strict_types=1);

namespace Test\Functional\V1\Blog;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class IndexTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            IndexFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/blog'));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals([
            'items' => [
                [
                    'slug' => 'dolor sit amet',
                    'date' => '2024-01-26T08:53:33+00:00',
                    'title' => 'Dolor sit amet',
                    'short' => '<p>Dolor sit amet, consectetur adipiscing elit</p>',
                ],
                [
                    'slug' => 'lorem-ipsum',
                    'date' => '2023-12-26T08:53:33+00:00',
                    'title' => 'Lorem ipsum',
                    'short' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>',
                ],
            ],
            'pagination' => [
                'count' => 2,
                'total' => 2,
                'per_page' => 20,
                'page' => 1,
                'pages' => 1,
            ],
        ], Json::decode((string)$response->getBody()));
    }
}
