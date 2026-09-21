<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Ports\Outbound;

use CheapDelivery\Application\Domain\Models\Carrier\Carriers as RegisteredCarriers;

/**
 * Carriers the service can hand a shipment to.
 */
interface Carriers
{
    /**
     * Returns every carrier with the modality it prices shipments by.
     *
     * @return RegisteredCarriers The carriers currently registered.
     */
    public function findAll(): RegisteredCarriers;
}
