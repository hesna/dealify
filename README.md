# Dealify
> Where you can create various deals and surprise your customers at checkout!
---
Dealify is a lightweight checkout system that allows you to create products, define various bundle-based pricing rules for them, and apply them on a basket of products while checking out.

## Features
Dealify v1 provides the minimum features needed to support its main duty, applying price rules on check out. it includes:

### Product Management
You may create a product, update it and get its data. we have following APIs to deal with a product: 
- `create product` POST /products
- `update product` PUT /products/{pid}
- `show product` GET /products/{pid}
---
##### POST /products
**Parameters** 

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `name` | required | string  | The product name. should be at most 255 characters. |
|     `price` | required | integer  | The product price. should be a numberc between 10 and 500 |

**Response**

```json
// sucsessful creation HTTP 201
{
    "name": "shiny new thing",
    "price": "70",
    "updated_at": "2021-03-18T09:50:09.000000Z",
    "created_at": "2021-03-18T09:50:09.000000Z",
    "id": 21
}

// or any kind of validation error HTTP 422
{
    "name": [
        "The name field is required."
    ],
    "price": [
        "The price must be between 10 and 500."
    ]
}
 ```
---
##### PUT /products/{pid}
**Parameters** 

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `name` | required | string  | The product name. should be at most 255 characters. |
|     `price` | required | integer  | The product price. should be a numberc between 10 and 500 |

**Response**
```json
// sucsessful update HTTP 200
{
    "id": 21,
    "name": "my updated title",
    "price": "70",
    "updated_at": "2021-03-18T09:50:09.000000Z",
    "created_at": "2021-03-18T09:50:09.000000Z",
}

// or any kind of validation error HTTP 422
{
    "price": [
        "The price field is required."
    ]
}
 ```
---
##### Get /products/{pid}
**Response**
```json
// sucsessful HTTP 200
{
    "id": 4,
    "name": "unicorn's horn",
    "price": "250",
    "updated_at": "2021-03-18T09:50:09.000000Z",
    "created_at": "2021-03-18T09:50:09.000000Z",
}
 ```

### Deal Management
You may set multiple deals for a product, delete all deals of a product and get a product's deals list. 
note that setting deals for a products, removes its old deals and replaces them with new ones.
we have following APIs to deal with product deals: 
- `set product deals` POST /products/{pid}/deals
- `show product deals` GET /products/{pid}/deals
- `delete product deals` DELETE /products/{pid}/deals
---

##### POST /products/{pid}/deals
**Request Body** 
client can provide the pricing rules for a product in a json format. each deal should include number of products, and discounted price.
validations are in place to make sure requirements are met. number must be between 2 and 50, and most importantly a client must not pass two different rules for a single number of products.

```json
{
    "deals": [
        {
            "number_of_products": 5,
            "price": 250
        },
        {
            "number_of_products": 3,
            "price": 170
        }
    ]
}
```

**Response**

```json
// sucsessful creation HTTP 201
[
    {
        "id": 46,
        "product_id": 5,
        "number_of_products": 3,
        "price": 170,
        "unit_price": 56.7,
        "created_at": "2021-03-18T10:38:19.000000Z",
        "updated_at": "2021-03-18T10:38:19.000000Z"
    },
    {
        "id": 45,
        "product_id": 5,
        "number_of_products": 5,
        "price": 250,
        "unit_price": 50,
        "created_at": "2021-03-18T10:38:19.000000Z",
        "updated_at": "2021-03-18T10:38:19.000000Z"
    }
]
// or any kind of validation error HTTP 422
{
    "deals.1.price": [
        "The deals.1.price field is required."
    ],
    "deals.0.number_of_products": [
        "The deals.0.number_of_products must be between 2 and 50."
    ]
}
// happens when there are two different rules for say 5 products HTTP 422
{
    "deals": [
        "the value of number_of_products must be unique within the given input."
    ]
}
 ```
---

##### GET /products/{pid}/deals
**Response**
```json
// sucsessful HTTP 200
[
    {
        "id": 44,
        "product_id": 5,
        "number_of_products": 2,
        "price": 115,
        "unit_price": 57.5,
        "created_at": "2021-03-18T08:22:56.000000Z",
        "updated_at": "2021-03-18T08:22:56.000000Z"
    },
    {
        "id": 42,
        "product_id": 5,
        "number_of_products": 3,
        "price": 170,
        "unit_price": 56.7,
        "created_at": "2021-03-18T08:22:56.000000Z",
        "updated_at": "2021-03-18T08:22:56.000000Z"
    },
    {
        "id": 41,
        "product_id": 5,
        "number_of_products": 5,
        "price": 250,
        "unit_price": 50,
        "created_at": "2021-03-18T08:22:56.000000Z",
        "updated_at": "2021-03-18T08:22:56.000000Z"
    }    
]
```
---


