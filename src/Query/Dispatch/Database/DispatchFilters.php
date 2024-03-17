<?php

namespace CheapDelivery\Query\Dispatch\Database;

use CheapDelivery\Query\Filters;

final readonly class DispatchFilters implements Filters
{
    private ?string $carrierName;

    private function __construct(?string $carrierName)
    {
        $this->carrierName = $carrierName;
    }

    public static function from(array $data): DispatchFilters
    {
        $carrierName = $data['carrierName'] ?? null;

        return new DispatchFilters(carrierName: $carrierName);
    }

    public function toArray(): array
    {
        return array_filter(['carrier_name' => $this->carrierName]);
    }
}
