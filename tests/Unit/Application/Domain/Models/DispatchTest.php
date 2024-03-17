<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Factories\Carriers;
use PHPUnit\Framework\TestCase;

class DispatchTest extends TestCase
{
    /**
     * @param Weight $weight
     * @param Distance $distance
     * @param Shipment $expected
     * @return void
     * @dataProvider weightAndDistanceProvider
     */
    public function testCalculateTheDispatchWithLowestCost(Weight $weight, Distance $distance, Shipment $expected): void
    {
        /** @Given I have a set of carriers */
        $carriers = Carriers::available();

        /** @And I have a dispatch created */
        $dispatch = Dispatch::create();

        /** @When I calculate the dispatch with the lowest cost */
        $actual = $dispatch->dispatchWithLowestCost(weight: $weight, distance: $distance, carriers: $carriers);

        /** @Then the value should be the same as expected */
        self::assertEquals($expected->cost, $actual->shipment?->cost);
        self::assertEquals($expected->carrierName, $actual->shipment?->carrierName);
        self::assertNotEmpty($actual->id->getValue());
    }

    public function testExceptionWhenNoCarriersAvailable(): void
    {
        /** @Given I don't have a set of carriers */
        $carriers = Carriers::unavailable();

        /** @Then an exception indicating that there are no carriers available must be thrown */
        $this->expectException(NoCarriersAvailable::class);
        $this->expectExceptionMessage('There are no carriers available for dispatch.');

        /** @When I calculate the dispatch with the lowest cost */
        Dispatch::create()->dispatchWithLowestCost(
            weight: new Weight(value: rand(1, 10)),
            distance: new Distance(value: rand(1, 10)),
            carriers: $carriers
        );
    }

    public function testExceptionWhenNoEligibleCarriers(): void
    {
        /** @Given I have a set of carriers */
        $carriers = Carriers::noEligible();

        /** @And I have a dispatch created */
        $dispatch = Dispatch::create();

        /** @Then an exception indicating that there are no eligible carriers must be thrown */
        $this->expectException(NoEligibleCarriers::class);
        $this->expectExceptionMessage('There are no eligible carriers for the dispatch.');

        /** @When I calculate the dispatch with the lowest cost */
        $dispatch->dispatchWithLowestCost(
            weight: new Weight(value: 60.00),
            distance: new Distance(value: 100.00),
            carriers: $carriers
        );
    }

    public static function weightAndDistanceProvider(): array
    {
        return [
            [
                'weight'   => new Weight(value: 1.00),
                'distance' => new Distance(value: 1.00),
                'expected' => Shipment::from(cost: new Cost(value: 2.22), carrierName: new Name(value: 'Loggi'))
            ],
            [
                'weight'   => new Weight(value: 4.00),
                'distance' => new Distance(value: 1000.00),
                'expected' => Shipment::from(cost: new Cost(value: 210.00), carrierName: new Name(value: 'DHL'))
            ],
            [
                'weight'   => new Weight(value: 9.50),
                'distance' => new Distance(value: 5.00),
                'expected' => Shipment::from(cost: new Cost(value: 10.00), carrierName: new Name(value: 'FedEx'))
            ]
        ];
    }
}
