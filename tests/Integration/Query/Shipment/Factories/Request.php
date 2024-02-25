<?php

namespace Test\Integration\Query\Shipment\Factories;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

final class Request
{
    public static function getFrom(array $parameters): ServerRequestInterface
    {
        $stream = fopen('php://memory', 'r+');

        $uri = new Uri('https', 'cheap-delivery.localhost', null, '/', http_build_query($parameters));
        $body = new Stream($stream);
        $headers = new Headers(['Content-Type' => 'application/json']);

        return new SlimRequest('GET', $uri, $headers, [], [], $body);
    }
}
