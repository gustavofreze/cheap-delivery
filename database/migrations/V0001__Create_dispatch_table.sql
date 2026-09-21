CREATE TABLE dispatch
(
    id           BINARY(16)     NOT NULL COMMENT '[NONE] The dispatch identifier in Version 7 UUID format (e.g., 0190d09e-a7a8-7e89-b48c-09fff0f0f0f0).',
    cost         DECIMAL(15, 2) NOT NULL COMMENT '[NONE] The amount the chosen carrier charges for the shipment (e.g., 96.40).',
    carrier_name VARCHAR(255)   NOT NULL COMMENT '[NONE] The trading name of the carrier the shipment was handed to (e.g., DHL).',
    created_at   TIMESTAMP(6)   NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT '[NONE] The UTC date and time when the record was created in ISO 8601 format (e.g., 2026-02-13T08:49:44.931408+00:00).',
    PRIMARY KEY (id),
    INDEX idx_dispatch_created_at_id (created_at DESC, id DESC)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_0900_ai_ci
    COMMENT = '[NONE] Table used to persist the dispatches the service decided.';
