<?php

namespace CheapDelivery\Query\Dispatch\Database;

use CheapDelivery\Query\Filters;
use CheapDelivery\Query\Query;
use CheapDelivery\Query\QueryBuilder;
use CheapDelivery\Query\QueryFailure;
use Throwable;

final readonly class Facade implements Query
{
    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    public function findAll(DispatchFilters|Filters $filters): array
    {
        try {
            $dispatches = $this->queryBuilder
                ->select(select: [
                    'cost',
                    'BIN_TO_UUID(id) AS id',
                    'created_at      AS createdAt',
                    'carrier_name    AS carrierName'
                ])
                ->from(from: 'dispatch')
                ->applyFilters(filters: $filters)
                ->orderBy(sort: 'created_at', order: 'DESC')
                ->executeQuery()
                ->fetchAllAssociative();

            return Builder::from(dispatches: $dispatches);
        } catch (Throwable $exception) {
            throw new QueryFailure(reason: $exception->getMessage());
        }
    }
}
