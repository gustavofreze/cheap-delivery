<?php

declare(strict_types=1);

namespace CheapDelivery\Query;

use Doctrine\DBAL\Query\QueryBuilder as DoctrineQueryBuilder;

class QueryBuilder extends DoctrineQueryBuilder
{
    private const string AND_WHERE = '%s = :%s';

    public function applyFilters(Filters $filters): QueryBuilder
    {
        foreach ($filters->toArray() as $column => $value) {
            $this
                ->andWhere(sprintf(self::AND_WHERE, $column, $column))
                ->setParameter($column, $value);
        }

        return $this;
    }
}
