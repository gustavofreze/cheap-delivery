<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Dispatch\FindAll\Database;

use TinyBlocks\HttpQuery\Clause\Every;
use TinyBlocks\HttpQuery\Clause\FilterColumns;
use TinyBlocks\HttpQuery\Clause\Filters;
use TinyBlocks\HttpQuery\Clause\SeekClause;
use TinyBlocks\HttpQuery\Clause\SortClause;
use TinyBlocks\HttpQuery\Cursor\Keyset;

final readonly class DispatchesKeysetQuery
{
    private function __construct(public string $sql, public array $parameters)
    {
    }

    public static function from(Keyset $keyset, array $comparisons): DispatchesKeysetQuery
    {
        $columns = FilterColumns::create()
            ->plain(field: 'cost', column: 'dsp.cost')
            ->plain(field: 'created_at', column: 'dsp.created_at')
            ->plain(field: 'carrier_name', column: 'dsp.carrier_name')
            ->wrapped(field: 'id', column: 'dsp.id', binding: 'UUID_TO_BIN(%s)');

        $filters = Filters::from(columns: $columns, comparisons: $comparisons);
        $seek = SeekClause::from(keyset: $keyset, columns: $columns);

        $predicate = Every::of($filters, $seek);

        $sort = SortClause::from(orders: $keyset->orders(), columns: $columns);
        $limit = $keyset->limit()->plusOne();

        $where = $predicate->isEmpty() ? '' : sprintf('WHERE %s', $predicate->sql());

        $template = '%s %s ORDER BY %s LIMIT %d';
        $sql = sprintf($template, Queries::BASE, $where, $sort->sql(), $limit->toInteger());

        return new DispatchesKeysetQuery(sql: $sql, parameters: $predicate->parameters());
    }
}
