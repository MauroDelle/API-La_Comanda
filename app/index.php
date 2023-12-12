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
require_once './controllers/AccesoController.php';

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
  $group->get('/estadisticas/masUsada', \PedidoController::class . '::LaMasUsada');
  $group->get('/estadisticas/menosUsada', \PedidoController::class . '::LaMenosUsada');
  $group->get('/estadisticas/masFacturada', \MesaController::class . '::MesaMasFacturada');
  $group->get('/estadisticas/menosFacturada', \MesaController::class . '::MesaMenosFacturada');
  $group->get('/estadisticas/mayorImporteFacturado', \MesaController::class . '::MesaMayorImporteFacturado');
  $group->get('/estadisticas/menorImporteFacturado', \MesaController::class . '::MesaMenorImporteFacturado');
  $group->post('/estadisticas/facturacionEntreFechas', \FacturaController::class . '::facturacionEntreFechas');

  $group->post('[/]', \MesaController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->put('/{id}', \MesaController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \MesaController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->put('/{id}', \PedidoController::class . '::ModificarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->delete('/{id}', \PedidoController::class . '::BorrarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('[/]', \PedidoController::class . '::TraerTodos');

  
  $group->post('[/]', \PedidoController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarMozo');
  $group->post('/inicio/{id}', \PedidoController::class . '::IniciarPedido')->add(\Autentificador::class . '::ValidarCocinero');
  $group->post('/final/{id}', \PedidoController::class . '::FinalizarPedido')->add(\Autentificador::class . '::ValidarCocinero');
  $group->post('/entregar/{id}', \PedidoController::class . '::EntregarPedido')->add(\Autentificador::class . '::ValidarMozo');
  $group->get('/listos', \PedidoController::class . '::TraerListos')->add(\Autentificador::class . '::ValidarMozo');
  $group->get('/pendientes', \PedidoController::class . '::TraerPendientes')->add(\Autentificador::class . '::ValidarMozo');


  $group->get('/estadisticas/masVendido', \PedidoController::class . '::obtenerLoMasVendido');
  $group->get('/estadisticas/menosVendido', \PedidoController::class . '::LoMenosVendido');
  $group->get('/estadisticas/cancelados', \PedidoController::class . '::PedidosCancelados');
  
});


$app->group('/exportarPDF', function (RouteCollectorProxy $group) {
  $group->get('/{orden}', \AccesoController::class . ':ExportarOperacionesPDF')->add(\Autentificador::class . '::ValidarSocio');
});

$app->group('/productoCSV', function (RouteCollectorProxy $group) {
  $group->post('/load', \ProductoController::class . '::Cargar');
  $group->get('/download', \ProductoController::class . '::Descargar');
});

$app->group('/factura', function (RouteCollectorProxy $group) {
  $group->post('[/]', \FacturaController::class . '::CargarUno');
});

$app->group('/encuesta', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EncuestaController::class . '::CargarUno')->add(\Autentificador::class . '::ValidarSocio');
  $group->get('/mejores', \EncuestaController::class . '::TraerMejores');
});



#endregion

$app->run();

?>