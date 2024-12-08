<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Modalities\CostModality;

interface CostModalityFactory
{
    public const string FIXED = 'Fixed';
    public const string LINEAR = 'Linear';
    public const string PARTIAL = 'Partial';
    public const string COMPOSITE = 'Composite';

    /**
     * Builds a CostModality instance based on the factory type.
     *
     * @return CostModality The created CostModality instance.
     */
    public function build(): CostModality;
}
