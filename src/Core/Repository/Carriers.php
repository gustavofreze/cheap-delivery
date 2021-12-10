<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Repository;

interface Carriers
{
    public function findAll(): array;
}
