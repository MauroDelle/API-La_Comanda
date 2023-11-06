<?php

require_once './Interfaces/IPersistencia.php';
require_once './models/Rol.php';

class Producto implements Ipersistencia
{
    public $id;
    public $sector;
    public $nombre;
    public $precio;
    public $tiempo_estimado;

    #region Constructor Por Defecto
    public function __construct(){}
    #endregion

    #region Getters
    public function getId() {
        return $this->id;
    }

    public function getSector() {
        return $this->sector;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getTiempoEstimado() {
        return $this->tiempo_estimado;
    }

    #endregion

    #region Setters

    public function setId($id) {
        $this->id = $id;
    }

    public function setSector($sector) {
        $this->sector = $sector;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setTiempoEstimado($tiempo_estimado) {
        $this->tiempo_estimado = $tiempo_estimado;
    }
    #endregion

    public static function crear($producto){

        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("INSERT INTO productos (sector, nombre, precio,tiempo_estimado) VALUES (:sector, :nombre, :precio, :tiempo_estimado)");
        $consulta->bindValue(':sector', $producto->sector, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $producto->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $producto->precio);
        $consulta->bindValue(':tiempo_estimado', $producto->tiempo_estimado);
        $consulta->execute();

        return $objDataAccess->getLastInsertedId();
    }
    public static function obtenerTodos()
    {

        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, sector, nombre, precio,tiempo_estimado FROM productos");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "Producto");
    }
    public static function obtenerUno($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, sector, nombre, precio, tiempo_estimado FROM productos WHERE id = :id");
        $query->bindValue(':id',$id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject('Producto');
    }
    public static function modificar($producto)
    {
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("UPDATE productos SET sector = :sector, nombre = :nombre, precio = :precio WHERE id = :id");
        $consulta->bindValue(':sector', $producto->sector, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $producto->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_STR);
        $consulta->bindValue(':id', $producto->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    public static function borrar($id){
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery('UPDATE productos SET fecha_baja = :fecha_baja WHERE id = :id AND fecha_baja IS NULL');
        $fecha = new DateTime(date("d-m-y"));
        $query ->bindValue(":id", $id, PDO::PARAM_INT);
        $query->bindValue(":fecha_baja",date_format($fecha,"Y-m-d H:i:s"));
        $query->execute();
    }

    public static function obtenerUnoPorId($id)
    {
        $objDataAccess = DataAccess::getInstance(); 
        $query = $objDataAccess->prepareQuery('SELECT id, sector, nombre, precio, tiempo_estimado FROM productos WHERE id = :id');
        $query->bindValue(':id', $id,PDO::PARAM_STR);
        var_dump($id);
        $query->execute();

        return $query->fetchObject('Producto');
    }




}


?>