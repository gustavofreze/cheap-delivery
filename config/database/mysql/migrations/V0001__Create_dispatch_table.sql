CREATE TABLE dispatch
(
    id           BINARY(16)     NOT NULL COMMENT 'Unique identifier for the dispatch.',
    cost         DECIMAL(15, 2) NOT NULL COMMENT 'Cost of the shipment.',
    carrier_name VARCHAR(255)   NOT NULL COMMENT 'Name of the carrier providing the shipment service.',
    created_at   TIMESTAMP(6)   NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT 'Date when the record was inserted.',
    PRIMARY KEY (id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT ='Table used to persist dispatch records.';
