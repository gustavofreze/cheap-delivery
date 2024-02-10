<?php

namespace CheapDelivery\Application\Commands;

use CheapDelivery\Application\Domain\Models\Person;
use CheapDelivery\Application\Domain\Models\Product;

final readonly class CalculateShipment implements Command
{
    public function __construct(public Person $person, public Product $product)
    {
    }
}
