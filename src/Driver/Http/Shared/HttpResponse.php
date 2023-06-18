<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Shared;

use TinyBlocks\Serializer\Serializer;

interface HttpResponse extends Serializer
{
    public function toArray(): array;
}
