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
    
    public static function TraerUno($request, $response, $args){

        $empleado = $args['empleado'];

        $empleado = Empleado::obtenerUno($empleado);
        $payload = json_encode($empleado);
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public static function TraerTodos($request, $response, $args){
        
    $lista = Empleado::obtenerTodos();
    $payload = json_encode(array("listaEmpleados" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
    }
    public static function BorrarUno($request, $response, $args){

      $id = $args['id'];

      if (Empleado::obtenerUnoPorId($id)) {
  
        Empleado::borrar($id);
        $payload = json_encode(array("mensaje" => "Empleado borrado con exito"));
      } else {
  
        $payload = json_encode(array("mensaje" => "ID no coincide con un usuario"));
      }
  
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public static function ModificarUno($request, $response, $args){

      $id = $args['id'];

      $empleado = Empleado::obtenerUnoPorId($id);
  
      if ($empleado != false) {
        $parametros = $request->getParsedBody();
  
        $actualizado = false;
        if (isset($parametros['nombre'])) {
          $actualizado = true;
          $empleado->nombre = $parametros['nombre'];
        }
        if (isset($parametros['rol'])) {
          $actualizado = true;
          $empleado->rol = $parametros['rol'];
        }
  
        if ($actualizado) {
          Empleado::modificar($empleado);
          $payload = json_encode(array("mensaje" => "empleado modificado con exito"));
        } else {
          $payload = json_encode(array("mensaje" => "empleado no modificar por falta de campos"));
        }
  
      } else {
        $payload = json_encode(array("error" => "empleado no existe"));
      }
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}




?>