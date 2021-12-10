<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface Action
{
    public function reply(Response $response): Response;

    public function withPayload(mixed $payload): Action;

    public function withHttpCode(int $httpCode): Action;

    public function withException(ActionException $exception): Action;

    public function bodyFromRequest(Request $request): array;
}
