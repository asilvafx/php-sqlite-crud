# SQLite CRUD

Simple PHP Slim Database CRUD application using SQLite3.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Contributing](#contributing)
- [License](#license)

## Introduction

This project is a simple CRUD (Create, Read, Update, Delete) application built with PHP Slim and SQLite3. It provides a basic REST API for interacting with an SQLite database.

## Features

- Middleware for JSON parsing using Express.
- CRUD operations on any specified table.
- Easy to start and deploy.

## Installation

To install and set up the project, follow these steps:

1. Clone the repository:
  ```
  git clone https://github.com/asilvafx/php-sqlite-crud.git
  cd php-sqlite-crud
  ```

2. Install the dependencies:
  ```
  composer install
  ```

## Usage
To start the server, run:
  ```
  php -S localhost:8000
  ```

The server will start on the port specified in the .env file (default is 3000).

## API Endpoints
1. Get all items from a specified table
  ```
  GET /:tableName
  ```

2. Create a new item in a specified table
  ```
  POST /:tableName
  ```

4. Update an item in a specified table
  ```
  PUT /:tableName/:id
  ```

5. Delete an item from a specified table
  ```
  DELETE /:tableName/:id
  ```

## Contributing
Contributions are welcome! Please feel free to submit a Pull Request.

## License
This project is licensed under the MIT License. See the LICENSE file for details.

