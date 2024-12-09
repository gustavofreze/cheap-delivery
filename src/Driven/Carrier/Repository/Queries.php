<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository;

final readonly class Queries
{
    public const string FIND_ALL = 'SELECT BIN_TO_UUID(id) AS id, name, cost_modality AS costModality FROM carrier;';
}
