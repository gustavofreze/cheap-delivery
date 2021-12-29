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

    public function __construct(private Environment $environment)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = new HomeData($this->environment->get('API_NAME'), $this->environment->get('API_DEVELOPED_BY'));

            return $this
                ->withHttpCode(HttpCode::OK)
                ->withPayload($data->toArray())
                ->reply($response);
        } catch (Throwable $exception) {
            return $this
                ->withException($exception)
                ->reply($response);
        }
    }
}
