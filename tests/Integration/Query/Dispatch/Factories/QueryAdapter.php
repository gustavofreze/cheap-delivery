<?php

namespace Test\Integration\Query\Dispatch\Factories;

use Test\Integration\QueryCapabilities;

final class QueryAdapter extends QueryCapabilities
{
    private const string INSERT_DISPATCH = '
            INSERT INTO dispatch (id, cost, carrier_name, created_at)
            VALUES (UUID_TO_BIN(:id), :cost, :carrierName, :createdAt);';

    public function saveDispatches(array $dispatches): void
    {
        foreach ($dispatches as $dispatch) {
            $this->connection->executeStatement(self::INSERT_DISPATCH, [
                'id'          => $dispatch['id'],
                'cost'        => $dispatch['cost'],
                'createdAt'   => $dispatch['createdAt'],
                'carrierName' => $dispatch['carrier']['name']
            ]);
        }
    }
}
