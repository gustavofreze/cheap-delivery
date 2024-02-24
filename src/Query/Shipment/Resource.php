<?php

namespace CheapDelivery\Query\Shipment;

use CheapDelivery\Query\Shipment\Database\Facade;
use CheapDelivery\Query\Shipment\Database\ShipmentFilters;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TinyBlocks\Http\HttpResponse;

final readonly class Resource implements RequestHandlerInterface
{
    public function __construct(private Facade $facade)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $filters = ShipmentFilters::from(data: $request->getQueryParams());
        $shipments = $this->facade->findAll(filters: $filters);

        return HttpResponse::ok(data: $shipments);
    }
}
