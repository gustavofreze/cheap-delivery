<?php

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

    /**
     * Checks if two identities are equal.
     *
     * @param Identity $other The other identity to compare.
     * @return bool True if equal, false otherwise.
     */
    public function equals(Identity $other): bool;
}
