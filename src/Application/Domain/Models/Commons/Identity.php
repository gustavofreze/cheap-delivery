<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

/**
 * Represents the concept of identity in the domain.
 */
interface Identity
{
    /**
     * Gets the unique identifier of the identity.
     *
     * @return mixed The unique identifier.
     */
    public function getValue(): mixed;
}
