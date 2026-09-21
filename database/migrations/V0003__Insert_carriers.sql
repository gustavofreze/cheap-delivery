INSERT INTO carrier (id, name, cost_modality)
VALUES (UUID_TO_BIN(UUID()), 'DHL', '{
  "modality": "Composite",
  "modalityOne": {"modality": "Fixed", "cost": 10.00},
  "modalityTwo": {"modality": "Linear", "cost": 0.05}
}'),
       (UUID_TO_BIN(UUID()), 'FedEx', '{
         "modality": "Composite",
         "modalityOne": {"modality": "Fixed", "cost": 4.30},
         "modalityTwo": {"modality": "Linear", "cost": 0.12}
       }'),
       (UUID_TO_BIN(UUID()), 'Loggi', '{
         "modality": "Composite",
         "modalityOne": {
           "modality": "Partial",
           "costModality": {
             "modality": "Composite",
             "modalityOne": {"modality": "Fixed", "cost": 2.10},
             "modalityTwo": {"modality": "Linear", "cost": 1.10}
           },
           "costCondition": {"name": "WeightSmallerThan", "weight": 5.00}
         },
         "modalityTwo": {
           "modality": "Partial",
           "costModality": {
             "modality": "Composite",
             "modalityOne": {"modality": "Fixed", "cost": 10.00},
             "modalityTwo": {"modality": "Linear", "cost": 0.01}
           },
           "costCondition": {"name": "WeightGreaterThanOrEqual", "weight": 5.00}
         }
       }');
