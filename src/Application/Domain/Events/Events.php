<?php

namespace CheapDelivery\Application\Domain\Events;

use CheapDelivery\Application\Domain\Models\Commons\Collectible;
use CheapDelivery\Application\Domain\Models\Commons\Collection;

final class Events implements Collectible
{
    use Collection;
}
