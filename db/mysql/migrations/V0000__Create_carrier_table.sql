CREATE TABLE carrier
(
    id            BINARY(16)   NOT NULL COMMENT 'Unique identifier for the carrier.',
    name          VARCHAR(255) NOT NULL COMMENT 'Name of the carrier.',
    cost_modality JSON         NOT NULL COMMENT 'JSON representation of types and costs of transportation offered by the carrier.',
    created_at    TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT 'Date and time when the carrier record was inserted.',
    PRIMARY KEY (id),
    INDEX carrier_idx01 (name),
    CONSTRAINT carrier_uk01
        UNIQUE (name)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT ='Table used to persist carrier records.';
