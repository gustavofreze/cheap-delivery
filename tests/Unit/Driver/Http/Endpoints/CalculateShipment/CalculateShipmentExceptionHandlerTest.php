<?php

namespace CheapDelivery\Driver\Http\Endpoints\CalculateShipment;

use CheapDelivery\Driver\Http\Endpoints\CalculateShipment\Factories\Request;
use CheapDelivery\Driver\Http\Endpoints\CalculateShipment\Mocks\CalculateShipmentHandlerMock;
use CheapDelivery\Driver\Http\Endpoints\CalculateShipment\Mocks\Exceptions;
use CheapDelivery\Driver\Http\Middlewares\ErrorHandling;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Http\HttpCode;

class CalculateShipmentExceptionHandlerTest extends TestCase
{
    private CalculateShipment $endpoint;

    private ErrorHandling $middleware;

    protected function setUp(): void
    {
        $useCase = new CalculateShipmentHandlerMock();
        $this->endpoint = new CalculateShipment(useCase: $useCase);
        $exceptionHandler = new CalculateShipmentExceptionHandler();
        $this->middleware = new ErrorHandling(exceptionHandler: $exceptionHandler);
    }

    public function testExceptionWhenNoEligibleCarriers(): void
    {
        /** @Given that I have data to calculate a shipment */
        $payload = [
            'person'  => [
                'name'     => 'Gustavo',
                'distance' => Exceptions::NO_ELIGIBLE_CARRIERS
            ],
            'product' => [
                'name'   => 'Notebook',
                'weight' => 1.0
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I process the request with this handler */
        $actual = $this->middleware->process(request: $request, handler: $this->endpoint);

        /** @Then the response should be an error indicating no eligible carriers */
        $expected = ['error' => 'There are no eligible carriers for the shipment.'];

        self::assertEquals(HttpCode::BAD_REQUEST->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }

    public function testExceptionWhenNoCarriersAvailable(): void
    {
        /** @Given that I have data to calculate a shipment */
        $payload = [
            'person'  => [
                'name'     => 'Gustavo',
                'distance' => Exceptions::NO_CARRIERS_AVAILABLE
            ],
            'product' => [
                'name'   => 'Notebook',
                'weight' => 1.0
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I process the request with this handler */
        $actual = $this->middleware->process(request: $request, handler: $this->endpoint);

        /** @Then the response should be an error indicating no carriers available */
        $expected = ['error' => 'No carriers available for shipment.'];

        self::assertEquals(HttpCode::NOT_FOUND->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }

    public function testExceptionWhenUnknownError(): void
    {
        /** @Given that I have data to calculate a shipment */
        $payload = [
            'person'  => [
                'name'     => 'Gustavo',
                'distance' => Exceptions::UNKNOWN_ERROR
            ],
            'product' => [
                'name'   => 'Notebook',
                'weight' => 1.0
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I process the request with this handler */
        $actual = $this->middleware->process(request: $request, handler: $this->endpoint);

        /** @Then the response should be an error indicating error */
        $expected = ['error' => 'Any error.'];

        self::assertEquals(HttpCode::INTERNAL_SERVER_ERROR->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }
}
