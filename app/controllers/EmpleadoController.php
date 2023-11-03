<?php
use Slim\Http\Request;
use Slim\Http\Response;

require_once './models/Empleado.php';
require_once './Interfaces/IInterfazAPI.php';


class EmpleadoController extends Empleado implements IInterfazAPI
{
    public static function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $rol = $params['rol'];
        $nombre = $params['nombre'];
        $baja = $params['baja'];
        $fecha = $params['fecha_alta'];

        $empleado = new Empleado();
        $empleado->rol = $rol;
        $empleado->nombre = $nombre;
        $empleado->baja = $baja;
        $empleado->fecha_alta = date('Y-m-d H:i:s');

        Empleado::crear($empleado);
        $guardadojson = json_encode(array("mensaje" => "Empleado creado con exito"));

        $response->getBody()->write($guardadojson);
        return $response->withHeader("Content-Type","application/json");
    }
    
    public static function TraerUno($request, $response, $args){}
    public static function TraerTodos($request, $response, $args){}
    public static function BorrarUno($request, $response, $args){}
    public static function ModificarUno($request, $response, $args){}

}




?>