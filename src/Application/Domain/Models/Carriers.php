<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Commons\Collectible;
use CheapDelivery\Application\Domain\Models\Commons\Collection;

final class Carriers implements Collectible
{
    use Collection;
}
