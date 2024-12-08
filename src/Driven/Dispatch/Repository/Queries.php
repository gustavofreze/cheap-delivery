<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\Repository;

final class Queries
{
    public const string INSERT_DISPATCH = '
           INSERT INTO dispatch (id, cost, carrier_name)
           VALUES (UUID_TO_BIN(:id), :cost, :carrierName);';
}
