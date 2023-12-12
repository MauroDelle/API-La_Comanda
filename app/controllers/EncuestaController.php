<?php
require_once './models/Encuesta.php';
require_once './interfaces/IInterfazAPI.php';

class EncuestaController extends Encuesta implements IInterfazAPI
{

    public static function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
  
      $codigoMesa = $parametros['codigoMesa'];
      $puntuacionMesa = $parametros['puntuacionMesa'];
      $puntuacionRestaurante = $parametros['puntuacionRestaurante'];
      $puntuacionMozo = $parametros['puntuacionMozo'];
      $puntuacionCocinero = $parametros['puntuacionCocinero'];
      $experiencia = $parametros['experiencia'];

      $header = $request->getHeaderLine(("Authorization"));
      $token = trim(explode("Bearer", $header)[1]);
      $data = AutentificadorJWT::ObtenerData($token);
      $dataClave = Empleado::obtenerUnoPorClave($data->nombre,$data->clave);
      
      $acceso = new Acceso();
      $acceso->idUsuario = $dataClave->id; // Aquí corregí de $data->ID a $data->id
      $acceso->fechaHora = date('Y-m-d H:i:s');
      $acceso->tipoTransaccion = "NUEVA-ENCUESTA";
      Acceso::crear($acceso);


      $enc = new Encuesta();
      $enc->codigoMesa = $codigoMesa;
      $enc->puntuacionMesa = $puntuacionMesa;
      $enc->puntuacionRestaurante = $puntuacionRestaurante;
      $enc->puntuacionMozo = $puntuacionMozo;
      $enc->puntuacionCocinero = $puntuacionCocinero;
      $enc->experiencia = $experiencia;
  
      $mesa = Mesa::obtenerUnoPorCodigo($codigoMesa);
      if ($mesa && ($mesa->estado == Estado::PAGANDO || $mesa->estado == Estado::PAGADO)) {
  
        Encuesta::crear($enc);
  
        $payload = json_encode(array("mensaje" => "Encuesta enviada con exito"));
      } else {
        $payload = json_encode(array("mensaje" => "Para enviar la encuesta el cliente tiene que terminar de comer"));
  
      }
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }


    public static function TraerUno($request, $response, $args){}
    public static function TraerTodos($request, $response, $args){}
    public static function BorrarUno($request, $response, $args){}
    public static function ModificarUno($request, $response, $args){}



    public static function TraerMejores($request, $response, $args)
    {
      $lista = Encuesta::obtenerMejoresComentarios();
      $payload = json_encode(array("Mejores comentarios" => $lista));
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    

}



?>