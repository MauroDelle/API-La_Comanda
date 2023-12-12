<?php
require_once "./models/Empleado.php";
require_once './middlewares/AutentificadorJWT.php';
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Autentificador
{
    public static function ValidarSocio(Request $request,RequestHandler $handler) : Response
    {
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);
        $response = new Response();

            try {
                //AutentificadorJWT::VerificarToken($token);
                $payload = AutentificadorJWT::ObtenerData($token);
                if ($payload->rol == 'socio') {
                    return $handler->handle($request);
                }
                else{
                    $response->getBody()->write(json_encode(array('Error' => "ACCION NO PERMITIDA, SOLAMENTE PARA ADMINS")));
                }
            } catch (Exception $e) {
                $response->getBody()->write(json_encode(array("Error" => $e->getMessage())));
            }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ValidarMozo($request, $handler)
    {
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);
        $response = new Response();

            try {
                //AutentificadorJWT::VerificarToken($token);
                $payload = AutentificadorJWT::ObtenerData($token);
                if ($payload->rol == 'mozo') {
                    return $handler->handle($request);
                }
                else{
                    $response->getBody()->write(json_encode(array('Error' => "ACCION NO PERMITIDA, SOLAMENTE PARA MOZOS")));
                }
            } catch (Exception $e) {
                $response->getBody()->write(json_encode(array("Error" => $e->getMessage())));
            }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ValidarCocinero($request, $handler)
    {
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);
        $response = new Response();

            try {
                //AutentificadorJWT::VerificarToken($token);
                $payload = AutentificadorJWT::ObtenerData($token);
                if ($payload->rol == 'cocinero') {
                    return $handler->handle($request);
                }
                else{
                    $response->getBody()->write(json_encode(array('Error' => "ACCION NO PERMITIDA, SOLAMENTE PARA COCINEROS")));
                }
            } catch (Exception $e) {
                $response->getBody()->write(json_encode(array("Error" => $e->getMessage())));
            }

        return $response->withHeader('Content-Type', 'application/json');
    }


}


?>