<?php

namespace Test\Integration\Query\Dispatch;

use CheapDelivery\Query\Dispatch\Resource;
use Test\Integration\IntegrationTestCapabilities;
use Test\Integration\Query\Dispatch\Factories\IsoDate;
use Test\Integration\Query\Dispatch\Factories\QueryAdapter;
use Test\Integration\Query\Dispatch\Factories\Request;

class ResourceTest extends IntegrationTestCapabilities
{
    private Resource $resource;

    private QueryAdapter $query;

    protected function setUp(): void
    {
        $this->query = new QueryAdapter(self::$container);
        $this->resource = self::$container->get(Resource::class);
    }

    protected function tearDown(): void
    {
        $this->query->rollBack();
    }

    public function testFindDispatchesWithoutFilters(): void
    {
        /** @Given I have some dispatches */
        $dispatches = [
            [
                'id'        => '03ec1cfc-5546-45ae-925d-eb36211965d9',
                'cost'      => floatval(rand(100, 150)),
                'carrier'   => [
                    'name' => 'DHL'
                ],
                'createdAt' => IsoDate::now()->toString()
            ],
            [
                'id'        => '9a33a5f5-3d4b-4777-afa7-fbdadd9620d3',
                'cost'      => floatval(rand(100, 150)),
                'carrier'   => [
                    'name' => 'FedEx'
                ],
                'createdAt' => IsoDate::now()->subtract(duration: 'PT10M')->toString()
            ]
        ];

        /** @And these dispatches are persisted */
        $this->query->saveDispatches(dispatches: $dispatches);

        /** @And I have a request to fetch the persisted dispatches without filters */
        $request = Request::getFrom(parameters: []);

        /** @When I make the request */
        $response = $this->resource->handle(request: $request);

        /** @Then the dispatches should be returned */
        $actual = json_decode($response->getBody()->__toString(), true);

        self::assertCount(2, $actual);
        self::assertEquals($dispatches, $actual);
    }

    public function testFindDispatchesWithFilters(): void
    {
        /** @Given I have some dispatches */
        $dispatches = [
            [
                'id'        => 'ca83b7f1-4d54-4447-85a1-6cfb59122899',
                'cost'      => floatval(rand(100, 150)),
                'carrier'   => [
                    'name' => 'DHL'
                ],
                'createdAt' => IsoDate::now()->toString()
            ],
            [
                'id'        => '73440f3c-2d29-42dd-9094-130d04e98712',
                'cost'      => floatval(rand(100, 150)),
                'carrier'   => [
                    'name' => 'FedEx'
                ],
                'createdAt' => IsoDate::now()->toString()
            ]
        ];

        /** @And these dispatches are persisted */
        $this->query->saveDispatches(dispatches: $dispatches);

        /** @And I have a request to fetch the persisted dispatches with filters */
        $request = Request::getFrom(parameters: ['carrierName' => 'DHL']);

        /** @When I make the request */
        $response = $this->resource->handle(request: $request);

        /** @Then the dispatches should be returned */
        $actual = json_decode($response->getBody()->__toString(), true);

        self::assertCount(1, $actual);
        self::assertEquals($dispatches[0]['id'], $actual[0]['id']);
        self::assertEquals($dispatches[0]['cost'], $actual[0]['cost']);
        self::assertEquals($dispatches[0]['carrier'], $actual[0]['carrier']);
        self::assertEquals($dispatches[0]['createdAt'], $actual[0]['createdAt']);
    }
}
