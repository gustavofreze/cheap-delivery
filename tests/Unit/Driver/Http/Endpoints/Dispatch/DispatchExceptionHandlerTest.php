<?php

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Driver\Http\Endpoints\Dispatch\Mocks\DispatchWithLowestCostHandlerMock;
use CheapDelivery\Driver\Http\Endpoints\Dispatch\Mocks\Exceptions;
use CheapDelivery\Driver\Http\Middlewares\ErrorHandling;
use CheapDelivery\Factories\Request;
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
                'name'   => 'MacBook Pro',
                'weight' => 2.16
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
                'name'   => 'MacBook Pro',
                'weight' => 2.16
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

    public function testExceptionWhenDistanceOutOfRange(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
        $payload = [
            'person'  => [
                'name'     => 'Gustavo',
                'distance' => Exceptions::DISTANCE_OUT_OF_RANGE
            ],
            'product' => [
                'name'   => 'MacBook Pro',
                'weight' => 2.16
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I process the request with this handler */
        $actual = $this->middleware->process(request: $request, handler: $this->endpoint);

        /** @Then the response should be an error indicating distance out of range */
        $expected = ['error' => 'Distance is out of range. Current <50000.00>, Maximum <20000.00>.'];

        self::assertEquals(HttpCode::UNPROCESSABLE_ENTITY->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }

    public function testExceptionWhenWeightOutOfRange(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
        $payload = [
            'person'  => [
                'name'     => 'Gustavo',
                'distance' => 550.00
            ],
            'product' => [
                'name'   => 'MacBook Pro',
                'weight' => 2000.00
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I process the request with this handler */
        $actual = $this->middleware->process(request: $request, handler: $this->endpoint);

        /** @Then the response should be an error indicating weight out of range */
        $expected = ['error' => 'Weight is out of range. Current <2000.00>, Maximum <1000.00>.'];

        self::assertEquals(HttpCode::UNPROCESSABLE_ENTITY->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }

    public function testExceptionWhenNonPositiveValue(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
        $payload = [
            'person'  => [
                'name'     => 'Gustavo',
                'distance' => 150.25
            ],
            'product' => [
                'name'   => 'MacBook Pro',
                'weight' => 0
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I process the request with this handler */
        $actual = $this->middleware->process(request: $request, handler: $this->endpoint);

        /** @Then the response should be an error indicating weight cannot be zero or negative */
        $expected = ['error' => 'Weight cannot be zero or negative. Invalid value <0>.'];

        self::assertEquals(HttpCode::UNPROCESSABLE_ENTITY->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }

    public function testExceptionWhenEmptyName(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
        $payload = [
            'person'  => [
                'name'     => '',
                'distance' => 800.00
            ],
            'product' => [
                'name'   => 'MacBook Pro',
                'weight' => 2.16
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I process the request with this handler */
        $actual = $this->middleware->process(request: $request, handler: $this->endpoint);

        /** @Then the response should be an error indicating empty name */
        $expected = ['error' => 'Name cannot be empty.'];

        self::assertEquals(HttpCode::UNPROCESSABLE_ENTITY->value, $actual->getStatusCode());
        self::assertEquals(json_encode($expected), $actual->getBody()->__toString());
    }

    public function testExceptionWhenNameTooLong(): void
    {
        /** @Given that I have the data to calculate the dispatch with the lowest cost */
        $payload = [
            'person'  => [
                'name'     => str_repeat('x', 256),
                'distance' => 800.00
            ],
            'product' => [
                'name'   => 'MacBook Pro',
                'weight' => 2.16
            ]
        ];

        /** @And that I use this data in the request */
        $request = Request::postFrom(payload: $payload);

        /** @When I process the request with this handler */
        $actual = $this->middleware->process(request: $request, handler: $this->endpoint);

        /** @Then the response should be an error indicating name is too long */
        $expected = ['error' => 'Name is too long. Current <256> characters, Maximum <255> characters.'];

        self::assertEquals(HttpCode::UNPROCESSABLE_ENTITY->value, $actual->getStatusCode());
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
                'name'   => 'MacBook Pro',
                'weight' => 2.16
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
