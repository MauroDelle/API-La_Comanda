<?php
require_once './models/EstadoMesa.php';


class Mesa implements Ipersistencia
{
    public $id;
    public $CODIGO_DE_MESA;
    public $estado;

    #region Constructor por defecto
    public function __construct(){}
    #endregion

    #region Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setCodigoDeMesa($codigoDeMesa) {
        $this->CODIGO_DE_MESA = $codigoDeMesa;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    #endregion
    
    #region Getters
    public function getId() {
        return $this->id;
    }
    
    public function getCodigoDeMesa() {
        return $this->CODIGO_DE_MESA;
    }

    public function getEstado() {
        return $this->estado;
    }
    #endregion

    public static function crear($mesa){
        $codigo = Mesa::CodigoAleatorio(5);
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("INSERT INTO mesas (CODIGO_DE_MESA, estado) VALUES (:codigoDeMesa, :estado)");
        $consulta->bindValue(':codigoDeMesa', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::CERRADA, PDO::PARAM_STR);
        $consulta->execute();

        return $objDataAccess->getLastInsertedId();
    }
    public static function obtenerTodos(){

        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("SELECT id, CODIGO_DE_MESA, estado FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    public static function obtenerUno($id){

        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("SELECT id, CODIGO_DE_MESA, estado FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public static function modificar($mesa){

        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("UPDATE mesas SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $mesa->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $mesa->estado, PDO::PARAM_STR);
        $consulta->execute();
    }
    public static function borrar($id){
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("UPDATE mesas SET estado = :estado WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':estado', Estado::BAJA, PDO::PARAM_STR);
        $consulta->execute();
    }
    static function CodigoAleatorio($longitud)
    {
        $caracteresUtilizables = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $codigo = "";

        for($i = 0; $i < $longitud; $i++)
        {
            $codigo .= $caracteresUtilizables[rand(0,strlen($caracteresUtilizables)-1)];
        }
        return $codigo;
    }

    public static function obtenerMesaPorCodigoPedido($codigoPedido)
    {
        $objAccesoDatos = DataAccess::getInstance();
        $consulta = $objAccesoDatos->prepareQuery(
            "SELECT m.id, m.CODIGO_DE_MESA, m.estado
            FROM mesas as m
            INNER JOIN pedidos as p ON p.idMesa = m.id
            WHERE p.codigoPedido = :codigoPedido"
        );
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }


    public static function obtenerCuenta($codigoPedido)
    {
        $objAccesoDatos = DataAccess::getInstance();
        $consulta = $objAccesoDatos->prepareQuery(
            "SELECT p.idMesa , SUM(pr.precio)
            FROM pedidos as p
            INNER JOIN productos as pr ON p.idProducto = pr.id
            WHERE p.codigoPedido = :codigoPedido"
        );
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerUsosMesas()
    {
        $objAccesoDatos = DataAccess::getInstance();
        $consulta = $objAccesoDatos->prepareQuery(
            "SELECT m.id, COUNT(*) as cantidad
            FROM pedidos as p
            INNER JOIN mesas as m ON p.idMesa = m.id"
        );


        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }




}
?>