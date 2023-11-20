<?php
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './models/Producto.php';
require_once './models/EstadoMesa.php';
require_once './Interfaces/IInterfazAPI.php';
require_once './models/PedidoProducto.php';


class PedidoController extends Pedido implements IInterfazAPI
{

  public static function CargarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
      $uploadedFiles = $request->getUploadedFiles();

      $idMesa = $parametros['idMesa'];
      $idProducto = $parametros['idProducto'];
      $nombreCliente = $parametros['nombreCliente'];

      $pedido = new Pedido();
      $pedido->idMesa = $idMesa;
      $pedido->idProducto = $idProducto;
      $pedido->nombreCliente = $nombreCliente;

    if (isset($uploadedFiles['fotoMesa'])) {
      $targetPath = './img/' . date_format(new DateTime(), 'Y-m-d_H-i-s') . '_' . $nombreCliente . '_Mesa_' . $idMesa . '.jpg';
      $uploadedFiles['fotoMesa']->moveTo($targetPath);
      $pedido->fotoMesa = $targetPath;
    }


      $mesa = Mesa::obtenerUno($idMesa);

      if ($mesa->estado == Estado::CERRADA) {
        $pedido->codigoPedido = Mesa::CodigoAleatorio(5);
        $mesa->estado = Estado::ESPERANDO;
        Mesa::modificar($mesa);
      } else {
        $pedido->codigoPedido = Pedido::obtenerUltimoCodigo($idMesa);
      }

      Pedido::crear($pedido);

      $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }


  public static function TraerUno($request, $response, $args)
  {

    $valor = $args['id'];
    $pedido = Pedido::obtenerUno($valor);
    $payload = json_encode($pedido);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public static function TraerTodos($request, $response, $args)
  {

    $lista = Pedido::obtenerTodos();
    $payload = json_encode(array("listaPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public static function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $pedidoId = $parametros['pedidoId'];
    Pedido::borrar($pedidoId);

    $payload = json_encode(array("mensaje" => "pedido borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public static function ModificarUno($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    Pedido::modificar($nombre);

    $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
