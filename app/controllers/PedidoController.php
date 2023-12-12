<?php
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './models/Producto.php';
require_once './models/EstadoMesa.php';
require_once './Interfaces/IInterfazAPI.php';
require_once './models/PedidoProducto.php';
require_once './models/Acceso.php';


class PedidoController extends Pedido implements IInterfazAPI
{

  public static function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();

    $idMesa = $parametros['idMesa'];
    $idProducto = $parametros['idProducto'];
    $nombreCliente = $parametros['nombreCliente'];
    $codigoPedido = $parametros['codigoPedido'];


    $header = $request->getHeaderLine(("Authorization"));
    $token = trim(explode("Bearer", $header)[1]);
    $data = AutentificadorJWT::ObtenerData($token);
    $dataClave = Empleado::obtenerUnoPorClave($data->nombre, $data->clave);

    $acceso = new Acceso();
    $acceso->idUsuario = $dataClave->id; // Aquí corregí de $data->ID a $data->id
    $acceso->fechaHora = date('Y-m-d H:i:s');
    $acceso->tipoTransaccion = "ALTA-PEDIDO";
    Acceso::crear($acceso);


    $pedido = new Pedido();
    $pedido->idMesa = $idMesa;
    $pedido->idProducto = $idProducto;
    $pedido->nombreCliente = $nombreCliente;
    $pedido->codigoPedido = $codigoPedido;

    if (isset($uploadedFiles['fotoMesa'])) {
      $targetPath = './img/' . date_format(new DateTime(), 'Y-m-d_H-i-s') . '_' . $nombreCliente . '_Mesa_' . $idMesa . '.jpg';
      $uploadedFiles['fotoMesa']->moveTo($targetPath);
      $pedido->fotoMesa = $targetPath;
    }

    $mesa = Mesa::obtenerUno($idMesa);

    if ($mesa->estado == Estado::CERRADA) {
      $pedido->codigoPedido = Mesa::CodigoAleatorio(5);
      var_dump($pedido->codigoPedido);
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

  public static function IniciarPedido($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $args['id'];

    $tiempoEstimado = $parametros['tiempoEstimado'];
    $pedido = Pedido::obtenerUno($id);
    var_dump($pedido);
    if ($pedido) {

      $header = $request->getHeaderLine(("Authorization"));
      $token = trim(explode("Bearer", $header)[1]);
      $data = AutentificadorJWT::ObtenerData($token);
      $dataClave = Empleado::obtenerUnoPorClave($data->nombre, $data->clave);

      $acceso = new Acceso();
      $acceso->idUsuario = $dataClave->id; // Aquí corregí de $data->ID a $data->id
      $acceso->fechaHora = date('Y-m-d H:i:s');
      $acceso->tipoTransaccion = "INICIO-PEDIDO";
      Acceso::crear($acceso);


      Producto::obtenerUno($pedido->idProducto);
      Pedido::iniciar($id, $tiempoEstimado);
      $payload = json_encode(array("mensaje" => "Pedido en Preparacion"));
    } else {
      $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Pedido"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function FinalizarPedido($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id = $args['id'];
    $pedido = Pedido::obtenerUno($id);
    var_dump($pedido->estado);
    if ($pedido && $pedido->estado == "En Preparacion") {

      $header = $request->getHeaderLine(("Authorization"));
      $token = trim(explode("Bearer", $header)[1]);
      $data = AutentificadorJWT::ObtenerData($token);
      $dataClave = Empleado::obtenerUnoPorClave($data->nombre, $data->clave);

      $acceso = new Acceso();
      $acceso->idUsuario = $dataClave->id; // Aquí corregí de $data->ID a $data->id
      $acceso->fechaHora = date('Y-m-d H:i:s');
      $acceso->tipoTransaccion = "FINALIZAR-PEDIDO";
      Acceso::crear($acceso);



      Producto::obtenerUno($pedido->idProducto);
      Pedido::finalizar($id);
      $payload = json_encode(array("mensaje" => "Pedido Finalizado"));
    } else {
      $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Pedido"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public static function EntregarPedido($request, $response, $args)
  {
    $id = $args['id'];

    $pedido = Pedido::obtenerUno($id);
    if ($pedido) {
      if ($pedido->estado == "Listo para servir") {

        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $dataClave = Empleado::obtenerUnoPorClave($data->nombre, $data->clave);

        $acceso = new Acceso();
        $acceso->idUsuario = $dataClave->id; // Aquí corregí de $data->ID a $data->id
        $acceso->fechaHora = date('Y-m-d H:i:s');
        $acceso->tipoTransaccion = "ENTREGAR-PEDIDO";
        Acceso::crear($acceso);

        Pedido::entregar($id);
        $mesa = Mesa::obtenerUno($pedido->idMesa);
        $mesa->estado = Estado::COMIENDO;
        Mesa::modificar($mesa);
        $payload = json_encode(array("mensaje" => "Pedido entregado"));
      } else {
        $payload = json_encode(array("mensaje" => "El pedido no esta listo"));
      }
    } else {
      $payload = json_encode(array("mensaje" => "ID no coinciden con ningun Pedido"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public static function TraerListos($request, $response, $args)
  {
    $listaListos = Pedido::obtenerListos();

    $payload = json_encode(array("listaPedidosListos" => $listaListos));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function TraerPendientes($request, $response, $args)
  {
    $listaPendientes = Pedido::obtenerPendientes();
    $lista = [];
    $cookies = $request->getCookieParams();
    $lista = $listaPendientes;

    foreach ($listaPendientes as $pedido) {
      if ((Producto::obtenerUno($pedido->idProducto))) {
        $lista[] = $pedido;
      }
    }

    $payload = json_encode(array("listaPedidosPendientes" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public static function obtenerLoMasVendido($request, $response, $args)
  {
    $listaListos = Pedido::loMasVendido();

    $payload = json_encode(array("listaPedidosListos" => $listaListos));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function LoMenosVendido($request, $response, $args)
  {
    $listaListos = Pedido::obtenerLoMenosVendido();

    $payload = json_encode(array("lista Pedidos Menos Vendidos" => $listaListos));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function PedidosCancelados($request, $response, $args)
  {
    $listaListos = Pedido::obtenerPedidosCancelados();

    $payload = json_encode(array("lista Pedidos Cancelados" => $listaListos));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function LaMasUsada($request, $response, $args)
  {
    $listaListos = Pedido::obtenerMesaMasUsada();

    $payload = json_encode(array("Mesa con mas cantidad de Pedidos registrados: " => $listaListos));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function LaMenosUsada($request, $response, $args)
  {
    $listaListos = Pedido::obtenerMesaMenosUsada();

    $payload = json_encode(array("Mesa con menor cantidad de pedidos fue: " => $listaListos));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
