<?php

namespace Test\Integration\Query\Dispatch\Factories;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;
use TinyBlocks\EnvironmentVariable\EnvironmentVariable;

final readonly class Request
{
    public static function getFrom(array $parameters): ServerRequestInterface
    {
        $uri = new Uri(
            scheme: 'https',
            host: EnvironmentVariable::from(name: 'CHEAP_DELIVERY_HOST')->toString(),
            port: null,
            path: '/',
            query: http_build_query($parameters)
        );
        /** @var resource $stream */
        $stream = fopen(filename: 'php://memory', mode: 'r+');
        $body = new Stream(stream: $stream);
        $headers = new Headers(headers: ['Content-Type' => 'application/json']);

        return new SlimRequest(method: 'GET', uri: $uri, headers: $headers, cookies: [], serverParams: [], body: $body);
    }
}
