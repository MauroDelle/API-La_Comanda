<?php
require_once './models/EstadoMesa.php';

class Pedido implements Ipersistencia
{

    #region ATRIBUTOS
    public $id;
    public $fotoMesa;
    public $idMesa;
    public $idProducto;
    public $nombreCliente;
    public $estado;
    public $codigoPedido;
    public $tiempoEstimado;
    public $tiempoInicio;
    public $tiempoEntregado;
    public $facturado;
    public $fechaBaja;
    #endregion

    #region CONSTRUCTOR
    public function __construct(){}

    #endregion

    #region SETTERS

    public function setFechaBaja($fechaBaja)
    {
        $this->fechaBaja = $fechaBaja;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function setCodigoPedido($codigoPedido) {
        $this->codigoPedido = $codigoPedido;
    }
    
    public function setFoto($foto) {
        $this->fotoMesa = $foto;
    }
    
    public function setIdMesa($idMesa) {
        $this->idMesa = $idMesa;
    }
    
    public function setIdProducto($idProducto) {
        $this->idProducto = $idProducto;
    }
    
    public function setNombreCliente($nombreCliente) {
        $this->nombreCliente = $nombreCliente;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function setTiempoEstimado($tiempoEstimado) {
        $this->tiempoEstimado = $tiempoEstimado;
    }
    
    // public function setIdEmpleado($idEmpleado) {
    //     $this->idEmpleado = $idEmpleado;
    // }
    
    public function setTiempoInicio($tiempoInicio) {
        $this->tiempoInicio = $tiempoInicio;
    }
    
    public function setTiempoEntregado($tiempoEntregado) {
        $this->tiempoEntregado = $tiempoEntregado;
    }
    
    public function setFacturado($facturado) {
        $this->facturado = $facturado;
    }

    #endregion

    #region GETTERS

    public function getFechaBaja()
    {
        return $this->fechaBaja;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getCodigoPedido() {
        return $this->codigoPedido;
    }
    
    public function getFoto() {
        return $this->fotoMesa;
    }
    
    public function getIdMesa() {
        return $this->idMesa;
    }
    
    public function getIdProducto() {
        return $this->idProducto;
    }
    
    public function getNombreCliente() {
        return $this->nombreCliente;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function getTiempoEstimado() {
        return $this->tiempoEstimado;
    }
    
    // public function getIdEmpleado() {
    //     return $this->idEmpleado;
    // }
    
    public function getTiempoInicio() {
        return $this->tiempoInicio;
    }
    
    public function getTiempoEntregado() {
        return $this->tiempoEntregado;
    }
    
    public function getFacturado() {
        return $this->facturado;
    }


    #endregion

    #region CRUD

    public static function crear($pedido){
        
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery('INSERT INTO pedidos (codigoPedido, fotoMesa, idMesa, idProducto, nombreCliente, estado) VALUES (:codigoPedido, :fotoMesa, :idMesa, :idProducto, :nombreCliente, :estado)');
        $query->bindValue(':codigoPedido', $pedido->codigoPedido, PDO::PARAM_STR);
        $query->bindValue(':fotoMesa', $pedido->fotoMesa, PDO::PARAM_STR);
        $query->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_INT);
        $query->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_INT);
        $query->bindValue(':nombreCliente', $pedido->nombreCliente, PDO::PARAM_STR);
        $query->bindValue(':estado', Estado::PENDIENTE, PDO::PARAM_STR);
        $query->execute();



        return $objDataAccess->getLastInsertedId();
    }
    
    public static function obtenerTodos(){

        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigoPedido, fotoMesa, idMesa, idProducto, nombreCliente, estado, tiempoEstimado, tiempoInicio, tiempoEntregado, fechaBaja FROM pedidos");

        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerUno($valor){

        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigoPedido, fotoMesa, idMesa, idProducto, nombreCliente, estado, tiempoEstimado, tiempoInicio, tiempoEntregado, fechaBaja FROM pedidos WHERE id = :valor");
        $query->bindValue(':valor', $valor, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchObject('Pedido');
    }

    public static function modificar($pedido){

        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("UPDATE pedidos SET codigoPedido = :codigoPedido, fotoMesa = :fotoMesa, idMesa = :idMesa, idProducto = :idProducto, nombreCliente = :nombreCliente, estado = :estado, tiempoEstimado = :tiempoEstimado, tiempoInicio = :tiempoInicio, tiempoEntregado = :tiempoEntregado, fechaBaja = :fechaBaja WHERE id = :id");
        $query->bindValue(':codigoPedido', $pedido->codigoPedido, PDO::PARAM_STR);
        $query->bindValue(':fotoMesa', $pedido->fotoMesa, PDO::PARAM_STR);
        $query->bindValue(':idMesa', $pedido->idMesa, PDO::PARAM_STR);
        $query->bindValue(':idProducto', $pedido->idProducto, PDO::PARAM_STR);
        $query->bindValue(':nombreCliente', $pedido->nombreCliente, PDO::PARAM_STR);
        $query->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
        $query->bindValue(':tiempoEstimado', $pedido->tiempoEstimado, PDO::PARAM_STR);
        $query->bindValue(':tiempoInicio', $pedido->tiempoInicio, PDO::PARAM_STR);
        $query->bindValue(':tiempoEntregado', $pedido->tiempoEntregado, PDO::PARAM_STR);
        $query->bindValue(':fechaBaja', $pedido->fechaBaja, PDO::PARAM_STR);
        $query->bindValue(':id', $pedido->id, PDO::PARAM_STR);
        $query->execute();
    }

    public static function borrar($objeto){}
    #endregion

    public static function obtenerUltimoCodigo($idMesa)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT codigoPedido FROM pedidos WHERE idMesa = :idMesa");
        $query->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchColumn();
    }

    public static function obtenerPendientes()
    {
        $objAccesoDatos = DataAccess::getInstance();
        $consulta = $objAccesoDatos->prepareQuery("SELECT id, codigoPedido, fotoMesa, idMesa, idProducto, nombreCliente, estado, tiempoEstimado, tiempoInicio, tiempoEntregado, fechaBaja FROM pedidos WHERE estado = :valor");
        $consulta->bindValue(':valor', Estado::PENDIENTE, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function iniciar($id, $tiempoEstimado)
    {
        $objAccesoDato = DataAccess::getInstance();
        $consulta = $objAccesoDato->prepareQuery("UPDATE pedidos SET estado = :estado, tiempoEstimado = :tiempoEstimado, tiempoInicio = :tiempoInicio WHERE id = :id");
        $fecha = new DateTime(date('H:i:s'));
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::PREPARACION, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoInicio', date_format($fecha, 'H:i:s'));
        $consulta->execute();
    }

    public static function finalizar($id)
    {
        $objAccesoDato = DataAccess::getInstance();
        $consulta = $objAccesoDato->prepareQuery("UPDATE pedidos SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::LISTO, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function entregar($id)
    {
        $objAccesoDato = DataAccess::getInstance();
        $consulta = $objAccesoDato->prepareQuery("UPDATE pedidos SET estado = :estado, tiempoEntregado = :tiempoEntregado WHERE id = :id");
        $fecha = new DateTime(date('H:i:s'));
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::ENTREGADO, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEntregado', date_format($fecha, 'H:i:s'));
        $consulta->execute();
    }
    
    public static function obtenerListos()
    {
        $objAccesoDatos = DataAccess::getInstance();
        $consulta = $objAccesoDatos->prepareQuery("SELECT id, codigoPedido, fotoMesa, idMesa, idProducto, nombreCliente, estado, tiempoEstimado, tiempoInicio, tiempoEntregado, fechaBaja FROM pedidos WHERE estado = :valor");
        $consulta->bindValue(':valor', Estado::LISTO, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }


    public static function loMasVendido()
    {
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("
            SELECT idProducto, COUNT(idProducto) AS cantidadVendida
            FROM Pedidos
            WHERE estado = 'Entregado'
            GROUP BY idProducto
            ORDER BY cantidadVendida DESC
        ");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerLoMenosVendido()
{
    $objDataAccess = DataAccess::getInstance();
    $consulta = $objDataAccess->prepareQuery("
        SELECT idProducto, COUNT(*) as cantidadVendida
        FROM pedidos
        GROUP BY idProducto
        ORDER BY cantidadVendida ASC
        LIMIT 5
    ");
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

    public static function obtenerPedidosCancelados()
    {
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("
            SELECT *
            FROM pedidos
            WHERE estado = 'Cancelado'
        ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerTodosPorCodigo($codigoPedido)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, idMesa, idProducto, nombreCliente, estado, codigoPedido FROM pedidos WHERE codigoPedido = :codigoPedido");
        $query->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $query->execute();
    
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function obtenerMesaMasUsada()
{
    $objDataAccess = DataAccess::getInstance();
    $consulta = $objDataAccess->prepareQuery("
        SELECT idMesa, COUNT(idMesa) as cantidadPedidos
        FROM pedidos
        GROUP BY idMesa
        ORDER BY cantidadPedidos DESC
        LIMIT 1
    ");
    $consulta->execute();

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

public static function obtenerMesaMenosUsada()
{
    $objDataAccess = DataAccess::getInstance();
    $consulta = $objDataAccess->prepareQuery("
        SELECT idMesa, COUNT(idMesa) as cantidadPedidos
        FROM pedidos
        GROUP BY idMesa
        ORDER BY cantidadPedidos ASC
        LIMIT 1
    ");
    $consulta->execute();

    return $consulta->fetch(PDO::FETCH_ASSOC);
}



}
?>