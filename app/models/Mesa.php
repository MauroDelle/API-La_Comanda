<?php
require_once './models/EstadoMesa.php';


class Mesa implements Ipersistencia
{
    public $id;
    public $codigoDeMesa;
    public $estado;

    #region Constructor por defecto
    public function __construct(){}
    #endregion

    #region Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setCodigoDeMesa($codigoDeMesa) {
        $this->codigoDeMesa = $codigoDeMesa;
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
        return $this->codigoDeMesa;
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

}
?>