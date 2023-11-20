<?php

#region DEPENDENCIAS
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
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';

require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/Autentificador.php';
require_once './middlewares/Validador.php';
require_once './middlewares/Logger.php';


// Carga el archivo .env con la configuracion de la BD.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
// Instantiate App
$app = AppFactory::create();
$app->setBasePath('/LaComanda');
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

#endregion


$app->group('/empleado', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EmpleadoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio')->add(\Validador::class . '::ValidarNuevoEmpleado');
  $group->put('/{id}', \EmpleadoController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('[/]', \EmpleadoController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/{empleado}', \EmpleadoController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->post('[/]', \ProductoController::class . '::CargarUno');
  $group->put('/{id}', \ProductoController::class . '::ModificarUno');
  $group->delete('/{id}', \ProductoController::class . '::BorrarUno');
  $group->get('[/]', \ProductoController::class . '::TraerTodos');
  $group->get('/{producto}', \ProductoController::class . '::TraerUno');
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->post('[/]', \MesaController::class . '::CargarUno');
  $group->put('/{id}', \MesaController::class . '::ModificarUno');
  $group->delete('/{id}', \MesaController::class . '::BorrarUno');
  $group->get('[/]', \MesaController::class . '::TraerTodos');
});

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->post('[/]', \PedidoController::class . '::CargarUno');
  $group->put('/{id}', \PedidoController::class . '::ModificarUno');
  $group->delete('/{id}', \PedidoController::class . '::BorrarUno');
  $group->get('[/]', \PedidoController::class . '::TraerTodos');
});

$app->group('/productoCSV', function (RouteCollectorProxy $group) {
  $group->post('/load', \ProductoController::class . '::Cargar');
  $group->get('/download', \ProductoController::class . '::Descargar');
});


// LOG IN 
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EmpleadoController::class . '::LogIn')->add(\Logger::class . '::ValidarLogin');
});

$app->run();
?>