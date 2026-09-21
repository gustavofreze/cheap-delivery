* [Find dispatches](#find-dispatches)

## Find dispatches

#### Lists the dispatches recorded by the service, most recent first.

The page is a forward-only keyset cursor, so there is no page number and no total. The next cursor is carried in
`links.next` whenever `meta.has_next` is true, and the `links.next` entry is absent otherwise.

**GET** `{{cheap-delivery-dns}}/dispatches`

### Request

**Path and query parameters**:

| Parameter      |  Type   | Description                                                                  | Constraints                                                                                                       | Required |
|:---------------|:-------:|:-----------------------------------------------------------------------------|:-------------------------------------------------------------------------------------------------------------------|:--------:|
| `sort`         | String  | Deterministic ordering of the page.                                          | Sortable by `cost`, `created_at` and `id`, with a leading minus marking descending. Default: `-created_at,-id`.    |    No    |
| `filter`       | String  | Filter over the dispatch fields.                                             | Only `carrier_name` is filterable, under the `==`, `=in=` and `=sw=` operators, with string values.                |    No    |
| `page[size]`   | Integer | Items per page. E.g., `20`.                                                  | Must be between 1 and 100. Default: 20.                                                                            |    No    |
| `page[cursor]` | String  | Opaque forward-only cursor carried by `links.next` of the previous response. | Must be a token this endpoint issued, never a value the client builds.                                             |    No    |

### Response

- `200 OK`

  **Description**: Indicates that the dispatches page was recovered.

  **Headers**:
  | Header |  Type  | Description                                                                      | Constraints                                                                           | Required |
  |:-------|:------:|:---------------------------------------------------------------------------------|:--------------------------------------------------------------------------------------|:--------:|
  | `Link` | String | RFC 8288 navigation carrying the same targets as the `links` object of the body. | Carries the `self` target always, and the `next` target when `meta.has_next` is true. |   Yes    |

  **Content-Type**: application/json

  **Body**:
  ```json
  {
      "data": [
          {
              "id": "0193b3a1-0000-7000-8000-000000000001",
              "cost": 96.4,
              "created_at": "2026-09-21T10:00:00.000000+00:00",
              "carrier_name": "DHL"
          }
      ],
      "meta": {
          "per_page": 1,
          "has_next": true
      },
      "links": {
          "self": "/dispatches?page[size]=1",
          "next": "/dispatches?page[cursor]=WyIyMDI2LTA5LTIxVDEwOjAwOjAwLjAwMDAwMCswMDowMCIsIjAxOTNiM2ExLTAwMDAtNzAwMC04MDAwLTAwMDAwMDAwMDAwMSJd&page[size]=1"
      }
  }
  ```

- `422 Unprocessable Entity`

  **Description**: Indicates that a query parameter failed validation.

  **Content-Type**: application/json

  **Body**:
  ```json
  {
      "code": "INVALID_REQUEST",
      "message": "Sort field <carrier_name> is not allowed."
  }
  ```

  or when the filter targets a field that is not filterable:

  ```json
  {
      "code": "INVALID_REQUEST",
      "message": "Filter field <cost> is not allowed."
  }
  ```

  or when the page size is above the maximum:

  ```json
  {
      "code": "INVALID_REQUEST",
      "message": "Page size <500> must be less than or equal to 100."
  }
  ```

  or when the cursor cannot be decoded:

  ```json
  {
      "code": "INVALID_REQUEST",
      "message": "Cursor token <broken> is invalid and could not be decoded."
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
