<?php

declare(strict_types=1);

namespace Test\Integration\Driven\Dispatch\Repository;

use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Application\Domain\Models\Dispatch\Dispatch;
use CheapDelivery\Application\Domain\Models\Dispatch\DispatchId;
use CheapDelivery\Application\Ports\Outbound\Dispatches;
use Test\Integration\IntegrationTestCase;
use Test\Unit\CarrierFactory;

final class DispatchRepositoryTest extends IntegrationTestCase
{
    public function testDrainsTheEventsItHandedToTheOutbox(): void
    {
        /** @Given a decided dispatch carrying its fact */
        $dispatch = Dispatch::dispatchWithLowestCost(
            id: DispatchId::generate(),
            weight: Weight::from(value: 2.16),
            carriers: CarrierFactory::registered(),
            distance: Distance::from(value: 800.00)
        );

        self::assertSame(1, $dispatch->peekEvents()->count());

        /** @When it is saved */
        $this->get(Dispatches::class)->save(dispatch: $dispatch);

        /** @Then the fact reached the outbox and the aggregate no longer carries it */
        self::assertSame(
            ['DispatchedWithLowestCost'],
            $this->fixtures()->outboxEventTypesOf(dispatchId: $dispatch->id->identityValue())
        );
        self::assertSame(0, $dispatch->peekEvents()->count());
    }
}
