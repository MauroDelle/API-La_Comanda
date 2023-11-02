<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Cookie\Cookie;


require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/Autentificador.php';
require_once './middlewares/Validador.php';
require_once './middlewares/Logger.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/EncuestaController.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
//$app->setBasePath('/app');

// Add error middleware
$errorMiddleware = function ($request, $exception, $displayErrorDetails) use ($app) {
  $statusCode = 500;
  $errorMessage = $exception->getMessage();
  $response = $app->getResponseFactory()->createResponse($statusCode);
  $response->getBody()->write(json_encode(['error' => $errorMessage]));

  return $response->withHeader('Content-Type', 'application/json');
};

$app->addErrorMiddleware(true, true, true)
  ->setDefaultErrorHandler($errorMiddleware);

$app->addBodyParsingMiddleware();


// ABM Routes


$app->run();
?>