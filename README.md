# Cheap Delivery

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

* [Overview](#overview)
* [Instalação](#installation)
    - [Repositório](#repository)
    - [Configuração](#settings)
* [Endpoints](#endpoints)

<div id="overview"></div> 

## Overview

A empresa XPTO realizou um sorteio entre jogadores do Brasil inteiro, porém, os brindes precisam chegar a seus
ganhadores de alguma maneira. Para tanto, é necessário implementar um sistema para calcular o menor custo para o envio
de cada brinde de acordo com a distância do ganhador, e o peso do brinde. Ao entrar em contato com as transportadoras,
recebemos as seguintes condições para o transporte:

| Empresa         | Valor fixo | Valor km/kg |
|:----------------|-----------:|------------:|
| DHL             |   R$ 10,00 |     R$ 0,05 |
| FedEx           |    R$ 4,30 |     R$ 0,12 |
| Loggi (até 5kg) |    R$ 2,10 |     R$ 1,10 |
| Loggi (+ 5kg)   |   R$ 10,00 |     R$ 0,01 |

<div id='installation'></div> 

## Instalação

<div id='repository'></div> 

### Repositório

Para clonar o repositório usando a linha de comando, execute:

```bash
git clone https://github.com/gustavofreze/cheap-delivery.git
```

<div id='settings'></div> 

### Configuração

```bash
make configure
```

<div id='endpoints'></div> 

## Endpoints

URLs de acesso:

| Ambiente | DNS                                | 
|:---------|:-----------------------------------|
| `Local`  | http://cheap-delivery.localhost:81 |

<div id="tests"></div> 

### Shipment

###### Realiza o cálculo do menor custo de envio disponível.

**POST** `{{dns}}/shipment`

**Request**

| Parâmetro         |  Tipo  | Descrição                  | Obrigatório |
|:------------------|:------:|:---------------------------|:-----------:|
| `person.name`     | String | Nome do destinatário.      |     Sim     |    
| `person.distance` | float  | Distância do destinatário. |     Sim     |    
| `product.name`    | String | Nome do produto.           |     Sim     |    
| `product.weight`  | float  | Peso do produto.           |     Sim     |    

```json
{
  "person": {
    "name": "Gustavo",
    "distance": 150.00
  },
  "product": {
    "name": "Notebook",
    "weight": 3.70
  }
}
```

**Response**

```
HTTP/1.1 200 OK
```

```json
{
  "carrier": "DHL",
  "cost": 37.75
}
```
