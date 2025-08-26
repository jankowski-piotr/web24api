# Company and Employee Management - REST API

## Introduction
This project is a RESTful API built with the Laravel framework, designed to manage company and employee data. The application provides full CRUD operations for both resources (companies, employees) and adheres to modern software development best practices.

## Features
- Full CRUD operations for companies (name, tax number, address, city, postal code).
- Full CRUD operations for employees (first name, last name, email, phone number - optional).
- All data is persisted in a database.
- Implementation of the Repository pattern.
- API documentation in OpenAPI format.

## API Documentation (OpenAPI)
The API specification documentation is available in JSON format at the following address:  
`public/docs/api/v1/openapi.json`

To update the documentation after making changes to the code, run the following command:
```bash
./vendor/bin/openapi --output public/docs/api/v1/openapi.json app/Http/Controllers app/Http/Requests app/Http/Resources
```

## Requirements

The following components are required to run the project:

- PHP (recommended version 8.2+)

- Composer

- Database (e.g., MySQL, PostgreSQL)

## Installation and Setup

- Clone the repository:

        git clone <URL_REPOSITORIUM>

- Configure environment variables by creating a .env file and generating the application key:

        cp .env.example .env

- Edit the .env file to configure your database connection 

        DB_CONNECTION, DB_DATABASE, DB_USERNAME, DB_PASSWORD

- Run the development server:

        docker compose up -d

- Run inside container

        php artisan key:generate
        php artisan migrate
        php artisan db:seed

    ℹ️ Creates a default user with a Bearer Token via seeding.

        test@example.com
        password

# Testing

This project includes a suite of tests to ensure the API's functionality. You can run all of the tests using the following command:

        php artisan test


The base URL for the API is /api/v1. All CRUD endpoints are available from this base URL. You can use tools such as Insomnia/Postman or cURL to interact with the API.