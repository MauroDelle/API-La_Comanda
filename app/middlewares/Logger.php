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
        var_dump($usuario);


        if ($usuario != false) {
            $acceso = new Acceso();
            $acceso->idUsuario = $usuario->id;
            $acceso->fechaHora = date('Y-m-d H:i:s');
            // Aquí establecemos el tipo de transacción basado en el tipo de usuario
            $acceso->tipoTransaccion = "Login-" . ucfirst($usuario->rol);
            Acceso::crear($acceso);
            return $handler->handle($request);
        }

        throw new Exception("Usuario y/o clave erroneos");
    }
}