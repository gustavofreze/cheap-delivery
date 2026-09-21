<?php

declare(strict_types=1);

namespace Test\Unit\Application\Domain\Models\Dispatch;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Models\Carrier\Carrier;
use CheapDelivery\Application\Domain\Models\Carrier\Carriers;
use CheapDelivery\Application\Domain\Models\Carrier\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\PartialCost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Application\Domain\Models\Dispatch\Dispatch;
use CheapDelivery\Application\Domain\Models\Dispatch\DispatchId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Unit\CarrierFactory;

final class DispatchTest extends TestCase
{
    public static function shipmentProvider(): array
    {
        return [
            'Light prize over a long distance goes to DHL'     => [
                'weight'      => 2.16,
                'distance'    => 800.00,
                'expectedCost' => 96.40,
                'expectedCarrierName' => 'DHL'
            ],
            'Heavy prize over a long distance goes to Loggi'   => [
                'weight'      => 7.50,
                'distance'    => 800.00,
                'expectedCost' => 70.00,
                'expectedCarrierName' => 'Loggi'
            ],
            'Light prize over a short distance goes to FedEx'  => [
                'weight'      => 1.00,
                'distance'    => 10.00,
                'expectedCost' => 5.50,
                'expectedCarrierName' => 'FedEx'
            ]
        ];
    }

    #[DataProvider('shipmentProvider')]
    public function testDispatchesThroughTheCheapestCarrier(
        float $weight,
        float $distance,
        float $expectedCost,
        string $expectedCarrierName
    ): void {
        /** @Given a set of registered carriers */
        $carriers = CarrierFactory::registered();

        /** @When the prize is dispatched */
        $dispatch = Dispatch::dispatchWithLowestCost(
            id: DispatchId::generate(),
            weight: Weight::from(value: $weight),
            carriers: $carriers,
            distance: Distance::from(value: $distance)
        );

        /** @Then the cheapest eligible carrier is chosen */
        self::assertSame($expectedCarrierName, $dispatch->shipment->carrierName->value);
        self::assertSame($expectedCost, $dispatch->shipment->cost->toFloat());
    }

    public function testRecordsTheFactOfTheDispatch(): void
    {
        /** @Given a set of registered carriers */
        $carriers = CarrierFactory::registered();

        /** @When the prize is dispatched */
        $dispatch = Dispatch::dispatchWithLowestCost(
            id: DispatchId::generate(),
            weight: Weight::from(value: 2.16),
            carriers: $carriers,
            distance: Distance::from(value: 800.00)
        );

        /** @Then the dispatch carries the fact it produced */
        $events = $dispatch->peekEvents();

        self::assertSame(1, $events->count());
        self::assertSame('DispatchedWithLowestCost', $events->first()->eventType->value);
    }

    public function testFailsWhenNoCarrierIsRegistered(): void
    {
        /** @Given no carrier is registered */
        $carriers = Carriers::createFromEmpty();

        /** @Then the dispatch is refused */
        $this->expectException(NoCarriersAvailable::class);
        $this->expectExceptionMessage('There are no carriers available for dispatch.');

        /** @When the prize is dispatched */
        Dispatch::dispatchWithLowestCost(
            id: DispatchId::generate(),
            weight: Weight::from(value: 2.16),
            carriers: $carriers,
            distance: Distance::from(value: 800.00)
        );
    }

    public function testFailsWhenNoRegisteredCarrierPricesTheWeight(): void
    {
        /** @Given every registered carrier prices only the heavy band */
        $carriers = Carriers::createFrom(elements: [
            Carrier::from(
                name: Name::from(value: 'Loggi'),
                modality: PartialCost::from(
                    modality: CarrierFactory::priced(rate: 0.01, fixed: 10.00),
                    condition: WeightGreaterThanOrEqual::from(threshold: Weight::from(value: 5.00))
                )
            )
        ]);

        /** @Then the dispatch is refused */
        $this->expectException(NoEligibleCarriers::class);
        $this->expectExceptionMessage('There are no eligible carriers for the dispatch.');

        /** @When a light prize is dispatched */
        Dispatch::dispatchWithLowestCost(
            id: DispatchId::generate(),
            weight: Weight::from(value: 2.16),
            carriers: $carriers,
            distance: Distance::from(value: 800.00)
        );
    }
}
