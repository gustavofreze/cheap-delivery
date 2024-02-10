<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Factories\Carriers;
use PHPUnit\Framework\TestCase;

class ShipmentsTest extends TestCase
{
    /**
     * @param Weight $weight
     * @param Distance $distance
     * @param Shipment $expected
     * @return void
     * @dataProvider weightAndDistanceProvider
     */
    public function testCalculateTheLowestShippingCost(Weight $weight, Distance $distance, Shipment $expected): void
    {
        /** @Given I have a set of carriers */
        $carriers = Carriers::available();

        /** @And the shipments are created */
        $shipments = Shipments::from(weight: $weight, distance: $distance, carriers: $carriers);

        /** @When calculating the lowest cost */
        $actual = $shipments->lowestCost();

        /** @Then the value should be the same as expected */
        self::assertEquals($expected->cost, $actual->cost);
        self::assertEquals($expected->carrierName, $actual->carrierName);
    }

    /**
     * @return void
     */
    public function testExceptionWhenNoCarriersAvailable(): void
    {
        /** @Given I don't have a set of carriers */
        $carriers = Carriers::unavailable();

        /** @Then an exception indicating that there are no carriers available must be thrown */
        $this->expectException(NoCarriersAvailable::class);
        $this->expectExceptionMessage('No carriers available for shipment.');

        /** @Then I create the shipments */
        Shipments::from(
            weight: new Weight(value: rand(1, 10)),
            distance: new Distance(value: rand(1, 10)),
            carriers: $carriers
        );
    }

    /**
     * @return void
     */
    public function testExceptionWhenNoEligibleCarriers(): void
    {
        /** @Given I have a set of carriers */
        $carriers = Carriers::noEligible();

        /** @And the shipments are created */
        $shipments = Shipments::from(
            weight: new Weight(value: 60.00),
            distance: new Distance(value: 100.00),
            carriers: $carriers
        );

        /** @Then an exception indicating that there are no eligible carriers must be thrown */
        $this->expectException(NoEligibleCarriers::class);
        $this->expectExceptionMessage('There are no eligible carriers for the shipment.');

        /** @When calculating the lowest cost */
        $shipments->lowestCost();
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
