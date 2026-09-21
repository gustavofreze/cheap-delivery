* [Dispatch with the lowest cost](#dispatch-with-the-lowest-cost)

## Dispatch with the lowest cost

#### Calculates the lowest available shipping cost for the prize and records the dispatch.

Every registered carrier prices the shipment through the cost modality it declares. A modality is a fixed amount, a rate
per kilometre and kilogram, a band restricted to a weight range, or a composition of those. The carrier that charges the
least is the one the prize is handed to, and the dispatch is recorded against it.

**POST** `{{cheap-delivery-dns}}/dispatches`

### Headers

| Header         |  Type  | Description               | Constraints               | Required |
|:---------------|:------:|:--------------------------|:--------------------------|:--------:|
| `Content-Type` | String | The request content type. | Must be application/json. |   Yes    |

### Request

**Body parameters**:

| Parameter         |  Type  | Description                            | Constraints                                     | Required |
|:------------------|:------:|:---------------------------------------|:------------------------------------------------|:--------:|
| `person`          | Object | Recipient the prize is delivered to.   | Must carry the name and the distance.           |   Yes    |
| `person.name`     | String | Name of the recipient.                 | Between 1 and 255 characters.                   |   Yes    |
| `person.distance` | Number | Distance to the recipient in km.       | Positive, between 0.01 and 20000.00.            |   Yes    |
| `product`         | Object | Prize being shipped.                   | Must carry the name and the weight.             |   Yes    |
| `product.name`    | String | Name of the prize.                     | Between 1 and 255 characters.                   |   Yes    |
| `product.weight`  | Number | Weight of the prize in kg.             | Positive, between 0.01 and 1000.00.             |   Yes    |

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

### Response

- `201 Created`

  **Description**: Indicates that the dispatch was recorded and its identifier was assigned.

  **Content-Type**: application/json

  **Body**:
  ```json
  {
      "id": "0193b3a1-0000-7000-8000-000000000001"
  }
  ```

- `404 Not Found`

  **Description**: Indicates that no carrier is registered, so nothing can be dispatched.

  **Content-Type**: application/json

  **Body**:
  ```json
  {
      "code": "NO_CARRIERS_AVAILABLE",
      "message": "There are no carriers available for dispatch."
  }
  ```

- `409 Conflict`

  **Description**: Indicates that no registered carrier prices a shipment of this weight.

  **Content-Type**: application/json

  **Body**:
  ```json
  {
      "code": "NO_ELIGIBLE_CARRIERS",
      "message": "There are no eligible carriers for the dispatch."
  }
  ```

- `422 Unprocessable Entity`

  **Description**: Indicates that one or more of the provided values are invalid.

  **Content-Type**: application/json

  **Body**:
  ```json
  {
      "code": "INVALID_REQUEST",
      "message": "`.person` must be present"
  }
  ```

  or when the weight is above the accepted maximum:

  ```json
  {
      "code": "WEIGHT_OUT_OF_RANGE",
      "message": "Weight is out of range. Current <2000.16>, Maximum <1000.00>."
  }
  ```

  or when the distance is above the accepted maximum:

  ```json
  {
      "code": "DISTANCE_OUT_OF_RANGE",
      "message": "Distance is out of range. Current <20000.01>, Maximum <20000.00>."
  }
  ```

  The three codes below report a registered carrier the service cannot read, so no request payload produces them. A
  value the payload gets wrong is caught before the domain and answered as `INVALID_REQUEST`.

  When a carrier declares a modality this service does not know:

  ```json
  {
      "code": "CARRIER_MODALITY_NOT_SUPPORTED",
      "message": "A registered carrier declares a cost modality this service does not support."
  }
  ```

  when a carrier prices a shipment at zero or below:

  ```json
  {
      "code": "NON_POSITIVE_VALUE",
      "message": "Cost cannot be zero or negative. Invalid value <0>."
  }
  ```

  when a carrier is registered under an empty name:

  ```json
  {
      "code": "INVALID_NAME",
      "message": "Name cannot be empty."
  }
  ```

- `500 Internal Server Error`

  **Description**: Indicates that an unexpected error occurred on the server while processing the request.

  **Content-Type**: application/json

  **Body**:
  ```json
  {
      "code": "INTERNAL_ERROR",
      "message": "An unexpected error occurred."
  }
  ```
