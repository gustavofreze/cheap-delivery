<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait ActionCapabilities
{
    private mixed $payload = null;

    private int $httpCode = HttpCode::OK;

    private string $contentType = 'application/json';

    public function reply(Response $response): Response
    {
        if (!is_null($this->payload)) {
            $response->getBody()->write(json_encode($this->payload));
        }

        return $response
            ->withStatus($this->httpCode)
            ->withHeader('Content-type', $this->contentType);
    }

    public function withPayload(mixed $payload): Action
    {
        $this->payload = $payload;

        return $this;
    }

    public function withHttpCode(int $httpCode): Action
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function withException(ActionException $exception): Action
    {
        $httpCode = $exception->getCode();
        $this->payload = ['error' => $exception->getErrors()];
        $this->httpCode = empty($httpCode) ? HttpCode::INTERNAL_SERVER_ERROR : $exception->getCode();

        return $this;
    }

    public function bodyFromRequest(Request $request): array
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
