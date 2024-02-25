<?php

namespace CheapDelivery\Driver\Http\Endpoints\CalculateShipment\Factories;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

final class Request
{
    public static function postFrom(array $payload): ServerRequestInterface
    {
        $encode = json_encode($payload);
        $stream = fopen('php://memory', 'r+');

        fwrite($stream, $encode);
        rewind($stream);

        $uri = new Uri('https', 'cheap-delivery.localhost');
        $body = new Stream($stream);
        $headers = new Headers(['Content-Type' => 'application/json']);

        return new SlimRequest('POST', $uri, $headers, [], [], $body);
    }
}
