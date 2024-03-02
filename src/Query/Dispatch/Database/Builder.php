<?php

namespace CheapDelivery\Query\Dispatch\Database;

final class Builder
{
    /**
     * @param array $dispatches
     * @return mixed[]
     */
    public static function from(array $dispatches): array
    {
        $mapper = fn(array $dispatch) => [
            'id'        => $dispatch['id'],
            'cost'      => floatval($dispatch['cost']),
            'carrier'   => [
                'name' => $dispatch['carrierName']
            ],
            'createdAt' => date('c', strtotime($dispatch['createdAt']))
        ];

        return array_map(fn(array $dispatch) => $mapper(dispatch: $dispatch), $dispatches);
    }
}
