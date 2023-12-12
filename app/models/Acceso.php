<?php
require_once './Interfaces/Ipersistencia.php';
require_once './db/DataAccess.php';

class Acceso implements Ipersistencia
{
    public $idTransaccion;
    public $idUsuario;
    public $fechaHora;
    public $tipoTransaccion;


    public function __construct(){}


    #region Getters setters
    public function getIdTransaccion() {
        return $this->idTransaccion;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getFechaHora() {
        return $this->fechaHora;
    }

    public function getTipoTransaccion() {
        return $this->tipoTransaccion;
    }

    // Setters
    public function setIdTransaccion($idTransaccion) {
        $this->idTransaccion = $idTransaccion;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setFechaHora($fechaHora) {
        $this->fechaHora = $fechaHora;
    }

    public function setTipoTransaccion($tipoTransaccion) {
        $this->tipoTransaccion = $tipoTransaccion;
    }

    #endregion



    public static function crear($transaccion)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("INSERT INTO Transacciones (idUsuario, fechaHora, tipoTransaccion) VALUES (:idUsuario, :fechaHora, :tipoTransaccion)");
    
        $query->bindValue(":idUsuario", $transaccion->getIdUsuario(), PDO::PARAM_INT);
        $query->bindValue(":fechaHora", $transaccion->getFechaHora(), PDO::PARAM_STR);
        $query->bindValue(":tipoTransaccion", $transaccion->getTipoTransaccion(), PDO::PARAM_STR);
    
        $query->execute();
    
        return $objDataAccess->getLastInsertedId();
    }

    public static function obtenerTodos()
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT idTransaccion, idUsuario, fechaHora, tipoTransaccion FROM Transacciones");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "Acceso");
    }

    public static function obtenerUno($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT idTransaccion, idUsuario, fechaHora, tipoTransaccion FROM Transacciones WHERE idTransaccion = :id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
    
        $query->execute();
    
        return $query->fetchObject('Transaccion');
    }
    public static function modificar($objeto){}
    
    public static function borrar($objeto){}


    public function ExportarOperacionesPDF($request, $response, $args)
    {
        
        $orden = $args['orden'];

        try
        {
            $archivo = CSV::ExportarPDF("operaciones.pdf", $orden);
            if(file_exists($archivo) && filesize($archivo) > 0)
            {
                $payload = json_encode(array("Archivo creado:" => $archivo));
            }
            else
            {
                $payload = json_encode(array("Error" => "Datos ingresados invalidos."));
            }
            $response->getBody()->write($payload);
        }
        catch(Exception $e)
        {
            echo $e;
        }
        finally
        {
            return $response->withHeader('Content-Type', 'text/csv');
        }    
    }



}

?>