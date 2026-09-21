<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Dispatch\FindAll\Database;

final readonly class Queries
{
    public const string BASE = "
        SELECT BIN_TO_UUID(dsp.id) AS id,
               dsp.cost            AS cost,
               dsp.carrier_name    AS carrier_name,
               DATE_FORMAT(dsp.created_at, '%Y-%m-%dT%H:%i:%s.%f+00:00') AS created_at
        FROM dispatch AS dsp
    ";
}
