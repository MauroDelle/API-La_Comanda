<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/EmpleadoController.php';
require_once './db/DataAccess.php';
require_once './controllers/ProductoController.php';

// Carga el archivo .env con la configuracion de la BD.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
// Instantiate App
$app = AppFactory::create();
$app->setBasePath('/LaComanda');
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->group('/empleado', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EmpleadoController::class . '::CargarUno');
  $group->put('/{id}', \EmpleadoController::class . '::ModificarUno');
  $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno');
  $group->get('[/]', \EmpleadoController::class . '::TraerTodos');
  $group->get('/{empleado}', \EmpleadoController::class . '::TraerUno');
});

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->post('[/]', \ProductoController::class . '::CargarUno');
  $group->put('/{id}', \ProductoController::class . '::ModificarUno');
  $group->delete('/{id}', \ProductoController::class . '::BorrarUno');
  $group->get('[/]', \ProductoController::class . '::TraerTodos');
  $group->get('/{producto}', \ProductoController::class . '::TraerUno');
});

$app->run();
?>