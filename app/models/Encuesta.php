<?php
require_once './interfaces/IPersistencia.php';
require_once './models/Rol.php';

class Encuesta implements Ipersistencia
{
    public $id;
    public $codigoMesa;
    public $puntuacionMesa;
    public $puntuacionRestaurante;
    public $puntuacionMozo;
    public $puntuacionCocinero;
    public $experiencia;


    public function __get($propiedad)
    {
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        } else {
            return null;
        }
    }

    public function __set($propiedad, $valor)
    {
        if (property_exists($this, $propiedad)) {
            $this->$propiedad = $valor;
        } else {
            echo "No existe " . $propiedad;
        }
    }


    public static function crear($encuesta)
    {
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("INSERT INTO encuestas (codigoMesa, puntuacionMesa, puntuacionRestaurante, puntuacionMozo, puntuacionCocinero, experiencia) VALUES (:codigoMesa,:puntuacionMesa, :puntuacionRestaurante, :puntuacionMozo, :puntuacionCocinero, :experiencia)");
        $consulta->bindValue(':codigoMesa', $encuesta->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':puntuacionMesa', $encuesta->puntuacionMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionRestaurante', $encuesta->puntuacionRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionMozo', $encuesta->puntuacionMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionCocinero', $encuesta->puntuacionCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':experiencia', $encuesta->experiencia, PDO::PARAM_STR);
        $consulta->execute();

        return $objDataAccess->getLastInsertedId();
    }

    public static function obtenerTodos()
    {
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("SELECT id, codigoMesa, puntuacionMesa, puntuacionRestaurante, puntuacionMozo, puntuacionCocinero, experiencia FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
    public static function obtenerMejoresComentarios()
    {
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("SELECT id, codigoMesa, puntuacionMesa, puntuacionRestaurante, puntuacionMozo, puntuacionCocinero, experiencia, (puntuacionMesa + puntuacionRestaurante + puntuacionMozo + puntuacionCocinero)/4 AS promedio
        FROM encuestas ORDER BY promedio DESC");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerUno($valor)
    {
        $objDataAccess = DataAccess::getInstance();
        $consulta = $objDataAccess->prepareQuery("SELECT id, codigoMesa, puntuacionMesa, puntuacionRestaurante, puntuacionMozo, puntuacionCocinero, experiencia FROM encuentas WHERE id = :valor");
        //$consulta->bindValue(':propiedad', $propiedad, PDO::PARAM_STR);
        $consulta->bindValue(':valor', $valor, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function modificar($producto)
    {
        // $objAccesoDato = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDato->prepararConsulta("UPDATE encuentos SET descripcion = :descripcion, tipo = :tipo, precio = :precio WHERE id = :id");
        // $consulta->bindValue(':descripcion', $producto->descripcion, PDO::PARAM_STR);
        // $consulta->bindValue(':tipo', $producto->tipo, PDO::PARAM_STR);
        // $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_STR);
        // $consulta->bindValue(':id', $producto->id, PDO::PARAM_INT);
        // $consulta->execute();
    }

    public static function borrar($id)
    {
        // $objAccesoDato = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET fechaBaja = :fechaBaja WHERE id = :id AND fechaBaja IS NULL");
        // $fecha = new DateTime(date("d-m-Y"));
        // $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        // $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        // $consulta->execute();
    }

    public static function ValidarPuntuacion($puntuacion)
    {

        if ($puntuacion >= 1 && $puntuacion <= 10) {
            return true;
        }

        return false;
    }

}

?>