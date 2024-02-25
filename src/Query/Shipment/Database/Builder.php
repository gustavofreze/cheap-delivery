<?php

namespace CheapDelivery\Query\Shipment\Database;

final class Builder
{
    public static function from(array $shipments): array
    {
        $mapper = fn(array $shipment) => [
            'id'        => $shipment['id'],
            'cost'      => floatval($shipment['cost']),
            'carrier'   => [
                'name' => $shipment['carrierName']
            ],
            'createdAt' => date('c', strtotime($shipment['createdAt']))
        ];

        return array_map(fn(array $shipment) => $mapper(shipment: $shipment), $shipments);
    }
}
