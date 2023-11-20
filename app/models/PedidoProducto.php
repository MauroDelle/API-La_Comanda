<?php

class PedidoProducto implements Ipersistencia
{
    public $id;
    public $codPedido;
    public $idProducto;
    public $tiempoEstimado;
    public $estado;
    public $idEmpleado;

    public function __construct(){}

    #region getters-setters

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getCodPedido() {
            return $this->codPedido;
        }

        public function setCodPedido($codPedido) {
            $this->codPedido = $codPedido;
        }
    
        public function getIdProducto() {
            return $this->idProducto;
        }
    
        public function setIdProducto($idProducto) {
            $this->idProducto = $idProducto;
        }

        public function getTiempoEstimado() {
            return $this->tiempoEstimado;
        }
    
        public function setTiempoEstimado($tiempoEstimado) {
            $this->tiempoEstimado = $tiempoEstimado;
        }
        public function getEstado() {
            return $this->estado;
        }
    
        public function setEstado($estado) {
            $this->estado = $estado;
        }
    
        public function getIdEmpleado() {
            return $this->idEmpleado;
        }
    
        public function setIdEmpleado($idEmpleado) {
            $this->idEmpleado = $idEmpleado;
        }

    #endregion

    
    public static function crear($objeto){

        $objDataAccess = DataAccess::getInstance();
        if (is_array($objeto)) {
            foreach ($objeto as $producto) {
                // Crear un nuevo registro en la tabla PedidoProducto
                $query = $objDataAccess->prepareQuery('INSERT INTO PedidoProducto (codigoPedido, idProducto, tiempoEstimado, estado, idEmpleado) VALUES (:codigoPedido, :idProducto, :tiempoEstimado, :estado, :idEmpleado)');
                $query->bindValue(':codigoPedido', $producto->codigoPedido, PDO::PARAM_STR);
                $query->bindValue(':idProducto', $producto->idProducto, PDO::PARAM_INT);
                $query->bindValue(':tiempoEstimado', $producto->tiempoEstimado, PDO::PARAM_STR);
                $query->bindValue(':estado', $producto->estado, PDO::PARAM_STR);
                $query->bindValue(':idEmpleado', $producto->idEmpleado, PDO::PARAM_INT);
                $query->execute();
            }
        } else {
            $query = $objDataAccess->prepareQuery('INSERT INTO PedidoProducto (codigoPedido, idProducto, tiempoEstimado, estado, idEmpleado) VALUES (:codigoPedido, :idProducto, :tiempoEstimado, :estado, :idEmpleado)');
            $query->bindValue(':codigoPedido', $objeto->codigoPedido, PDO::PARAM_STR);
            $query->bindValue(':idProducto', $objeto->idProducto, PDO::PARAM_INT);
            $query->bindValue(':tiempoEstimado', $objeto->tiempoEstimado, PDO::PARAM_STR);
            $query->bindValue(':estado', $objeto->estado, PDO::PARAM_STR);
            $query->bindValue(':idEmpleado', $objeto->idEmpleado, PDO::PARAM_INT);
            $query->execute();
        }

    }
    public static function obtenerTodos(){}
    public static function obtenerUno($valor){}
    public static function modificar($objeto){}
    public static function borrar($objeto){}

}
?>