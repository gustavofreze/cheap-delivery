# Cheap Delivery

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

* [Overview](#overview)
    - [Use cases](#use_cases)
    - [Queries](#queries)
* [Installation](#installation)
    - [Repository](#repository)
    - [Configuration](#configuration)
    - [Tests](#tests)
    - [Review](#review)
* [Environment setup](#environment_setup)

<div id="overview"></div> 

## Overview

Company XPTO held a drawing among players from all over Brazil. However, the prizes need to be delivered to the winners.
To achieve this, it is necessary to implement a system that calculates the lowest shipping cost for each award based on
the distance to the winner and the weight of the prize. When contacting carriers, we received the following
transportation conditions:

| Carrier            | Fixed value | Value per km/kg |
|:-------------------|------------:|----------------:|
| DHL                |    R$ 10,00 |         R$ 0,05 |
| FedEx              |     R$ 4,30 |         R$ 0,12 |
| Loggi (up to 5kg)  |     R$ 2,10 |         R$ 1,10 |
| Loggi (5kg and up) |    R$ 10,00 |         R$ 0,01 |

<div id='use_cases'></div> 

### Use cases

- [Dispatch with lowest cost](docs/USE_CASES.md#dispatch-with-lowest-cost)

<div id='queries'></div> 

### Queries

- [Query dispatch with lowest cost](docs/QUERIES.md#query-dispatches-with-lowest-cost)

<div id='installation'></div> 

## Installation

<div id='repository'></div> 

### Repository

To clone the repository using the command line, run:

```bash
git clone https://github.com/gustavofreze/cheap-delivery.git
```

<div id='configuration'></div> 

### Configuration

To install project dependencies locally, run:

```bash
make configure
```

To start the application containers, run:

```bash
make start
```

<div id='tests'></div> 

### Tests

Run only unit tests:

```bash
make unit-test
```

Run only integration tests:

```bash
make integration-test
```

Run all tests:

```bash
make test 
```

Displays coverage reports in the browser:

```bash
make show-coverage 
```

<div id='review'></div> 

### Review

Run static code analysis:

```bash
make review 
```

> You can check other available commands by running `make help`.

## Environment Setup

### Access URLs

| Environment | DNS                             | 
|:------------|:--------------------------------|
| `Local`     | http://cheap-delivery.localhost |

### Database

| Environment | URL                         | Port | 
|:------------|:----------------------------|:----:|
| `Local`     | jdbc:mysql://localhost:3307 | 3307 |
