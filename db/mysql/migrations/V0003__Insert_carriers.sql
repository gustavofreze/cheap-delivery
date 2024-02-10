INSERT INTO carrier(id, name, cost_modality) VALUES(UUID_TO_BIN(UUID()), 'DHL', '{"modality":"Composite","modalityOne":{"cost":10,"modality":"Fixed"},"modalityTwo":{"cost":0.05,"modality":"Linear"}}'), (UUID_TO_BIN(UUID()), 'FedEx', '{"modality":"Composite","modalityOne":{"cost":4.30,"modality":"Fixed"},"modalityTwo":{"cost":0.12,"modality":"Linear"}}'), (UUID_TO_BIN(UUID()), 'Loggi', '{"modality":"Composite","modalityOne":{"modality":"Partial","costModality":{"modality":"Composite","modalityOne":{"cost":2.10,"modality":"Fixed"},"modalityTwo":{"cost":0.12,"modality":"Linear"}},"costCondition":{"name":"WeightSmallerThan","weight":5.00}},"modalityTwo":{"modality":"Partial","costModality":{"modality":"Composite","modalityOne":{"cost":10.00,"modality":"Fixed"},"modalityTwo":{"cost":0.01,"modality":"Linear"}},"costCondition":{"name":"WeightGreaterThanOrEqual","weight":5.00}}}');