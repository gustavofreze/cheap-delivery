<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

trait HttpResponseAdapter
{
    private mixed $payload = null;

    private int $httpCode = HttpCode::OK;

    private string $contentType = 'application/json';

    public function reply(ResponseInterface $response): ResponseInterface
    {
        if (!is_null($this->payload)) {
            $response->getBody()->write(json_encode($this->payload));
        }

        return $response
            ->withStatus($this->httpCode)
            ->withHeader('Content-type', $this->contentType);
    }

    public function withPayload(mixed $payload): HttpResponse
    {
        $this->payload = $payload;

        return $this;
    }

    public function withHttpCode(int $httpCode): HttpResponse
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function withException(Throwable $exception): HttpResponse
    {
        $httpCode = empty($exception->getCode()) ? HttpCode::INTERNAL_SERVER_ERROR : $exception->getCode();
        $this->httpCode = $httpCode;

        if ($exception instanceof HttpException) {
            $this->payload = ['error' => $exception->getErrors()];

            return $this;
        }

        $this->payload = ['error' => $exception->getMessage()];

        return $this;
    }

    public function bodyFromRequest(ServerRequestInterface $request): array
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if (!strstr($contentType, $this->contentType)) {
            return [];
        }

        $contents = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $request->withParsedBody($contents)->getParsedBody();
    }
}
