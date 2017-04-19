# aws-ecs-php-micro-service

This project is a proof of concept of hwo a php architecture micro service.
With a full example provided from development to production.

## Requirement

  - The project is based on Docker container technology
  - The production environment will be running on AWS ECS
  - All related command are Unix like
  
## Goals

  This POC will consist of a mini web store, where
  we can get a list of products, simulate purchase and shipment.
  
  For this purpose, we will split it in three applications.
  All designed in API-Centric.
  
    - Authentication Service
    - InStore
    - Warehouse

  The `Authentication service` will handle signUp,token and ACL.
  The `InStore` will represent the showroom of products.
  The `Warehouse` will be the operation part of product, store and shipment.

## Services Architecture

  In order to make this Proof Of Concept much easier to understand and try,
  all the services will be under the same repository.
  
  Disclaimer: Some of best practice has been volontary avoid in order to
              keep it simple and much easier. For this reason, you will 
              not see any framework, cache or specific design pattern.

  Each service will have two API endpoint, `public.php` and `internal.php`
  respectively use from world wide access and intra-service communication.

## Authentication service

### Public resources

Will be listening on port 8081
    
  - Sign Up - `POST` /auth/signup
    `Request`
    ```json
    # Content-Type:application/vnd.api+json
    {
      "nickname": "Jane",
      "password": "super secure password"
    }
    ```
    
  - Login - `POST` /auth/login
    `Request`
    ```json
    # Content-Type:application/vnd.api+json
    {
      "nickname": "Jane",
      "password": "super secure password"
    }
    ```
      
### Internal resources

Will be listening on port 80

  - Permissions - `GET` /auth/permissions?$token
      
## InStore service

### Public resources
    
Will be listening on port 8082

  - List Products - `GET` /instore/products
  - Purchase product - `POST` /instore/product/$id/command/purchase
    `Request`
    ```json
    # Content-Type:application/vnd.api+json
    {
      "quantity": 1,
      "delivery_address": "Luxembourg"
    }
    ```
      
### Internal resources

Will be listening on port 80

  - Update price - `PUT` /instore/product/$id
    `Request`
    ```json
    # Content-Type:application/vnd.api+json
    {
      "price": 20
    }
    ```
   
## Warehouse Service

### Public resources

Will be listening on port 8083
    
  - Shipment Status - `GET` /warehouse/shipment/$id
  - Increment Product quantity - `POST` /warehouse/inventory/$id/command/increment
    `Request`
    ```json
    # Content-Type:application/vnd.api+json
    {
      "quantity": 1,
      "location": "5C"
    }
    ```
  - Decrement Product quantity - `POST` /warehouse/inventory/$id/command/decrement
    `Request`
    ```json
    # Content-Type:application/vnd.api+json
    {
      "quantity": 2,
      "location": "AE"
    }
    ```
      
### Internal resources

Will be listening on port 80

  - Shipping product - `POST` /warehouse/purchase
    `Request`
    ```json
    # Content-Type:application/vnd.api+json
    {
      "product_id": "xxx-xxx-xxx",
      "price": 15,
      "description": "",
      "purchase_date": "",
      "delivery_address": "Luxembourg",
    }
    ```
