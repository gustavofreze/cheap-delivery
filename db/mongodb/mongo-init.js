// noinspection JSUnresolvedFunction, JSUnresolvedVariable

db = db.getSiblingDB('admin');

db.auth('root', 'root');

db = db.getSiblingDB('cheap_delivery');

db.createUser({
    user: 'cheap_delivery_app',
    pwd: 'cheap',
    roles: [{
        role: 'readWrite',
        db: 'cheap_delivery'
    }]
});

db.createCollection('carrier');

db.carrier.insertMany([
    {"name":"DHL","costModality":{"modality":"Composite","modalityOne":{"cost":10.00,"modality":"Fixed"},"modalityTwo":{"cost":0.05,"modality":"Linear"}}},
    {"name":"FedEx","costModality":{"modality":"Composite","modalityOne":{"cost":4.30,"modality":"Fixed"},"modalityTwo":{"cost":0.12,"modality":"Linear"}}},
    {"name":"Loggi","costModality":{"modality":"Composite","modalityOne":{"modality":"Partial","costModality":{"modality":"Composite","modalityOne":{"cost":2.10,"modality":"Fixed"},"modalityTwo":{"cost":0.12,"modality":"Linear"}},"costCondition":{"name":"WeightSmallerThan","weight":5.00}},"modalityTwo":{"modality":"Partial","costModality":{"modality":"Composite","modalityOne":{"cost":10.00,"modality":"Fixed"},"modalityTwo":{"cost":0.01,"modality":"Linear"}},"costCondition":{"name":"WeightGreaterThanOrEqual","weight":5.00}}}}
]);
