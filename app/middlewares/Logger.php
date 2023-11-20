<?php
require_once "./models/Empleado.php";


class Logger
{
    public static function ValidarLogin($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $usuario = Empleado::obtenerUnoPorClave($nombre,$clave);


        if ($usuario != false) {
            return $handler->handle($request);
        }

        throw new Exception("Usuario y/o clave erroneos");
    }
}