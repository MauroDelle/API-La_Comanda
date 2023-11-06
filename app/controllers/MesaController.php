<?php
require_once './models/Mesa.php';
require_once './models/EstadoMesa.php';
require_once './Interfaces/IInterfazAPI.php';

class MesaController extends Mesa implements IInterfazAPI
{

    public static function CargarUno($request, $response, $args){

        $parametros = $request->getParsedBody();
        $mesa = new Mesa();
    
        Mesa::crear($mesa);
    
        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }
    public static function TraerUno($request, $response, $args){}
    public static function TraerTodos($request, $response, $args){}
    public static function BorrarUno($request, $response, $args){}
    public static function ModificarUno($request, $response, $args){}

}


?>