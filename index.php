<?php
error_reporting(null);
ini_set('display_errors', 0);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/db.service.php';
require __DIR__ . '/src/crud.php';

$app = AppFactory::create();
$dbService = new Database();
$crud = new Crud($dbService->getConnection());

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

// Get all items
$app->get('/{tableName}', function ($request, $response, $args) use ($crud) {
    $tableName = $args['tableName'];
    $data = $crud->read($tableName);
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

// Create a new item
$app->post('/{tableName}', function ($request, $response, $args) use ($crud) {
    $tableName = $args['tableName'];
    $data = json_decode($request->getBody(), true);
    $id = $crud->create($tableName, $data);
    $response->getBody()->write("Item added with ID: $id");
    return $response->withStatus(201);
});

// Update an item
$app->put('/{tableName}/{id}', function ($request, $response, $args) use ($crud) {
    $tableName = $args['tableName'];
    $id = $args['id'];
    $data = json_decode($request->getBody(), true);
    if($crud->update($tableName, $id, $data)){
        $response->getBody()->write("Item updated with ID: $id");
    }

    return $response->withStatus(200);
});

// Delete an item
$app->delete('/{tableName}/{id}', function ($request, $response, $args) use ($crud) {
    $tableName = $args['tableName'];
    $id = $args['id'];
    $crud->erase($tableName, $id);
    $response->getBody()->write("Item deleted successfully.");
    return $response->withStatus(200);
});

// Run the application
$app->run();