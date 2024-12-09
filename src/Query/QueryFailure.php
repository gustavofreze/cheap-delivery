<?php

declare(strict_types=1);

namespace CheapDelivery\Query;

use RuntimeException;

final class QueryFailure extends RuntimeException
{
    public function __construct(string $reason)
    {
        $template = 'Query failed. Reason: <%s>.';

        parent::__construct(message: sprintf($template, $reason));
    }
}
