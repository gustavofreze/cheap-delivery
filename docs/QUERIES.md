* [Query dispatches with Lowest Cost](#query_dispatches_with_lowest_cost)

<div id='query_dispatches_with_lowest_cost'></div> 

## Query dispatches with Lowest Cost

###### Returns all dispatches with the lowest cost.

**GET** `{{dns}}/dispatches?carrierName={:carrierName}`

**Request**

| Parâmetro     |  Tipo  | Descrição     | Obrigatório |
|:--------------|:------:|:--------------|:-----------:|
| `carrierName` | String | Carrier name. |     No      |    

**Response**

```
HTTP/1.1 200 OK
```

```json
[
    {
        "id": "dfa08115-cd39-4bf6-8797-c34fc640316a",
        "cost": 96.4,
        "carrier": {
            "name": "DHL"
        },
        "createdAt": "2024-03-06T09:12:55-03:00"
    },
    {
        "id": "3967810a-0c14-469a-a2a8-5af1f8921c3a",
        "cost": 4.94,
        "carrier": {
            "name": "Loggi"
        },
        "createdAt": "2024-03-06T08:58:45-03:00"
    }
]
```