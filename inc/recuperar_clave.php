<?php
include 'funciones.inc.php';

$respuesta= new stdClass();

$email=$_POST['email'];
if (existeCorreo($email)) {
    $asunto='Recuperar clave Prontophot';
    $code=generarCodigo(64);
    $url=URL_SITIO.'recuperar/'.$code;
    guardarCode($email, $code);
    $cuerpo='<div style="display:block;position:relative; min-width:400px;">
		<div style="display:block; width: 100%; height:auto">
			<img src="'.URL_SITIO.'img/header.png" style="width:100%;">
		</div>
		<div class="cuerpo" style="display:block; padding: 10px 50px;background-color:#fff; width:calc(100% - 100px);position: relative; min-height: 200px;">
			<h2 style="font: normal normal bold 18px/21px Arial;letter-spacing: 0px;color: #DA0000;">&iexcl;Hola '.$email.'!</h2>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;color: #333333;">Tu pedido de recuperar la contraseña fue procesado.</p>
            <p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;color: #333333;">Utilice el siguiente <a href="'.$url.'" target="_blank">Link</a>.</p>
            <p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;color: #333333;">Sino funciona el link copie y pegue la siguiente URL '.$url.'</p>
			<br>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;">Cualquier solicitud, o consulta, no dejes de comunicarte con nosotros a trav&eacute;s de nuestro <a href="https://prontophot.com" style="font: normal normal bold 18px/21px Arial;color: #DA0000;">sitio web</a> o por <a href="#" style="font: normal normal bold 18px/21px Arial;color: #DA0000;">Whastapp</a></p>
			<br>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;color: #DA0000;">&iexcl;Muchas gracias!<br>Equipo Prontophot.</p>
		</div>
		<div class="pie" style="padding: 10px 50px;display:flex; width:calc(100% - 100px); background-color: #000; position:relative; height:20px;">
			<a href="#" target="_blank" style="display:inline-block; width:25px;"><img alt="logo fb" style="height:20px;" src="https://pronto.silex14web.com/img/face.png"></a>
			<a href="#" target="_blank" style="display:inline-block; width:25px;"><img alt="logo instagram" style="height:20px;" src="https://pronto.silex14web.com/img/insta.png"></a>
		</div>
	</div>';
    
    $res=enviar_mail($email, $email, $asunto, $cuerpo, $cuerpo);
    if($res){
        $respuesta->success=true;
        $respuesta->msg='Mensaje Enviado';
    }else{
        $respuesta->success=false;
        $respuesta->msg='Error al enviar, intente de nuevo'.$res;
    }
}else{
    $respuesta->false;
    $respuesta->msg='No existe el correo';
}


echo json_encode($respuesta);