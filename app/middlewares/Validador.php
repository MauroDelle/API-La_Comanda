<?php
require_once "./models/Empleado.php";
require_once './middlewares/AutentificadorJWT.php';


class Validador
{
    public static function ValidarNuevoEmpleado($request,$handler)
    {
        $params = $request->getParsedBody();

        $empleado = $params['nombre'];
        $rol = $params['rol'];
        if(Empleado::ValidadorRol($rol))
        {
            return $handler->handle($request);
        }

        throw new Exception("Error en la creacion del empleado");
    }

    public static function VerificarArchivo($request, $handler)
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (isset($uploadedFiles['csv'])) {
          
            if (preg_match('/\.csv$/i', $uploadedFiles['csv']->getClientFilename()) == 0){
                throw new Exception("Debe ser un archivo CSV");
            }
            
            return $handler->handle($request);
        }

        throw new Exception("Error no se recibio el archivo");
    }



}

?>