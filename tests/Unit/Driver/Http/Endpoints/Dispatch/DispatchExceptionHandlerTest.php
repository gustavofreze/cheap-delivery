<?php

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Driver\Http\Endpoints\Dispatch\Factories\Request;
use CheapDelivery\Driver\Http\Endpoints\Dispatch\Mocks\DispatchWithLowestCostHandlerMock;
use CheapDelivery\Driver\Http\Endpoints\Dispatch\Mocks\Exceptions;
use CheapDelivery\Driver\Http\Middlewares\ErrorHandling;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Http\HttpCode;

class DispatchExceptionHandlerTest extends TestCase
{
    private DispatchWithLowestCost $endpoint;

    private ErrorHandling $middleware;

    protected function setUp(): void
    {
        $useCase = new DispatchWithLowestCostHandlerMock();
        $this->endpoint = new DispatchWithLowestCost(useCase: $useCase);
        $exceptionHandler = new DispatchExceptionHandler();
        $this->middleware = new ErrorHandling(exceptionHandler: $exceptionHandler);
    }

    public function testExceptionWhenNoEligibleCarriers(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
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
        $expected = ['error' => 'There are no eligible carriers for the dispatch.'];

        self::assertEquals(HttpCode::BAD_REQUEST->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }

    public function testExceptionWhenNoCarriersAvailable(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
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
        $expected = ['error' => 'There are no carriers available for dispatch.'];

        self::assertEquals(HttpCode::NOT_FOUND->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }

    public function testExceptionWhenUnknownError(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
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
