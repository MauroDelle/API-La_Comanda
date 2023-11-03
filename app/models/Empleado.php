<?php
require_once './Interfaces/IPersistencia.php';
require_once './Interfaces/IInterfazAPI.php';
require_once 'Rol.php';

class Empleado implements Ipersistencia
{
    public $id;
    public $rol;
    public $nombre;
    public $baja;
    public $fecha_alta;
    public $fecha_baja;

    #region Constructor por defecto
    public function __construct(){}
    #endregion

    #region Setters.
    public function setId($id){ $this->id = $id;}
    public function setRol($rol){$this->rol = $rol;}
    public function setNombre($nombre){$this->nombre = $nombre;}
    public function setBaja($baja){$this->baja = $baja;}
    public function setFecha_alta($fecha_alta){$this->fecha_alta = $fecha_alta;}
    public function setFecha_baja($fecha_baja){$this->fecha_baja = $fecha_baja;}
    #endregion

    #region Getters
    public function getId(){ return $this->id;}
    public function getRol(){return $this->rol;}
    public function getNombre(){return $this->nombre;}
    public function getBaja(){return $this->baja;} 
    public function getFecha_alta(){return $this->fecha_alta;}
    public function getFecha_baja(){return $this->fecha_baja;}


    #endregion

    public static function crear($user)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("INSERT INTO Empleados (rol,nombre,baja,fecha_alta) VALUES
        (:rol,:nombre,:baja,:fecha_alta)");
        $query->bindValue(":rol", $user->rol, PDO::PARAM_STR);
        $query->bindValue(":nombre", $user->getNombre(),PDO::PARAM_STR);
        $query->bindValue(":baja", $user->getBaja(), PDO::PARAM_BOOL);
        $query->bindValue(":fecha_alta", $user->getFecha_alta());
        $query->execute();

        return $objDataAccess->getLastInsertedId();
    }

    public static function obtenerTodos()
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, rol, nombre, baja,fecha_alta,fecha_baja");
    }
    public static function obtenerUno($empleado)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, rol, nombre,baja,fecha_alta,fecha_baja FROM empleados WHERE empleado = :empleado");
        $query->bindValue(':empleado',$empleado, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchObject('Empleado');
    }

    public static function modificar($usuario){}
    public static function borrar($id){}


}
   
?>