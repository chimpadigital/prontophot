<?php
include_once 'funciones.inc.php';
include_once 'config.inc.php';
$respuesta= new stdClass();
$nombre=$_POST['nombre'];
$email=$_POST['email'];
$telefono=$_POST['telefono'];
$consulta=$_POST['consulta'];

if(isset($_POST['g-recaptcha-response'])){
    $captcha=$_POST['g-recaptcha-response'];
}
if(!$captcha){
    $respuesta->success=false;
    $respuesta->msg='Error, tilde el captcha';
    
}else{
    $secretKey = "6LcHpIQcAAAAAF3IsUy4P9l2NbFiOZY-8wq0nOJu";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    if($responseKeys["success"]) {
        $asunto='Nuevo Contacto desde la web';
        $cuerpo='<p>Nombre :'.$nombre.'</p><p>Email :'.$email.'</p><p>Telefono :'.$telefono.'</p><p>Consulta :'.$consulta.'</p>';
        
        $res=enviar_mail(MAIL_TO, 'consulta', $asunto, $cuerpo, $cuerpo);
        if($res){
            $respuesta->success=true;
            $respuesta->msg='Mensaje Enviado';
        }else{
            $respuesta->success=false;
            $respuesta->msg='Error al enviar, intente de nuevo';
        }
    } else {
        $respuesta->success=false;
        $respuesta->msg='Error, eres un robot spammer';
    }
}
echo json_encode($respuesta);