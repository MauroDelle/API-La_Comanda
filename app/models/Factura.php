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


        public static function crear($factura)
        {
            $objDataAccess = DataAccess::getInstance();
            $consulta = $objDataAccess->prepareQuery("INSERT INTO Facturas (CODIGO_MESA, CODIGO_PEDIDO, FECHA, TOTAL) VALUES (:codigo_mesa, :codigo_pedido, :fecha, :total)");
            
            $consulta->bindValue(':codigo_mesa', $factura->codigo_mesa, PDO::PARAM_INT);
            $consulta->bindValue(':codigo_pedido', $factura->codigo_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':fecha', $factura->fecha);
            $consulta->bindValue(':total', $factura->total);
            $consulta->execute();
        
            return $objDataAccess->getLastInsertedId();
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