<?php

namespace CheapDelivery\Query;

interface Query
{
    /**
     * Finds all entities matching the given filters.
     *
     * @param Filters $filters The filters to apply to the query.
     * @return mixed[] An array of entities matching the filters.
     * @throws QueryFailure If the query fails for any reason.
     */
    public function findAll(Filters $filters): array;
}
