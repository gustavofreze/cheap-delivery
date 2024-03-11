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

**Responses**

- `204 No Content`

  **Description**: Indicates that based on the request data, a dispatch with the lowest cost has been calculated.

  **Content-Type**: application/json

  **Body**: N/A


- `400 Bad Request`

  **Description**: Indicates that the request could not be processed due to client issues.

  **Content-Type**: application/json

  **Body**:
    ```json
    {
        "error": "There are no carriers available for dispatch."
    } 
    ```


- `404 Not Found`

  **Description**: Indicates that the requested resource could not be found.

  **Content-Type**: application/json

  **Body**:
    ```json
    {
        "error": "There are no carriers available for dispatch."
    }
    ```


- `422 Unprocessable Entity`

  **Description**: Indicates that one or more of the provided values are invalid.

  **Content-Type**: application/json

  **Body**:
    ```json
    {
        "error": "Weight is out of range. Current <2000.16>, Maximum <1000.00>."
    }
    ```


- `500 Internal Server Error`

  **Description**: Indicates that an unexpected error occurred on the server while processing the request.

  **Content-Type**: application/json

  **Body**:
    ```json
    {
        "error": "An internal server error occurred."
    }
    ```
