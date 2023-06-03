<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Ports\Outbound;

interface Carriers
{
    public function findAll(): array;
}
