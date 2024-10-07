<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Commands;

use CheapDelivery\Application\Domain\Models\Person;
use CheapDelivery\Application\Domain\Models\Product;

final readonly class DispatchWithLowestCost implements Command
{
    public function __construct(public Person $person, public Product $product)
    {
    }
}
