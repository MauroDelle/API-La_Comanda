<?php
require_once './models/Mesa.php';
require_once './models/EstadoMesa.php';
require_once './Interfaces/IInterfazAPI.php';

class MesaController extends Mesa implements IInterfazAPI
{

    public static function CargarUno($request, $response, $args){

        $parametros = $request->getParsedBody();
        $mesa = new Mesa();
    
        Mesa::crear($mesa);
    
        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function TraerUno($request, $response, $args){

    $valor = $args['mesa'];
    $mesa = Mesa::obtenerUno($valor);
    $payload = json_encode($mesa);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');

    }
    public static function TraerTodos($request, $response, $args){

        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public static function BorrarUno($request, $response, $args){

      $id = $args['id'];

      if (Mesa::obtenerUno($id)) {
        Mesa::borrar($id);
        $payload = json_encode(array("mensaje" => "mesa borrada con exito"));
      } else {
        $payload = json_encode(array("mensaje" => "ID no coinciden con ninguna Mesa"));
      }
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public static function ModificarUno($request, $response, $args){

      $id = $args['id'];

      $mesa = Mesa::obtenerUno($id);
  
      if ($mesa != false) {
        $parametros = $request->getParsedBody();
  
        $actualizado = false;
        if (isset($parametros['estado'])) {
          $actualizado = true;
          $mesa->estado = $parametros['estado'];
        }
  
        if ($actualizado) {
          Mesa::modificar($mesa);
          $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
        } else {
          $payload = json_encode(array("mensaje" => "Mesa no modificada por falta de campos"));
        }
      } else {
        $payload = json_encode(array("mensaje" => "ID no coinciden con ninguna Mesa"));
      }
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}


?>