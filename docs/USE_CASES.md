* [Dispatch with lowest cost](#dispatch_with_lowest_cost)

<div id='dispatch_with_lowest_cost'></div> 

## Dispatch with lowest cost

###### It is the process that calculates the dispatch with the lowest available cost.

**POST** `{{dns}}/dispatches`

**Request**

| Parameter         |  Type  | Description           | Required |
|:------------------|:------:|:----------------------|:--------:|
| `person`          | Object | Recipient's data.     |   Yes    |    
| `person.name`     | String | Recipient's name.     |   Yes    |    
| `person.distance` | Float  | Recipient's distance. |   Yes    |    
| `product`         | Object | Product's data.       |   Yes    |    
| `product.name`    | String | Product's name.       |   Yes    |    
| `product.weight`  | Float  | Product's weight.     |   Yes    |

```json
{
    "person": {
        "name": "Gustavo",
        "distance": 800.0
    },
    "product": {
        "name": "MacBook Pro",
        "weight": 2.16
    }
}
```

**Response**

```
HTTP/1.1 204 No Content
```
