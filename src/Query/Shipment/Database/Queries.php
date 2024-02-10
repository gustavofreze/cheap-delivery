<?php

namespace CheapDelivery\Query\Shipment\Database;

final class Queries
{
    public const FIND_ALL = 'SELECT BIN_TO_UUID(id) AS id, cost, carrier_name AS carrierName FROM shipment;';
}
