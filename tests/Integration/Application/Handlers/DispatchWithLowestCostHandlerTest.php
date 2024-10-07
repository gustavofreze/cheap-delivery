<?php

declare(strict_types=1);

namespace Test\Integration\Application\Handlers;

use CheapDelivery\Application\Commands\DispatchWithLowestCost;
use CheapDelivery\Application\Domain\Models\Distance;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Application\Domain\Models\Person;
use CheapDelivery\Application\Domain\Models\Product;
use CheapDelivery\Application\Domain\Models\Weight;
use CheapDelivery\Application\Handlers\DispatchWithLowestCostHandler;
use Test\Integration\Application\Handlers\Factories\QueryAdapter;
use Test\Integration\IntegrationTestCapabilities;

class DispatchWithLowestCostHandlerTest extends IntegrationTestCapabilities
{
    private DispatchWithLowestCostHandler $handler;

    private QueryAdapter $query;

    protected function setUp(): void
    {
        $this->query = new QueryAdapter(self::$container);
        $this->handler = self::$container->get(DispatchWithLowestCostHandler::class);
    }

    protected function tearDown(): void
    {
        $this->query->rollBack();
    }

    public function testDispatchWithLowestCost(): void
    {
        /** @Given that I have a command to dispatch with the lowest cost */
        $command = new DispatchWithLowestCost(
            person: new Person(name: new Name(value: 'Gustavo'), distance: new Distance(value: 800.0)),
            product: new Product(name: new Name(value: 'MacBook Pro'), weight: new Weight(value: 2.16))
        );

        /** @When I request that this command be executed */
        $this->handler->handle(command: $command);

        /** @Then the dispatch should be persisted */
        $actual = $this->query->findLastDispatch();

        self::assertSame(96.4, $actual->shipment?->cost->value);
        self::assertSame('DHL', $actual->shipment?->carrierName->value);

        /** @And a new event will be inserted into the outbox table */
        $event = $this->query->findEventBy(aggregateId: $actual->id, eventType: 'DispatchedWithLowestCost');

        self::assertSame(1, $event->revision());
        self::assertEquals($actual, $event->dispatch);
    }
}
