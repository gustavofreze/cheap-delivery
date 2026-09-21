<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

use Closure;
use TinyBlocks\Collection\Collection as TinyBlocksCollection;
use TinyBlocks\Collection\Order;

abstract class Collection extends TinyBlocksCollection
{
    protected function sortedByValue(Closure $comparator): static
    {
        return $this->sort(order: Order::ASCENDING_VALUE, comparator: $comparator);
    }
}
