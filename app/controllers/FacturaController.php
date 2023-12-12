<?php
include_once './models/Factura.php';
include_once './models/Mesa.php';
include_once './models/Pedido.php';

class FacturaController extends Factura
{
    public static function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigo_pedido = $parametros['codigo_pedido'];
    
        // Obtener todos los pedidos con el código especificado
        $pedidos = Pedido::obtenerTodosPorCodigo($codigo_pedido);
        var_dump($pedidos);
    
        $total = 0;
    
        $todosEntregados = true;
    
        foreach ($pedidos as $detalle) {
            // Obtener el precio del producto por su ID
            $precioProducto = Producto::obtenerPrecioPorId($detalle['idProducto']);
    
            if ($detalle['estado'] !== 'Entregado') {
                $todosEntregados = false;
            }
            if ($precioProducto !== null) {
                $total += $precioProducto;
            }
        }
    
        if ($todosEntregados && !empty($pedidos)) {
            // Acceder a los campos del primer pedido
            $idMesa = $pedidos[0]['idMesa'];
            $codigoPedido = $pedidos[0]['codigoPedido'];
    
            // Crear la instancia de Factura y guardarla
            $factura = new Factura();
            $factura->codigo_mesa = $idMesa;
            $factura->codigo_pedido = $codigoPedido;
            $factura->fecha = date('Y-m-d H:i:s'); 
            $factura->total = $total;
            Factura::crear($factura);
    
            // Respuesta JSON
            $guardadojson = json_encode(array("mensaje" => "Pedido facturado con exito"));
        } else {
            $guardadojson = json_encode(array("mensaje" => "No se puede facturar. El pedido Todavia no fue entregado"));
        }
    
        $response->getBody()->write($guardadojson);
        return $response->withHeader("Content-Type", "application/json");
    }

    
}
