<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Dispatch\FindAll\Http;

use Psr\Http\Message\ServerRequestInterface;
use TinyBlocks\HttpQuery\Cursor\Criteria;
use TinyBlocks\HttpQuery\Cursor\Keyset;
use TinyBlocks\HttpQuery\Operator;
use TinyBlocks\HttpQuery\Schema;
use TinyBlocks\HttpQuery\Sort;
use TinyBlocks\HttpQuery\ValueKind;

final readonly class FindDispatchesRequest
{
    private function __construct(public Keyset $keyset, public array $comparisons)
    {
    }

    public static function from(ServerRequestInterface $request): FindDispatchesRequest
    {
        $schema = Schema::create()
            ->sortable(fields: ['cost', 'created_at', 'id'])
            ->filterable(
                field: 'carrier_name',
                operators: [Operator::EQUAL, Operator::IN, Operator::STARTS_WITH],
                valueKind: ValueKind::STRING
            )
            ->defaultSort(sort: Sort::fromExpression(expression: '-created_at,-id'));

        $criteria = Criteria::fromQuery(schema: $schema, request: $request);

        return new FindDispatchesRequest(keyset: $criteria->keyset(), comparisons: $criteria->comparisons());
    }
}
