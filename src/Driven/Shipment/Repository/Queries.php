<?php

namespace CheapDelivery\Driven\Shipment\Repository;

final class Queries
{
    public const INSERT_SHIPMENT = '
           INSERT INTO shipment (id, cost, carrier_name)
           VALUES (UUID_TO_BIN(:id), :cost, :carrierName);';
}
