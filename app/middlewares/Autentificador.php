<?php
require_once "./models/Empleado.php";
require_once './middlewares/AutentificadorJWT.php';

class Autentificador
{
    public static function ValidarSocio($request, $handler)
    {
        $cookies = $request->getCookieParams();
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);

        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);

        if ($payload->rol == 'socio') {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }

    public static function ValidarMozo($request, $handler)
    {
        $cookies = $request->getCookieParams();
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);

        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);


        if ($payload->rol == 'socio' || $payload->rol == 'mozo' /*&& Usuario::ValidarExpiracionToken($token)*/) {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }

    public static function ValidarPreparador($request, $handler)
    {
        $cookies = $request->getCookieParams();
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);
        AutentificadorJWT::VerificarToken($token);
        $payload = AutentificadorJWT::ObtenerData($token);

        if ($payload->rol == 'socio' || $payload->rol == 'cocinero' || $payload->rol == 'cervecero' || $payload->rol == 'bartender') {
            return $handler->handle($request);
        }

        throw new Exception("Token no valido");
    }


}


?>