<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Commands;

use CheapDelivery\Application\Domain\Models\Dispatch\DispatchId;
use CheapDelivery\Application\Domain\Models\Dispatch\Person;
use CheapDelivery\Application\Domain\Models\Dispatch\Product;

final readonly class DispatchWithLowestCost implements Command
{
    public function __construct(public DispatchId $id, public Person $person, public Product $product)
    {
    }
}
