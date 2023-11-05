<?php
require_once './models/Producto.php';
require_once './interfaces/IInterfazAPI.php';

class ProductoController extends Producto implements IInterfazAPI
{
    public static function CargarUno($request,$response,$args)
    {
        $params = $request->getParsedBody();

        $sector = $params['sector'];
        $nombre = $params['nombre'];
        $precio = $params['precio'];
        $tiempo_estimado = $params['tiempo_estimado'];

        $producto = new Producto(); 
        $producto->sector = $sector;
        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->tiempo_estimado = $tiempo_estimado;

        Producto::crear($producto);

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);

        return $response->withHeader("Content-Type","application/json");
    }
    public static function TraerUno($request, $response, $args){

        $producto = $args['producto'];

        $producto = Producto::obtenerUno($producto);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public static function TraerTodos($request, $response, $args){
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public static function ModificarUno($request, $response, $args){

        $id = $args['id'];

        $producto = Producto::obtenerUnoPorId($id);
    
        if ($producto != false) {
          $parametros = $request->getParsedBody();
    
          $actualizado = false;
          if (isset($parametros['sector'])) {
            $actualizado = true;
            $producto->sector = $parametros['sector'];
          }
          if (isset($parametros['nombre'])) {
            $actualizado = true;
            $producto->nombre = $parametros['nombre'];
          }
          if (isset($parametros['precio'])) {
            $actualizado = true;
            $producto->precio = $parametros['precio'];
          }
          if (isset($parametros['tiempo_estimado'])) {
            $actualizado = true;
            $producto->tiempo_estimado = $parametros['tiempo_estimado'];
          }
    
          if ($actualizado) {
            Producto::modificar($producto);
            $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
          } else {
            $payload = json_encode(array("mensaje" => "Producto no modificar por falta de campos"));
          }
    
        } else {
          $payload = json_encode(array("error" => "Producto no existente"));
        }
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');



    }
    public static function BorrarUno($request, $response, $args)
    {
      $id = $args['id'];
      
      if(Producto::obtenerUno($id))
      {
        Producto::borrar($id);
        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
      }else
      {
        $payload = json_encode(array("mensaje" => "ID no coincide con un Producto"));
      }

      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }
}

?>