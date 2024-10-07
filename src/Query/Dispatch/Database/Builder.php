<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Dispatch\Database;

final class Builder
{
    public static function from(array $dispatches): array
    {
        $mapper = fn(array $dispatch) => [
            'id'        => $dispatch['id'],
            'cost'      => (float)$dispatch['cost'],
            'carrier'   => [
                'name' => $dispatch['carrierName']
            ],
            'createdAt' => date('c', strtotime($dispatch['createdAt']))
        ];

        return array_map(fn(array $dispatch) => $mapper(dispatch: $dispatch), $dispatches);
    }
}
