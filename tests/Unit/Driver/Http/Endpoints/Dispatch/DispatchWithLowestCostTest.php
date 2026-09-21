<?php

declare(strict_types=1);

namespace Test\Unit\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Driver\Http\Endpoints\Dispatch\DispatchWithLowestCost;
use CheapDelivery\Driver\Http\InvalidRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Unit\RequestFactory;
use TinyBlocks\Http\Code;

final class DispatchWithLowestCostTest extends TestCase
{
    public static function invalidPayloadProvider(): array
    {
        return [
            'Missing person'     => [
                'payload' => ['product' => ['name' => 'MacBook Pro', 'weight' => 2.16]]
            ],
            'Missing product'    => [
                'payload' => ['person' => ['name' => 'Gustavo', 'distance' => 800.0]]
            ],
            'Empty holder name'  => [
                'payload' => [
                    'person'  => ['name' => '', 'distance' => 800.0],
                    'product' => ['name' => 'MacBook Pro', 'weight' => 2.16]
                ]
            ],
            'Negative distance'  => [
                'payload' => [
                    'person'  => ['name' => 'Gustavo', 'distance' => -1.0],
                    'product' => ['name' => 'MacBook Pro', 'weight' => 2.16]
                ]
            ],
            'Holder name too long' => [
                'payload' => [
                    'person'  => ['name' => str_repeat('a', 256), 'distance' => 800.0],
                    'product' => ['name' => 'MacBook Pro', 'weight' => 2.16]
                ]
            ]
        ];
    }

    public static function boundaryNameProvider(): array
    {
        return [
            'Shortest accepted name' => ['name' => 'a'],
            'Longest accepted name'  => ['name' => str_repeat('a', 255)]
        ];
    }

    #[DataProvider('boundaryNameProvider')]
    public function testAcceptsANameAtTheBoundary(string $name): void
    {
        /** @Given a dispatch request whose holder name sits at a length boundary */
        $spy = new DispatchingWithLowestCostSpy();
        $endpoint = new DispatchWithLowestCost(dispatching: $spy);

        $request = RequestFactory::postFrom(payload: [
            'person'  => ['name' => $name, 'distance' => 800.0],
            'product' => ['name' => 'MacBook Pro', 'weight' => 2.16]
        ]);

        /** @When the request is handled */
        $actual = $endpoint->handle($request);

        /** @Then the request is accepted */
        self::assertSame(Code::CREATED->value, $actual->getStatusCode());
        self::assertSame($name, $spy->received->person->name->value);
    }

    public function testAnswersWithTheDispatchIdentifier(): void
    {
        /** @Given a valid dispatch request */
        $spy = new DispatchingWithLowestCostSpy();
        $endpoint = new DispatchWithLowestCost(dispatching: $spy);

        $request = RequestFactory::postFrom(payload: [
            'person'  => ['name' => 'Gustavo', 'distance' => 800.0],
            'product' => ['name' => 'MacBook Pro', 'weight' => 2.16]
        ]);

        /** @When the request is handled */
        $actual = $endpoint->handle($request);

        /** @Then the dispatch is created and its identifier answered */
        self::assertSame(Code::CREATED->value, $actual->getStatusCode());
        self::assertSame(
            ['id' => $spy->received->id->identityValue()],
            (array)json_decode($actual->getBody()->__toString(), true)
        );
    }

    public function testHandsTheCommandToTheApplication(): void
    {
        /** @Given a valid dispatch request */
        $spy = new DispatchingWithLowestCostSpy();
        $endpoint = new DispatchWithLowestCost(dispatching: $spy);

        $request = RequestFactory::postFrom(payload: [
            'person'  => ['name' => 'Gustavo', 'distance' => 800.0],
            'product' => ['name' => 'MacBook Pro', 'weight' => 2.16]
        ]);

        /** @When the request is handled */
        $endpoint->handle($request);

        /** @Then the command carries what the payload declared */
        self::assertSame('Gustavo', $spy->received->person->name->value);
        self::assertSame(800.00, $spy->received->person->distance->toFloat());
        self::assertSame('MacBook Pro', $spy->received->product->name->value);
        self::assertSame(2.16, $spy->received->product->weight->toFloat());
    }

    #[DataProvider('invalidPayloadProvider')]
    public function testRefusesAnInvalidPayload(array $payload): void
    {
        /** @Given an invalid dispatch request */
        $endpoint = new DispatchWithLowestCost(dispatching: new DispatchingWithLowestCostSpy());

        /** @Then the request is refused */
        $this->expectException(InvalidRequest::class);

        /** @When the request is handled */
        $endpoint->handle(RequestFactory::postFrom(payload: $payload));
    }

    public function testPropagatesTheApplicationFailure(): void
    {
        /** @Given an application that refuses the dispatch */
        $spy = new DispatchingWithLowestCostSpy(failure: new NoCarriersAvailable());
        $endpoint = new DispatchWithLowestCost(dispatching: $spy);

        $request = RequestFactory::postFrom(payload: [
            'person'  => ['name' => 'Gustavo', 'distance' => 800.0],
            'product' => ['name' => 'MacBook Pro', 'weight' => 2.16]
        ]);

        /** @Then the failure reaches the error boundary */
        $this->expectException(NoCarriersAvailable::class);

        /** @When the request is handled */
        $endpoint->handle($request);
    }
}
