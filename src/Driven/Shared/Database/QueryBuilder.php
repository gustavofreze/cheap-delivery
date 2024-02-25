<?php

namespace CheapDelivery\Driven\Shared\Database;

interface QueryBuilder
{
    /**
     * Map the results using a callback.
     *
     * @param callable $callback The callback function to apply to each element.
     * @return mixed[] The mapped results.
     * @throws DatabaseFailure If an error occurs during the mapping operation.
     */
    public function map(callable $callback): array;

    /**
     * Bind values to parameters in the SQL query.
     *
     * @param array $data An associative array where keys are column names and values are the corresponding values.
     * @return QueryBuilder The current instance for method chaining.
     * @throws DatabaseFailure If an error occurs during the binding operation.
     */
    public function bind(array $data): QueryBuilder;

    /**
     * Set the query string for the statement.
     *
     * @param string $sql The SQL query string.
     * @return QueryBuilder The current instance for method chaining.
     * @throws DatabaseFailure If an error occurs while setting the query string.
     */
    public function query(string $sql): QueryBuilder;

    /**
     * Execute the prepared statement.
     *
     * @return QueryBuilder The current instance for method chaining.
     * @throws DatabaseFailure If an error occurs during the execution.
     */
    public function execute(): QueryBuilder;

    /**
     * Fetch all rows from the result set.
     *
     * @return mixed[] All rows from the result set.
     * @throws DatabaseFailure If an error occurs during the fetching operation.
     */
    public function fetchAll(): array;
}
