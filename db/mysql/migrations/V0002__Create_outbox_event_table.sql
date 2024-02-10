CREATE TABLE outbox_event
(
    id             BINARY(16) PRIMARY KEY NOT NULL COMMENT 'UUID that identifies the event in canonical format (separated by hyphen in the form 8-4-4-4-12).',
    aggregate_type VARCHAR(255)           NOT NULL COMMENT 'Name of the aggregation root that produced the event in CamelCase format.',
    aggregate_id   VARCHAR(36)            NOT NULL COMMENT 'Textual representation of the aggregation root identifier.',
    event_type     VARCHAR(255)           NOT NULL COMMENT 'Name of the event in CamelCase format.',
    revision       INT                    NOT NULL COMMENT 'Positive number indicating the version of the payload produced from the event.',
    payload        JSON                   NOT NULL COMMENT 'Event payload as a JSON object.',
    occurred_on    DATETIME(6)            NOT NULL COMMENT 'Moment when the event occurred.',
    created_at     DATETIME(6)            NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT 'Date when the record was inserted.',
    INDEX outbox_event_idx01 (aggregate_id),
    INDEX outbox_event_idx02 (occurred_on),
    INDEX outbox_event_idx03 (created_at)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT ='Table used to persist events atomically for eventual publication on the message broker.';
