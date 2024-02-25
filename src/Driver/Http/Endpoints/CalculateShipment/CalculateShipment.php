<?php

namespace CheapDelivery\Driver\Http\Endpoints\CalculateShipment;

use CheapDelivery\Application\Handlers\CalculateShipmentHandler;
use CheapDelivery\Application\Ports\Inbound\CommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TinyBlocks\Http\HttpResponse;

final readonly class CalculateShipment implements RequestHandlerInterface
{
    public function __construct(private CalculateShipmentHandler|CommandHandler $useCase)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $payload = json_decode($request->getBody()->__toString(), true);
        $request = new Request(payload: $payload);
        $command = $request->toCommand();

        $this->useCase->handle(command: $command);

        return HttpResponse::noContent();
    }
}
