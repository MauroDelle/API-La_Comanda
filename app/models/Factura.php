<?php

class Factura
{
    public $id;
    public $codigo_mesa;
    public $codigo_pedido;
    public $fecha;
    public $total;

    public function __construct() {}

        #region Getter Setter

        public function getId() {
            return $this->id;
        }
    
        public function setId($id) {
            $this->id = $id;
        }
        public function getCodigoMesa() {
            return $this->codigo_mesa;
        }
        public function setCodigoMesa($codigo_mesa) {
            $this->codigo_mesa = $codigo_mesa;
        }
        public function getCodigoPedido() {
            return $this->codigo_pedido;
        }
        public function setCodigoPedido($codigo_pedido) {
            $this->codigo_pedido = $codigo_pedido;
        }
        public function getFecha() {
            return $this->fecha;
        }
        public function setFecha($fecha) {
            $this->fecha = $fecha;
        }
        public function getTotal() {
            return $this->total;
        }
        public function setTotal($total) {
            $this->total = $total;
        }

        #endregion


    public static function Create($obj){

        $objAccesoDatos = DataAccess::getInstance();
        $consulta = $objAccesoDatos->prepareQuery("INSERT INTO Facturas (CODIGO_MESA, CODIGO_PEDIDO, FECHA, TOTAL) VALUES (:codigo_mesa, :codigo_pedido, :fecha, :total)");
        
        $consulta->bindValue(':codigo_mesa', $obj->getCodigoMesa(), PDO::PARAM_STR);
        $consulta->bindValue(':codigo_pedido', $obj->getCodigoPedido(), PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $obj->getFecha());
        $consulta->bindValue(':total', $obj->getTotal());
        $consulta->execute();
    }

    public static function GetAll(){
        $objAccesoDatos = DataAccess::getInstance();
        $consulta = $objAccesoDatos->prepareQuery("SELECT id, codigo_mesa, codigo_pedido, fecha, total FROM Facturas");

        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Factura');
    }

    public static function GetByCodigo($codigo, $tipoCodigo){
        $objAccesoDatos = DataAccess::getInstance();
        $respuesta= null;
        if($tipoCodigo == 'M'){
            $consulta = $objAccesoDatos->prepareQuery("SELECT id, codigo_mesa, codigo_pedido, fecha, total FROM Facturas WHERE codigo_mesa = :codigo_mesa");
            $consulta->bindValue(':codigo_mesa', $codigo, PDO::PARAM_STR);
            $consulta->execute();
            $respuesta = $consulta->fetchAll(PDO::FETCH_CLASS, 'Factura');
            
        }else if($tipoCodigo == 'P'){
            $consulta = $objAccesoDatos->prepareQuery("SELECT id, codigo_mesa, codigo_pedido, fecha, total FROM Facturas WHERE codigo_pedido = :codigo_pedido");
            $consulta->bindValue(':codigo_pedido', $codigo, PDO::PARAM_STR);
            $consulta->execute();
            $respuesta = $consulta->fetchObject('Factura');
        }
        return $respuesta;
    }
}

?>