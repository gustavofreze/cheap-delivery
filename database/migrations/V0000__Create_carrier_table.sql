CREATE TABLE carrier
(
    id            BINARY(16)   NOT NULL COMMENT '[NONE] The carrier identifier in UUID format (e.g., 0190d09e-a7a8-7e89-b48c-09fff0f0f0f0).',
    name          VARCHAR(255) NOT NULL COMMENT '[NONE] The trading name the carrier is known by (e.g., DHL).',
    cost_modality JSON         NOT NULL COMMENT '[NONE] The modality the carrier prices a shipment by, which is a fixed amount, a rate per kilometre and kilogram, a band restricted to a weight range, or a composition of those (e.g., {"modality": "Composite", "modalityOne": {"cost": 10, "modality": "Fixed"}, "modalityTwo": {"cost": 0.05, "modality": "Linear"}}).',
    created_at    TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT '[NONE] The UTC date and time when the record was created in ISO 8601 format (e.g., 2026-02-13T08:49:44.931408+00:00).',
    PRIMARY KEY (id),
    CONSTRAINT unq_carrier_name UNIQUE (name)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_0900_ai_ci
    COMMENT = '[NONE] Table used to persist the carriers a shipment can be handed to.';
