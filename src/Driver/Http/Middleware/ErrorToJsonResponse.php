<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Middleware;

use CheapDelivery\Driver\Http\HttpResponse;
use CheapDelivery\Driver\Http\HttpResponseAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Throwable;

final class ErrorToJsonResponse implements MiddlewareInterface, HttpResponse
{
    use HttpResponseAdapter;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle(request: $request);
        } catch (Throwable $exception) {
            return $this
                ->withException(exception: $exception)
                ->reply(response: new Response());
        }
    }
}
