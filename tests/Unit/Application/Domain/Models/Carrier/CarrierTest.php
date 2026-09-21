<?php

declare(strict_types=1);

namespace Test\Unit\Application\Domain\Models\Carrier;

use CheapDelivery\Application\Domain\Models\Carrier\Carrier;
use CheapDelivery\Application\Domain\Models\Carrier\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Application\Domain\Models\Carrier\Conditions\WeightSmallerThan;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CompositeCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\FixedCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\LinearCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\PartialCost;
use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use PHPUnit\Framework\TestCase;
use Test\Unit\CarrierFactory;

final class CarrierTest extends TestCase
{
    public function testQuotesTheShipmentItPrices(): void
    {
        /** @Given a carrier charging a fixed amount plus a rate per km and kg */
        $carrier = Carrier::from(
            name: Name::from(value: 'DHL'),
            modality: CarrierFactory::priced(rate: 0.05, fixed: 10.00)
        );

        /** @When it quotes a shipment */
        $shipment = $carrier->quote(weight: Weight::from(value: 2.16), distance: Distance::from(value: 800.00));

        /** @Then the quote carries the carrier and what it charges */
        self::assertSame('DHL', $shipment->carrierName->value);
        self::assertSame(96.40, $shipment->cost->toFloat());
    }

    public function testQuotesNothingWhenItDoesNotPriceTheWeight(): void
    {
        /** @Given a carrier pricing only the heavy band */
        $carrier = Carrier::from(
            name: Name::from(value: 'Loggi'),
            modality: PartialCost::from(
                modality: CarrierFactory::priced(rate: 0.01, fixed: 10.00),
                condition: WeightGreaterThanOrEqual::from(threshold: Weight::from(value: 5.00))
            )
        );

        /** @When it quotes a light shipment */
        $shipment = $carrier->quote(weight: Weight::from(value: 2.16), distance: Distance::from(value: 800.00));

        /** @Then it declines to quote */
        self::assertNull($shipment);
    }

    public function testChargesTheFixedAmountWhateverTheShipment(): void
    {
        /** @Given a fixed cost modality */
        $modality = FixedCost::from(cost: Cost::from(value: 10.00));

        /** @When it prices two different shipments */
        $light = $modality->calculate(weight: Weight::from(value: 1.00), distance: Distance::from(value: 10.00));
        $heavy = $modality->calculate(weight: Weight::from(value: 900.00), distance: Distance::from(value: 900.00));

        /** @Then both cost the same */
        self::assertSame(10.00, $light->toFloat());
        self::assertSame(10.00, $heavy->toFloat());
    }

    public function testChargesTheRatePerKilometreAndKilogram(): void
    {
        /** @Given a linear cost modality */
        $modality = LinearCost::from(rate: Cost::from(value: 0.05));

        /** @When it prices a shipment */
        $cost = $modality->calculate(weight: Weight::from(value: 2.16), distance: Distance::from(value: 800.00));

        /** @Then the charge is the rate times the distance times the weight */
        self::assertSame(86.40, $cost->toFloat());
    }

    public function testFallsBackToTheModalityThatPricesTheShipment(): void
    {
        /** @Given a composite of two bands, only one of which prices a light shipment */
        $modality = CompositeCost::from(
            first: PartialCost::from(
                modality: FixedCost::from(cost: Cost::from(value: 2.10)),
                condition: WeightSmallerThan::from(threshold: Weight::from(value: 5.00))
            ),
            second: PartialCost::from(
                modality: FixedCost::from(cost: Cost::from(value: 10.00)),
                condition: WeightGreaterThanOrEqual::from(threshold: Weight::from(value: 5.00))
            )
        );

        /** @When it prices a light and a heavy shipment */
        $light = $modality->calculate(weight: Weight::from(value: 2.16), distance: Distance::from(value: 10.00));
        $heavy = $modality->calculate(weight: Weight::from(value: 7.50), distance: Distance::from(value: 10.00));

        /** @Then each falls back to the band that prices it */
        self::assertSame(2.10, $light->toFloat());
        self::assertSame(10.00, $heavy->toFloat());
    }

    public function testPricesNothingWhenNoBandApplies(): void
    {
        /** @Given a composite whose bands both decline the shipment */
        $modality = CompositeCost::from(
            first: PartialCost::from(
                modality: FixedCost::from(cost: Cost::from(value: 2.10)),
                condition: WeightGreaterThanOrEqual::from(threshold: Weight::from(value: 100.00))
            ),
            second: PartialCost::from(
                modality: FixedCost::from(cost: Cost::from(value: 10.00)),
                condition: WeightGreaterThanOrEqual::from(threshold: Weight::from(value: 200.00))
            )
        );

        /** @When it prices a light shipment */
        $cost = $modality->calculate(weight: Weight::from(value: 2.16), distance: Distance::from(value: 10.00));

        /** @Then nothing is charged */
        self::assertNull($cost);
    }
}
