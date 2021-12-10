<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Shipping;

use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Name;
use CheapDelivery\Core\Models\Person;
use CheapDelivery\Core\Models\Product;
use CheapDelivery\Core\Models\Shipments;
use CheapDelivery\Core\Models\Weight;
use CheapDelivery\Core\Repository\Carriers;
use CheapDelivery\Driver\Http\Action;
use CheapDelivery\Driver\Http\ActionCapabilities;
use CheapDelivery\Driver\Http\Exceptions\InvalidRequestPayload;
use CheapDelivery\Driver\Http\Exceptions\NoEligibleCarriers;
use CheapDelivery\Driver\Http\HttpCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

final class CalculateShipping implements Action
{
    use ActionCapabilities;

    public function __construct(private Carriers $carriers, private CalculateShippingValidator $validator)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $body = $this->bodyFromRequest($request);
            $this->validator->validate($body);

            $person = new Person(
                name: new Name($body['person']['name']),
                distance: new Distance($body['person']['distance'])
            );

            $product = new Product(
                name: new Name($body['product']['name']),
                weight: new Weight($body['product']['weight'])
            );

            $carriers = $this->carriers->findAll();
            $shipments = Shipments::from($carriers, $product->getWeight(), $person->getDistance());
            $shipment = $shipments->lowestCost();

            if (is_null($shipment)) {
                throw new NoEligibleCarriers();
            }

            return $this
                ->withHttpCode(HttpCode::OK)
                ->withPayload($shipment->toArray())
                ->reply($response);
        } catch (NoEligibleCarriers|InvalidRequestPayload $exception) {
            return $this
                ->withException($exception)
                ->reply($response);
        } catch (Throwable $exception) {
            return $this
                ->withHttpCode(HttpCode::INTERNAL_SERVER_ERROR)
                ->withPayload($exception->getMessage())
                ->reply($response);
        }
    }
}
