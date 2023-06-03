<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Actions\Home;

use CheapDelivery\Driven\Environment\Environment;
use CheapDelivery\Driver\Http\HttpCode;
use CheapDelivery\Driver\Http\HttpResponse;
use CheapDelivery\Driver\Http\HttpResponseAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final class Home implements HttpResponse
{
    use HttpResponseAdapter;

    public function __construct(private readonly Environment $environment)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = new HomeData(
                name: $this->environment->get('API_NAME'),
                developedBy: $this->environment->get('API_DEVELOPED_BY')
            );

            return $this
                ->withHttpCode(httpCode: HttpCode::OK)
                ->withPayload(payload: $data->toArray())
                ->reply(response: $response);
        } catch (Throwable $exception) {
            return $this
                ->withException(exception: $exception)
                ->reply(response: $response);
        }
    }
}
