# Mini E-commerce REST API  
**Built with Laravel 10 + Sanctum Authentication**

This project is a simple, clean, fully functional **REST API** for a mini E-commerce system.  
It includes **user authentication**, **admin-only product management**, **order creation**, **pagination**, and **search**.

---

## Features
- User Registration & Login (Laravel Sanctum Token)
- Product Listing (search + pagination)
- Product Creation (Admin Only)
- Order Creation (Stock Validation + Order Items)
- Middleware + Validation
- Pagination + Search
- JSON API responses
- Developer-friendly structure

---

## Tech Stack
| Component | Version |
|----------|---------|
| PHP | **8.2** |
| Laravel | **10.x** |
| DB | MySQL |
| Auth | **Laravel Sanctum** |
| API Testing | Postman |

---

## Installation & Setup

### Clone Project
```bash
git clone https://github.com/anik4997/ecommerce-api.git
cd ecommerce-api

```
## Install Dependencies

### Composer install
```bash
composer install

```
## Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

## Install Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## Run Migrations
```bash
php artisan migrate
```

## API BASE URL
```bash
http://localhost:8084/api

```

## API Endpoints (With Live Tested Responses)
POST /api/register
Body->
```bash

{
    "name": "Oli Ahammed",
    "email": "oli@test.com",
    "password": "password123",
    "password_confirmation": "password123"
}

```
## Response
{
    "user": {
        "name": "Oli Ahammed",
        "email": "oli@test.com",
        "is_admin": false,
        "updated_at": "2025-12-02T18:44:18.000000Z",
        "created_at": "2025-12-02T18:44:18.000000Z",
        "id": 1
    },
    "token": "1|jdul8J2MD40GTE3qeW8p1k5NoViyRzNB16p5K2GY575853a5"
}

## Login 

POST /api/login
Body->
```bash

{
    "email": "oli@test.com",
    "password": "password123"
}


```
## Response
{
    "user": {
        "name": "Oli Ahammed",
        "email": "oli@test.com",
        "is_admin": false,
        "updated_at": "2025-12-02T18:44:18.000000Z",
        "created_at": "2025-12-02T18:44:18.000000Z",
        "id": 1
    },
    "token": "1|jdul8J2MD40GTE3qeW8p1k5NoViyRzNB16p5K2GY575853a5"
}

## Create Product (Admin Only)

POST /api/products
Body:

Wrong/No Token Response:

## Response
{
    "message": "Unauthenticated."
}
## Create Product (Admin Only)

POST /api/products
Body->
```bash

{
    "title": "Test Product",
    "description": "Test desc",
    "price": 99.99,
    "stock": 20
}


```
## Response
{
    "title": "Test Product",
    "description": "Test desc",
    "price": 99.99,
    "stock": 20,
    "updated_at": "2025-12-02T19:29:00.000000Z",
    "created_at": "2025-12-02T19:29:00.000000Z",
    "id": 1
}

## Create Order

POST /api/orders
Body->
```bash

{
    "items":[{"product_id":1,"quantity":2}],
    "shipping_address":{"street":"dhanmondi 27","city":"Dhaka"}
}

```
## Response
{
    "order": {
        "user_id": 1,
        "total_amount": 199.98,
        "shipping_address": {
            "street": "dhanmondi 27",
            "city": "Dhaka"
        },
        "status": "pending",
        "updated_at": "2025-12-02T19:52:20.000000Z",
        "created_at": "2025-12-02T19:52:20.000000Z",
        "id": 1,
        "items": [
            {
                "id": 1,
                "order_id": 1,
                "product_id": 1,
                "quantity": 2,
                "unit_price": "99.99",
                "line_total": "199.98",
                "created_at": "2025-12-02T19:52:20.000000Z",
                "updated_at": "2025-12-02T19:52:20.000000Z",
                "product": {
                    "id": 1,
                    "title": "Test Product",
                    "description": "Test desc",
                    "price": "99.99",
                    "stock": 18,
                    "created_at": "2025-12-02T19:29:00.000000Z",
                    "updated_at": "2025-12-02T19:52:20.000000Z"
                }
            }
        ]
    }
}

### Authentication

    Authorization: Bearer YOUR_TOKEN
    Accept: application/json
    Content-Type: application/json