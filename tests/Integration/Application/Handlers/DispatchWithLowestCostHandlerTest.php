<?php

declare(strict_types=1);

namespace Test\Integration\Application\Handlers;

use CheapDelivery\Application\Commands\DispatchWithLowestCost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Application\Domain\Models\Dispatch\DispatchId;
use CheapDelivery\Application\Domain\Models\Dispatch\Person;
use CheapDelivery\Application\Domain\Models\Dispatch\Product;
use CheapDelivery\Application\Ports\Inbound\DispatchingWithLowestCost;
use PHPUnit\Framework\Attributes\DataProvider;
use Test\Integration\IntegrationTestCase;

final class DispatchWithLowestCostHandlerTest extends IntegrationTestCase
{
    public static function shipmentProvider(): array
    {
        return [
            'Light prize over a long distance goes to DHL'    => [
                'weight'              => 2.16,
                'distance'            => 800.00,
                'expectedCost'        => 96.4,
                'expectedCarrierName' => 'DHL'
            ],
            'Heavy prize over a long distance goes to Loggi'  => [
                'weight'              => 7.50,
                'distance'            => 800.00,
                'expectedCost'        => 70.0,
                'expectedCarrierName' => 'Loggi'
            ]
        ];
    }

    #[DataProvider('shipmentProvider')]
    public function testRecordsTheDispatchAndItsFact(
        float $weight,
        float $distance,
        float $expectedCost,
        string $expectedCarrierName
    ): void {
        /** @Given a dispatch command for the seeded carriers */
        $id = DispatchId::generate();
        $command = new DispatchWithLowestCost(
            id: $id,
            person: Person::from(
                name: Name::from(value: 'Gustavo'),
                distance: Distance::from(value: $distance)
            ),
            product: Product::from(
                name: Name::from(value: 'MacBook Pro'),
                weight: Weight::from(value: $weight)
            )
        );

        /** @When the command is handled */
        $this->get(DispatchingWithLowestCost::class)->handle(command: $command);

        /** @Then the dispatch is recorded against the cheapest carrier */
        self::assertSame(1, $this->fixtures()->dispatchCountOf(carrierName: $expectedCarrierName));

        /** @And the fact is in the outbox with the cost the carrier charged */
        $identifier = $id->identityValue();

        self::assertSame(['DispatchedWithLowestCost'], $this->fixtures()->outboxEventTypesOf(dispatchId: $identifier));

        $payload = $this->fixtures()->outboxPayloadOf(
            eventType: 'DispatchedWithLowestCost',
            dispatchId: $identifier
        );

        self::assertSame(['cost', 'carrier_name'], array_keys($payload));
        self::assertSame($expectedCost, (float)$payload['cost']);
        self::assertSame($expectedCarrierName, $payload['carrier_name']);
    }
}
