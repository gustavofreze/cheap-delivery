<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

interface HttpResponse
{
    public function reply(ResponseInterface $response): ResponseInterface;

    public function withPayload(mixed $payload): HttpResponse;

    public function withHttpCode(int $httpCode): HttpResponse;

    public function withException(Throwable $exception): HttpResponse;

    public function bodyFromRequest(ServerRequestInterface $request): array;
}
