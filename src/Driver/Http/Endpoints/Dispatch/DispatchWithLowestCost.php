<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Application\Handlers\DispatchWithLowestCostHandler;
use CheapDelivery\Application\Ports\Inbound\CommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TinyBlocks\Http\Response;

final readonly class DispatchWithLowestCost implements RequestHandlerInterface
{
    public function __construct(private DispatchWithLowestCostHandler|CommandHandler $useCase)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $payload = (array)json_decode($request->getBody()->__toString(), true);
        $request = new Request(payload: $payload);
        $command = $request->toCommand();

        $this->useCase->handle(command: $command);

        return Response::noContent();
    }
}
