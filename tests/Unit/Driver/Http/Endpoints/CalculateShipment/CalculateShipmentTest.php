<?php

namespace CheapDelivery\Driver\Http\Endpoints\CalculateShipment;

use CheapDelivery\Driver\Http\Endpoints\CalculateShipment\Factories\Request;
use CheapDelivery\Driver\Http\Endpoints\CalculateShipment\Mocks\CalculateShipmentHandlerMock;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Http\HttpCode;

class CalculateShipmentTest extends TestCase
{
    private CalculateShipment $endpoint;

    private CalculateShipmentHandlerMock $useCase;

    protected function setUp(): void
    {
        $this->useCase = new CalculateShipmentHandlerMock();
        $this->endpoint = new CalculateShipment(useCase: $this->useCase);
    }

    public function testCalculateShipment(): void
    {
        /** @Given that I have data to calculate a shipment */
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
        self::assertEquals(HttpCode::NO_CONTENT->value, $actual->getStatusCode());

        /** @And a command should be registered */
        $person = $this->useCase->lastCommand->person;
        $product = $this->useCase->lastCommand->product;

        self::assertEquals($payload['person']['name'], $person->name->value);
        self::assertEquals($payload['person']['distance'], $person->distance->value);
        self::assertEquals($payload['product']['name'], $product->name->value);
        self::assertEquals($payload['product']['weight'], $product->weight->value);
    }
}
