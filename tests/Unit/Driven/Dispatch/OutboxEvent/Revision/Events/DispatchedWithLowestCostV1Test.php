<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Events;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Application\Domain\Models\Commons\Utc;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;
use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Dispatch;
use CheapDelivery\Application\Domain\Models\DispatchId;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Application\Domain\Models\Shipment;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

class DispatchedWithLowestCostV1Test extends TestCase
{
    public function testBuildReturnsEventRecord(): void
    {
        /** @Given I have a dispatch */
        $dispatch = new Dispatch(
            id: new DispatchId(value: Uuid::generateV4()),
            shipment: Shipment::from(
                cost: new Cost(value: 10.99),
                carrierName: new Name(value: 'FedEx')
            )
        );

        /** @And I have an event created for this dispatch */
        $instant = Utc::now();
        $event = new DispatchedWithLowestCost(id: $dispatch->id, dispatch: $dispatch, instant: $instant);

        /** @When I build the event record from this event in version 01 */
        $actual = (new DispatchedWithLowestCostV1(event: $event))->build();

        /** @Then the data should be equal to the expected */
        $expectedPayload = json_encode([
            'id'       => $dispatch->id->value->toString(),
            'instant'  => $instant->dateTime->format(DateTimeInterface::RFC3339),
            'dispatch' => [
                'id'       => $dispatch->id->value->toString(),
                'shipment' => [
                    'cost'        => $dispatch->shipment?->cost->value,
                    'carrierName' => $dispatch->shipment?->carrierName->value
                ]
            ]
        ], JSON_PRESERVE_ZERO_FRACTION);
        self::assertSame(1, $actual->revision->value);
        self::assertSame('Dispatch', $actual->aggregateType->value);
        self::assertSame($expectedPayload, $actual->payload->toJson());
        self::assertSame($dispatch->id->getValue(), $actual->aggregateId->getValue());
        self::assertInstanceOf(EventRecord::class, $actual);
    }
}
