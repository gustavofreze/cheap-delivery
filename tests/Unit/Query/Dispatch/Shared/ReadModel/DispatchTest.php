<?php

declare(strict_types=1);

namespace Test\Unit\Query\Dispatch\Shared\ReadModel;

use CheapDelivery\Query\Dispatch\Shared\ReadModel\Dispatch;
use PHPUnit\Framework\TestCase;

final class DispatchTest extends TestCase
{
    public function testRendersEveryFieldTheContractPublishes(): void
    {
        /** @Given a dispatch read model */
        $dispatch = Dispatch::from(
            id: '0193b3a1-0000-7000-8000-000000000001',
            cost: 96.40,
            createdAt: '2026-09-21T10:00:00.000000+00:00',
            carrierName: 'DHL'
        );

        /** @When it is rendered */
        $actual = $dispatch->toArray();

        /** @Then every field is published in snake case */
        self::assertSame(
            [
                'id'           => '0193b3a1-0000-7000-8000-000000000001',
                'cost'         => 96.40,
                'created_at'   => '2026-09-21T10:00:00.000000+00:00',
                'carrier_name' => 'DHL'
            ],
            $actual
        );
    }
}
