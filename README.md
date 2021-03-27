# Dealify
> Where you can create various deals and surprise your customers at checkout!
---
Dealify is a lightweight checkout system that allows you to create products, define various product-bundle-based pricing rules, and apply them on a basket of products while checking out.

## What is Dealify?
In a nutshell, Dealify allows you to store some product and deals info, give it a customer's basket on checkout, and returns a simple receipt and payment information.

Here is an example. You may define such data structure in Dealify:

| Name | Unit Price |  Special Deals   |
| -------------:|:--------:|:-------:|
| `A` | $50 | 2 for $88 <br> 3 for $130 <br> 5 for $200 | 
| `B` | $30 | 2 for $45  |
| `C` | $20 | -  | 
| `D` | $15 | -  |

Later you can provide it with customers' baskets and expect proper calculations:

| Products | Total Price |  Discount   |
| -------------:|:---------:|:-------:|
| `A, B`        | $80       | - | 
| `A, A`        | $88       | $12  |
| `A, A, A`     | $130      | $20  |
| `B, A, B`     | $95       | $15  |
| `C, D, B, A`  | $115      | -  |

As you see, defined deals are applied on customer baskets. 

As a **bonus**, if multiple deals are applicable on a basket, Dealify can 
decide which combination is best for customer and apply the deals in a way customers gain most discount!

|  Products | Total Price |  Discount   | Description | Dealify's Choice
| -------------:|:---------:|:-------:|:--------:|:--------:|
| `A, A, A, A`  | $200      | -     |  if there were no deals | :red_circle: |
| `A, A, A, A`  | $180      | $20   | (A,A,A) + (A) | :red_circle:
| `A, A, A, A`  | $176      | $24   | (A,A) + (A,A) | :green_circle:

## Features
Dealify v1 provides the minimum features needed to support its main duty, applying price rules on check out. it includes:

### Product Management
You may create a product, update it and get its data. we have following APIs to deal with a product: 
- `create product` POST /products
- `update product` PUT /products/{pid}
- `show product` GET /products/{pid}
---
#### POST /products
**Parameters** 

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `name` | required | string  | The product name. should be at most 255 characters. |
|     `price` | required | integer  | The product price. should be a numeric between 10 and 500 |

**Response**

*successful creation HTTP 201*
```json
{
    "data": {
        "id": 21,
        "name": "shiny new thing",
        "price": "70"
    }
}
```
*any kind of validation error HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "price": [
            "The price must be between 10 and 500."
        ]
    }
}
 ```
---
#### PUT /products/{pid}
**Parameters** 

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `name` | required | string  | The product name. should be at most 255 characters. |
|     `price` | required | integer  | The product price. should be a numeric between 10 and 500 |

**Response**

*successful update HTTP 200*
```json
{
    "data": {
        "id": 18,
        "name": "new name",
        "price": "200"
    }
}
```
*any kind of validation error HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "The name must not be greater than 255 characters."
        ],
        "price": [
            "The price field is required."
        ]
    }
}
 ```
---
#### Get /products/{pid}
**Response**

*successful HTTP 200*
```json
{
    "data": {
        "id": 1,
        "name": "The Ring",
        "price": 35
    }
}
 ```

### Deal Management
You may set multiple deals for a product, delete all deals of a product and get a product's deals list. 
note that setting deals for a products, removes its old deals and replaces them with new ones.
we have following APIs to manage product deals: 
- `set product deals` POST /products/{pid}/deals
- `show product deals` GET /products/{pid}/deals
- `delete product deals` DELETE /products/{pid}/deals
---

#### POST /products/{pid}/deals
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

*successful creation HTTP 201*
```json
{
    "data": [
        {
            "id": 29,
            "product_id": 5,
            "number_of_products": 2,
            "price": 88
        },
        {
            "id": 30,
            "product_id": 5,
            "number_of_products": 3,
            "price": 130
        },
        {
            "id": 31,
            "product_id": 5,
            "number_of_products": 5,
            "price": 200
        }
    ]
}
```
*any kind of validation error HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "deals.0.price": [
            "The deals.0.price field is required."
        ],
        "deals.1.number_of_products": [
            "The deals.1.number_of_products must be between 2 and 50."
        ]
    }
}
```
*happens when there are two different rules for say 5 products HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "deals": [
            "the value of number_of_products must be unique within the given input."
        ]
    }
}
 ```
---

#### GET /products/{pid}/deals
**Response**

*successful HTTP 200*
```json
{
    "data": [
        {
            "id": 29,
            "product_id": 5,
            "number_of_products": 2,
            "price": 88
        },
        {
            "id": 30,
            "product_id": 5,
            "number_of_products": 3,
            "price": 130
        },
        {
            "id": 31,
            "product_id": 5,
            "number_of_products": 5,
            "price": 200
        }
    ]
}
```
---

#### DELETE /products/{pid}/deals
**Response**

*successful HTTP 204 NO CONTENT*

---

### Checkout
Finally, you may give an array of product ids to check in customer basket. 
Dealify will provide you with checkout, receipt and payment info.

- `checkout` POST /checkout
---

#### POST /checkout
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
            "id": 3
        },
        {
            "id": 1
        },
        {
            "id": 5
        },
        {
            "id": 5
        },
        {
            "id": 3
        }                                        
    ]
}
```

**Response**

client will receive a report consisting a list of products customer's buying, deals applied on basket, total price before and after applying deals and amount of discounts customer has gained. 

*product with not existing id or any kind of validation error HTTP 422*
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "products.0.id": [
            "The selected products.0.id is invalid."
        ]
    }
}
```

