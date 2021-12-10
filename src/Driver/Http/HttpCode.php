<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http;

final class HttpCode
{
    # [Successful 2xx]
    public const OK = 200;

    # [Client Error 4xx]
    public const BAD_REQUEST = 400;
    public const UNPROCESSABLE_ENTITY = 422;

    # [Server Error 5xx]
    public const INTERNAL_SERVER_ERROR = 500;
}
