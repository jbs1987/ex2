<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

//Generate the routes for access. We use api for modify to other in futures updates.

$app->group('/api', function () use ($app) {
    $app->get('/hostings', 'listarHostings');
    $app->post('/crear', 'crearHosting');
    $app->put('/actualizar/{id}', 'actualizarHosting');
    $app->delete('/eliminar/{id}', 'eliminarHosting');
  }); 