##### DELETE /products/{pid}/deals
**Response**
```json
// sucsessful HTTP 204
null
```

### Checkout
Finally you may give an array of product ids and you check in customer basket, and dealify will provide you with checkout and receipt info.

- `checkout` POST /checkout
---

##### POST /checkout
**Request Body** 
client easily sends an array of ids, in any order. ids only need to map to an existing product.

```json
{
    "products": [
        {
            "id": 5
        },
        {
            "id": 5
        },        
        {
            "id": 13
        },
        {
            "id": 10
        },
        {
            "id": 5
        },
        {
            "id": 3
        },
        {
            "id": 3
        },
        {
            "id": 5
        },
        {
            "id": 5
        },
        {
            "id": 5
        },
        {
            "id": 5
        },
        {
            "id": 5
        },
        {
            "id": 5
        }                                        
    ]
}
```

**Response**
he/she will recieve a report consisting a list of products he's buying, deals applied on his basket, total price before and after applying deals and amount of discounts he's gained. 

```json
// product with id not exists or any kind of validation error HTTP 422
{
    "products.1.id": [
        "The selected products.1.id is invalid."
    ]
}

{
    "products": [
        {
            "name": "quo",
            "price": 20,
            "count": 2
        },
        {
            "name": "hic",
            "price": 60,
            "count": 12
        },
        {
            "name": "voluptatem",
            "price": 50,
            "count": 1
        },
        {
            "name": "recusandae",
            "price": 50,
            "count": 2
        }
    ],
    "applied_deals": [
        {
            "product_name": "hic",
            "number_of_products": 5,
            "price": 250,
            "match_times": 2
        },
        {
            "product_name": "hic",
            "number_of_products": 2,
            "price": 115,
            "match_times": 1
        }
    ],
    "total_raw_price": 910,
    "total_price": 805,
    "total_discount": 105
}
```

**IMPORTANT NOTE**
When faced with multiple deals applicable on a basket, Dealify applies the best combination for user to earn most discount.
under the hood, it is calculating the amount of discount each deal is offering, and tries to apply most profitable deals first.
in above example, given 12 products with code 5, Dealify applied `5*5` twice and `5*2` once, while there is another `5*3` rule also available. 


## Installation
You may clone the repo and deploy it like any other standard laravel project.
Don't forget to run migrations first:
`php artisan migrate`

There are also db seeds provided to help you kickstart the project and get to the checkout easily. seeder creates a number of products and basic but logical deals for them:
`php artisan db:seed`  

Also there is a Postman collection provided in repo to make working with APIs easier.

## Notables
- As mentioned before, Dealify handles multiple **same name rules** in a way most profit is gained by user. 
- Under the hood, there are core classes and services that are framework agnostic. It's an attemp to reach a **cleaner architecture** that allows easier expansion and maintenance. 
- When time comes for **scaling up**, the checkout endpoint can be deployed as an independent service and run on multiple nodes, calculating different baskets at the same time. Thanks to aformentioned core services, main calculation logic is framework & db independent and can adopt suitable technologies if needed.
- Dealify already can deal with **1000's of rules**. It only loads applicable deals for each basket, and won't load even one unrelated deal. it does not matter how many products or deals are there, only the ones related to the current basket are needed.
- when a **fault** occurs in calculation process, there could be a simple checkout process that ignors the deals and just reports bought products and total price. of course customer has the right to know system has crashed and decide to come back later to profit from the deals.
- Dealify tries to prodive easy-used APIs for **operation** team. deals usually come in big CSV files and it's easy to create json request like ones Dealify expects from these files. of course, in future we might consider allowing CSV files as input, it would require little effort as core system does not care about input formats.


## TODOs
There are many enhancements still applicable on Dealify, most important ones include:
- Write unit and feature tests for system
- Dockerise the app
- Make deals related to a product invalid, if the price of the product is changed.
- Further refactor core services and objects

---

Developed with Laravel framework.
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="100">
As requested by Devolon.
<img src="https://media-exp1.licdn.com/dms/image/C560BAQE0KP4uvwVOGg/company-logo_200_200/0/1519874975273?e=2159024400&v=beta&t=CkfwEoN1f15LYPPpzpLnceXBQ-lOz4MxfTTlHeODoJg" width="100">





