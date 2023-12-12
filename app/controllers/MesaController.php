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

    public static function CuentaMesa($request, $response, $args)
    {
      $codigoPedido = $args['codigoPedido'];
  
      $mesa = Mesa::obtenerMesaPorCodigoPedido($codigoPedido);
      if ($mesa) {
        $cuenta = Mesa::obtenerCuenta($codigoPedido);
        $mesa->estado = Estado::PAGANDO;
        Mesa::modificar($mesa);
        $payload = json_encode(array("mensaje" => "La cuenta de la mesa " . $cuenta[0]['idMesa'] . " es: $" . $cuenta[0]['SUM(pr.precio)']));
      } else {
        $payload = json_encode(array("mensaje" => "Codigo de Pedido no coinciden con ninguna Mesa"));
      }
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public static function CobrarMesa($request, $response, $args)
    {
      $codigoPedido = $args['codigoPedido'];
  
      $mesa = Mesa::obtenerMesaPorCodigoPedido($codigoPedido);
  
      if ($mesa && $mesa->estado == Estado::PAGANDO) {
        $cuenta = Mesa::obtenerCuenta($codigoPedido);
        $mesa->estado = Estado::PAGADO;
        Mesa::modificar($mesa);
        $payload = json_encode(array("mensaje" => "Se cobro de la mesa " . $cuenta[0]['idMesa'] . " es: $" . $cuenta[0]['SUM(pr.precio)']));
        //$payload = json_encode(array("mensaje" => "La cuenta de la mesa"));
      } else {
        $payload = json_encode(array("mensaje" => "Codigo de Pedido no coinciden con ninguna Mesa"));
      }
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public static function CerrarMesa($request, $response, $args)
    {
      $id = $args['id'];
  
      $mesa = Mesa::obtenerUno($id);
  
      if ($mesa && $mesa->estado == Estado::PAGADO) {
        $mesa->estado = Estado::CERRADA;
        Mesa::modificar($mesa);
        $payload = json_encode(array("mensaje" => "Mesa cerrada"));
      } else {
        $payload = json_encode(array("mensaje" => "La mesa aun no esta paga o no existe"));
      }
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public static function UsosMesa($request, $response, $args)
    {
      $mesas = Mesa::obtenerUsosMesas();
  
      var_dump($mesas);
  
      $payload = json_encode(array("mensaje" => $mesas));
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }


    public static function MesaMasFacturada($request, $response, $args)
    {
      $listaListos = Mesa::obtenerMesaMasFacturo();

      $payload = json_encode(array("La mesa que mas facturo fue: " => $listaListos));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public static function MesaMenosFacturada($request, $response, $args)
    {
      $listaListos = Mesa::obtenerMesaMenosFacturo();

      $payload = json_encode(array("La mesa que menos facturo fue: " => $listaListos));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public static function MesaMayorImporteFacturado($request, $response, $args)
    {
      $listaListos = Mesa::obtenerMesaMayorImporteFacturado();

      $payload = json_encode(array("La mesa con el mayor importe facturado fue: " => $listaListos));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public static function MesaMenorImporteFacturado($request, $response, $args)
    {
      $listaListos = Mesa::obtenerMesaMenorImporteFacturado();

      $payload = json_encode(array("La mesa con el menor importe facturado fue: " => $listaListos));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }



}


?>