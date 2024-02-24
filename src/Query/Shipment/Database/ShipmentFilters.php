<?php

namespace CheapDelivery\Query\Shipment\Database;

use CheapDelivery\Query\Filters;

final readonly class ShipmentFilters implements Filters
{
    private ?string $carrierName;

    private function __construct(?string $carrierName)
    {
        $this->carrierName = $carrierName;
    }

    public static function from(array $data): ShipmentFilters
    {
        $carrierName = $data['carrierName'] ?? null;

        return new ShipmentFilters(carrierName: $carrierName);
    }

    public function toArray(): array
    {
        return array_filter(['carrier_name' => $this->carrierName]);
    }
}
