<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Modalities\CostModality;

interface CostModalityFactory
{
    public const FIXED = 'Fixed';
    public const LINEAR = 'Linear';
    public const PARTIAL = 'Partial';
    public const COMPOSITE = 'Composite';

    /**
     * Builds a CostModality instance based on the factory type.
     *
     * @return CostModality The created CostModality instance.
     */
    public function build(): CostModality;
}
