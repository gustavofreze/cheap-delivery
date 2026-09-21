<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Dispatch\FindAll\Http;

use CheapDelivery\Query\Dispatch\FindAll\DispatchesFinding;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class FindDispatches implements RequestHandlerInterface
{
    private const string BASE_URI = '/dispatches';

    public function __construct(private DispatchesFinding $dispatches)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = FindDispatchesRequest::from(request: $request);

        return $this->dispatches
            ->findAll(keyset: $query->keyset, comparisons: $query->comparisons)
            ->toResponse(baseUri: FindDispatches::BASE_URI);
    }
}
