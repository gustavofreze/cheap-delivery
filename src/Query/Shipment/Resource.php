<?php

namespace CheapDelivery\Query\Shipment;

use CheapDelivery\Query\Shipment\Database\Facade;
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
        $shipments = $this->facade->findAll();

        return HttpResponse::ok(data: $shipments);
    }
}
