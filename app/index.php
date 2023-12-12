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
require_once './controllers/FacturaController.php';
require_once './controllers/EncuestaController.php';

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

#region app->group's

// LOG IN 
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EmpleadoController::class . '::LogIn')->add(\Logger::class . '::ValidarLogin');
});

$app->group('/empleado', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EmpleadoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio')->add(\Validador::class . '::ValidarNuevoEmpleado');
  $group->put('/{id}', \EmpleadoController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \EmpleadoController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('[/]', \EmpleadoController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/{empleado}', \EmpleadoController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->post('[/]', \ProductoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \ProductoController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \ProductoController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('[/]', \ProductoController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/{producto}', \ProductoController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . '::TraerTodos')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/{mesa}', \MesaController::class . '::TraerUno')->add(\Autentificador::class . '::ValidarSocio');


  $group->get('/cuenta/{codigoPedido}', \MesaController::class . '::CuentaMesa');
  $group->get('/cobrar/{codigoPedido}', \MesaController::class . '::CobrarMesa');
  $group->get('/cerrar/{id}', \MesaController::class . '::CerrarMesa');
  $group->get('/usos/', \MesaController::class . '::UsosMesa');


  $group->post('[/]', \MesaController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \MesaController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \MesaController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/pedido', function (RouteCollectorProxy $group) {
  
  $group->post('[/]', \PedidoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \PedidoController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \PedidoController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('[/]', \PedidoController::class . '::TraerTodos');

  $group->post('/inicio/{id}', \PedidoController::class . '::IniciarPedido')->add(\Autentificador::class . '::ValidarSocio');
  $group->post('/final/{id}', \PedidoController::class . '::FinalizarPedido');
  $group->post('/entregar/{id}', \PedidoController::class . '::EntregarPedido');
  $group->get('/listos', \PedidoController::class . '::TraerListos');
  $group->get('/pendientes', \PedidoController::class . '::TraerPendientes');

  
  // $group->get('/{codigoMesa}-{codigoPedido}', \PedidoController::class . '::TraerPedidosMesa');
});

$app->group('/productoCSV', function (RouteCollectorProxy $group) {
  $group->post('/load', \ProductoController::class . '::Cargar');
  $group->get('/download', \ProductoController::class . '::Descargar');
});


$app->group('/factura', function (RouteCollectorProxy $group) {
  $group->post('[/]', \FacturaController::class . '::CargarUno');
});

$app->group('/encuesta', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EncuestaController::class . '::CargarUno');
  $group->get('/mejores', \EncuestaController::class . '::TraerMejores');
});



#endregion

$app->run();

?>