<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

use TinyBlocks\BuildingBlocks\Entity\Identity as TinyBlocksIdentity;

/**
 * Identity of an aggregate root within the delivery domain.
 */
interface AggregateIdentity extends TinyBlocksIdentity
{
}
