<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Driver\Http\Endpoints\Dispatch\Mocks\DispatchWithLowestCostHandlerMock;
use CheapDelivery\Factories\Request;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Http\Code;

class DispatchWithLowestCostTest extends TestCase
{
    private DispatchWithLowestCost $endpoint;

    private DispatchWithLowestCostHandlerMock $useCase;

    protected function setUp(): void
    {
        $this->useCase = new DispatchWithLowestCostHandlerMock();
        $this->endpoint = new DispatchWithLowestCost(useCase: $this->useCase);
    }

    public function testDispatchWithLowestCost(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
        $payload = [
            'person'  => [
                'name'     => 'Gustavo',
                'distance' => 100.0
            ],
            'product' => [
                'name'   => 'Notebook',
                'weight' => 1.0
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I execute the request */
        $actual = $this->endpoint->handle(request: $request);

        /** @Then the request should be successful */
        self::assertEquals(Code::NO_CONTENT->value, $actual->getStatusCode());

        /** @And a command should be registered */
        $person = $this->useCase->lastCommand->person;
        $product = $this->useCase->lastCommand->product;

        self::assertEquals($payload['person']['name'], $person->name->value);
        self::assertEquals($payload['person']['distance'], $person->distance->value);
        self::assertEquals($payload['product']['name'], $product->name->value);
        self::assertEquals($payload['product']['weight'], $product->weight->value);
    }
}
