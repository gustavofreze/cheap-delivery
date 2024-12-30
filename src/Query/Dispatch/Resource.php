<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Dispatch;

use CheapDelivery\Query\Dispatch\Database\DispatchFilters;
use CheapDelivery\Query\Dispatch\Database\Facade;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use TinyBlocks\Http\Response;

final readonly class Resource implements RequestHandlerInterface
{
    public function __construct(private Facade $facade)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $filters = DispatchFilters::from(data: $request->getQueryParams());
            $dispatches = $this->facade->findAll(filters: $filters);

            return Response::ok(body: $dispatches);
        } catch (Throwable $exception) {
            return Response::internalServerError(body: ['error' => $exception->getMessage()]);
        }
    }
}
