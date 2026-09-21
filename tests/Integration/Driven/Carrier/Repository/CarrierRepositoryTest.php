<?php

declare(strict_types=1);

namespace Test\Integration\Driven\Carrier\Repository;

use CheapDelivery\Application\Domain\Models\Carrier\Carrier;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Application\Ports\Outbound\Carriers;
use Test\Integration\IntegrationTestCase;

final class CarrierRepositoryTest extends IntegrationTestCase
{
    public function testReadsTheSeededCarriersBackWithTheirModalities(): void
    {
        /** @When the registered carriers are read */
        $carriers = $this->get(Carriers::class)->findAll();

        /** @Then every seeded carrier comes back */
        $names = $carriers->map(transformations: fn(Carrier $carrier): string => $carrier->name->value)->toArray();

        self::assertSame(['DHL', 'FedEx', 'Loggi'], $names);
    }

    public function testRebuildsTheModalityThatPricesTheShipment(): void
    {
        /** @Given the registered carriers */
        $carriers = $this->get(Carriers::class)->findAll();

        /** @When each one quotes the same shipment */
        $quotes = $carriers
            ->map(transformations: fn(Carrier $carrier): ?float => $carrier->quote(
                weight: Weight::from(value: 2.16),
                distance: Distance::from(value: 800.00)
            )?->cost->toFloat())
            ->toArray();

        /** @Then the stored modality decides what each charges */
        self::assertSame([96.4, 211.66, 1902.9], $quotes);
    }
}