*successful response HTTP 200*
```json
{
    "products": [
        {
            "code": "4",
            "name": "cucumber",
            "unit-price": 35,
            "count": 1
        },
        {
            "code": "5",
            "name": "carrots",
            "unit-price": 40,
            "count": 2
        },
        {
            "code": "10",
            "name": "nemo",
            "unit-price": 55,
            "count": 1
        }
    ],
    "applied_deals": [
        {
            "product_name": "carrots",
            "number_of_products": 2,
            "price": 88,
            "match_times": 1
        }
    ],
    "total_raw_price": 170,
    "total_price": 178,
    "total_discount": -8
}
```
**Multiple Rules Scenario**

*following the `A, A, A, A` example in introduction section, following response will be returned having the described data structure:*
```json
{
    "products":[
        {
            "code":"1",
            "name":"A",
            "unit-price":50,
            "count":4
        }
    ],
    "applied_deals":[
        {
            "product_name":"A",
            "number_of_products":2,
            "price":88,
            "match_times":2
        }
    ],
    "total_raw_price":200,
    "total_price":176,
    "total_discount":24
}
```
**Note:** There is also a feature test named `test_checkout_with_tricky_samename_rules()`, testing this exact scenario.

## Installation
Dealify makes use of Sail, Laravel's time-saving dockerizing tool. 
You're just simple steps away from deploying the project and starting to work on it.

0. make sure you have `git`, `docker` and `docker-compose` installed.
1. clone the project to your preferred directory.
   ```shell
   git clone git@github.com:hesna/dealify.git
   ```
2. we need to resolve project dependencies to be able to use Sail. 
   you may execute following command and not worry about php and composer on your local host.
    ```shell
    cd dealify
   
    docker run --rm \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install
    ```
3. with dependencies in place, copy `.env.example` file and change variables as suits your environment.
make sure you take care of MySQL configs.
   ```shell
    cp .env.example ./.env
    ```
4. here comes the Sail. 
    ```shell
    ./vendor/bin/sail up -d
    ```
    as quoted from Laravel docs:
    > The first time you run the Sail up command, Sail's application containers will be built on your machine. This could take several minutes. Don't worry, subsequent attempts to start Sail will be much faster.
   
5. once the containers are running, your app is ready to go.
you may take a look at `http://localhost` to see laravel's welcome page. 
   
    we're almost done!
   

6. run migrations to create database tables and schema.
   ```shell
   ./vendor/bin/sail artisan migrate
    ```
7. there are also db seeds provided to help you kickstart the project and get to the checkout easily. 
   seeder creates a number of products and basic but logical deals for them.
   ```shell
   ./vendor/bin/sail artisan db:seed
    ```
   
8. at the end, there is a [Postman Collection](https://github.com/hesna/dealify/blob/main/Dealify.postman_collection.json) provided in repo to make start working with APIs easier.

With this final step, we're done. happy discovering, using and contributing! :)

### Tests
To make sure everything is working, you may run Dealify's tests. 
don't forget to create and config a test database before running tests!
   ```shell
    cp .env ./.env.testing
   ```
You may delete everything if you wish and just overwrite db configs:
```yaml
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=dealify-test
DB_USERNAME=your-username
DB_PASSWORD=your-password
```
To run the tests:
   ```shell
   ./vendor/bin/sail test
   ```
If everything is set up correctly, you're going to be greeted by green lights everywhere:

<img width="516" alt="Dealify Tests" src="https://user-images.githubusercontent.com/2319368/112732344-04c6db80-8f57-11eb-9340-0a5e85375c5e.png">

## Notables
- As mentioned before, Dealify handles multiple **same name rules** in a way most profit is gained by the customer. 
- Under the hood, there are core contracts, classes and services that are framework agnostic. 
  It's an attempt to reach a **cleaner architecture** that allows easier testing, expansion and maintenance. 
- When time comes for **scaling up**, the checkout endpoint can be deployed as an independent service and run on multiple nodes, 
  calculating different baskets at the same time. Thanks to the aforementioned architecture, 
  main calculation logic is framework & db independent and can adopt suitable technologies if needed.
- Dealify already can deal with **1000's of rules**. It only loads applicable deals for each basket, and won't load even one unrelated deal. 
  it does not matter how many products or deals are there in database, only the ones related to the current basket are needed.
- when a **fault** occurs in calculation process, there could be a simple checkout process that ignores the deals and just reports bought products and total price. 
  of course customer has the right to know system has crashed and decide to come back later to profit from the deals.
- Dealify tries to provide easy-used APIs for **operation** team. deals usually come in big CSV files, 
  and it's easy to create json request like ones Dealify expects from these files. 
  of course, in future we might consider allowing CSV files as input, it would require little effort as core system does not care about input formats.


## TODOs
There are many enhancements still applicable on Dealify, most important ones include:
- Write more unit tests.
- Refactor core services and objects, introduce DTOs for some data arrays, etc.
- Make `ArrayValuesForKeyAreUnique` validator return better error messages.
- Make deals related to a product invalid, if the price of the product is changed.

---

Developed with Laravel framework. as requested by Devolon.

<br>
<img src="https://www.gemsdigitalmedia.com/wp-content/uploads/2017/08/laravel-logo.png" width="100" alt="laravel logo">
<img src="https://media-exp1.licdn.com/dms/image/C560BAQE0KP4uvwVOGg/company-logo_200_200/0/1519874975273?e=2159024400&v=beta&t=CkfwEoN1f15LYPPpzpLnceXBQ-lOz4MxfTTlHeODoJg" width="100" alt="devolon logo">
