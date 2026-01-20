<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Intervention\Image\ImageManagerStatic as Image;
// Load Composer's autoloader
require 'vendor/autoload.php';
require_once 'config.inc.php';
include_once __DIR__ . '/../conexion/conectar.inc.php';

if (!function_exists('metodoPago')) {
    function metodoPago($metodo)
    {
        switch ($metodo) {
            case '1':
                return 'Transferencia Bancaria';
                break;
            case '2':
                return 'MercadoPago';
                break;
            case '3':
                return 'Paga al retirar';
                break;
        }
    }
}
if (!function_exists('metodoRetiro')) {
    function metodoRetiro($metodo)
    {
        switch ($metodo) {
            case 'suc1':
                return 'Retiro por sucursal 1, Calle 12 N°1108 e/55 y 56';
                break;
            case 'suc2':
                return 'Retiro por sucursal 2, Calle 50 entre 8 y 9, Pasaje 8 bis';
                break;
            case 'suc3':
                return 'Retiro por sucursal 3, Cantilo N° 173 e/ 13ª y 13b, City Bell';
                break;
            case 'recibir':
                return 'Envio a domicilio, paga al recibir';
                break;
            case 'urbano':
                return 'Envio a domicilio dentro del casco urbano ';
                break;
        }
    }
}
if (!function_exists('existeCorreo')) {
    function existeCorreo($email)
    {
        global $conectar;
        $respuesta = new stdClass();
        $sel = $conectar->query("SELECT * FROM clientes WHERE email='$email'");
        if ($sel->num_rows > 0) {
            $respuesta->success = true;
        } else {
            $respuesta->success = false;
        }
        return $respuesta;
    }
}

if (!function_exists('guardarCode')) {
    function guardarCode($email, $code)
    {
        global $conectar;
        $sel = $conectar->query("UPDATE clientes SET code='$code' WHERE email='$email'");
        if ($sel) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('guardarPago')) {
    function guardarPago($idpago, $estado, $pedido)
    {
        global $conectar;
        $sel = $conectar->query("SELECT * FROM pagos WHERE id_payment='$idpago'");
        if ($sel->num_rows > 0) {
            if ($estado == 'approved') {
                $conectar->query("UPDATE pedidos SET estado='1' WHERE id='$pedido'");
                return true;
            }
        } else {
            $sql = "INSERT INTO `pagos`(`id_pedido`, `id_payment`, `status`) VALUES ('$pedido','$idpago','$estado')";
            $res = $conectar->query($sql);
            if ($res) {
                if ($estado == 'approved') {
                    $conectar->query("UPDATE pedidos SET estado='1' WHERE id='$pedido'");
                    stockProducto($pedido);
                    return true;
                }
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('stockProducto')) {
    function stockProducto($pedido)
    {
        global $conectar;
        $prod = $conectar->query("SELECT * FROM pedidos_detalle WHERE id_pedido='$pedido'");
        while ($row = $prod->fetch_assoc()) {
            $idp = $row['id_producto'];
            $cant = $row['cantidad'];
            $conectar->query("UPDATE `productos` SET `stock`=stock-'$cant' WHERE id=$idp");
        }
    }
}

function crearPedido($param)
{
    global $conectar;
    $fecha = date('Y-m-d');
    $respuesta = new stdClass();
    $idcliente = $param['idcliente'];
    $nombre = $param['nombre'];
    $direccion = $param['direccion'];
    $cp = $param['cp'];
    $ciudad = $param['ciudad'];
    $provincia = $param['provincia'];
    $dni = $param['dni'];
    $telefono = $param['telefono'];
    $valor = $param['valor'];
    $metodo = $param['metodo'];
    $entrega = $param['entrega'];
    $envio = $param['envio'];
    $desc = $param['desc'];
    $sql = "INSERT INTO `pedidos`(`id_cliente`, `nombre`, `direccion`, `cp`, `ciudad`, `provincia`, `dni`, `telefono`, `total`, `metodo`, `entrega`, `envio`,`estado`,`estado_pedido`, `fecha`, `descripcion` ) VALUES ('$idcliente','$nombre','$direccion','$cp','$ciudad','$provincia','$dni','$telefono','$valor','$metodo','$entrega','$envio','0','1','$fecha','$desc')";
    $res = $conectar->query($sql);
    if ($res) {
        $respuesta->success = true;
        $idpedido = $conectar->insert_id;
        $respuesta->pedido = $idpedido;
    } else {
        $respuesta->success = false;
        $respuesta->error = $conectar->error;
    }
    return $respuesta;
}

if (!function_exists('pedidoImagenes')) {
    function pedidoImagenes($pedido, $archivos)
    {
        global $conectar;
        foreach ($archivos as $file) {
            $archivo = $file['archivo'];
            $ext = explode('.', $archivo);
            $name = $ext[0];
            $image = __DIR__ . '/../' . $archivo;
            $thumb = $name . '.webp';
            $nuevo = __DIR__ . '/../' . $name . '.webp';
            $img = Image::make($image);
            $img->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($nuevo);
            $formato = $file['tamano'];
            $acabado = $file['acabado'];
            $cant = $file['cantidad'];
            $imp = $file['idimpresion'];
            $query = "INSERT INTO `pedidos_imagenes`(`imagen`,`thumb`, `id_impresion`, `id_pedido`, `cantidad`, `formato`, `acabado`) VALUES ('$archivo','$thumb','$imp','$pedido','$cant','$formato','$acabado')";
            $res2 = $conectar->query($query);
        }
    }
}

function extension($filename)
{
    return substr(strrchr($filename, '.'), 1);
}
function string_sanitize($string, $force_lowercase = true, $anal = false)
{
    $strip = array(
        "~",
        "`",
        "!",
        "@",
        "#",
        "$",
        "%",
        "^",
        "&",
        "*",
        "(",
        ")",
        "_",
        "=",
        "+",
        "[",
        "{",
        "]",
        "}",
        "\\",
        "|",
        ";",
        ":",
        "\"",
        "'",
        "&#8216;",
        "&#8217;",
        "&#8220;",
        "&#8221;",
        "&#8211;",
        "&#8212;",
        "â€”",
        "â€“",
        ",",
        "<",
        ".",
        ">",
        "/",
        "?"
    );
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;
    return ($force_lowercase) ? (function_exists('mb_strtolower')) ?
        mb_strtolower($clean, 'UTF-8') :
        strtolower($clean) :
        $clean;
}

function getUrl($cadena)
{

    $eliminar = array("+", "!", "¡", "?", "¿", "‘", "\"", "$", "(", ")", ".", ":", ";", "_", "/", "\\", "\$", "%", "@", "#", ",", "«", "»");
    $buscados = array(" ", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "Ñ", "ü", "à", "è", "ì", "ò", "ù", "À", "È", "Ì", "Ò", "Ù");
    $sustitut = array("-", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n", "n", "u", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
    $final = strtolower(str_replace($buscados, $sustitut, str_replace($eliminar, "", $cadena)));
    $final = str_replace("–", "-", $final);
    $final = str_replace("–", "-", $final);
    return (strlen($final) > 50) ? substr($final, 0, strrpos(substr($final, 0, 50), "-")) : $final;
}
function setUrl($url)
{
    $nuevo = str_replace('-', ' ', $url);
    return $nuevo;
}

/* function montoPlan($plan){
    switch ($plan){
        case '1':
            return PLAN_1;
        break;
        case '2':
            return PLAN_2;
        break;
        case '3':
            return PLAN_3;
        break;
        case '4':
            return PLAN_4;
        break;    
    }
} */

function dispo($valor)
{
    if ($valor == '0') {
        return gettext('NO');
    } else {
        return gettext('SI');
    }
}

/*
obsoleto php 7.3
function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
    $max = strlen($pattern) - 1;
    for ($i = 0; $i < $longitud; $i++) {
        $key .= $pattern[mt_rand(0, $max)];
    }
    return $key;
}*/

function generarCodigo($longitud)
{
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
    $max = strlen($pattern) - 1;

    for ($i = 0; $i < $longitud; $i++) {
        $key .= $pattern[mt_rand(0, $max)];
    }

    return $key;
}

function enviar_mail($correo, $nombre, $asunto, $cuerpo, $cuerpo_alt)
{

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'prontophot.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->SMTPAutoTLS = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username   = 'web@prontophot.com';                     // SMTP username
        $mail->Password   = 'XSTJ.FwQhn}I';                               // SMTP password
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->setFrom('web@prontophot.com', 'WEB');
        $mail->addAddress($correo, $nombre);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;
        $mail->AltBody = $cuerpo_alt;

        $mail->send();
        $res = true;
    } catch (Exception $e) {
        $res = $e->getMessage();
    }
    return $res;
}
function notificarRetiro($pedido)
{
    global $conectar;
    $cl = $conectar->query("SELECT c.email,c.nombre,c.apellido FROM pedidos p LEFT JOIN clientes c ON p.id_cliente=c.id WHERE id='$pedido'");
    $rowc = $cl->fetch_assoc();
    $correo = $rowc['email'];
    $nombre = $rowc['nombre'] . ' ' . $rowc['apellido'];

    $cuerpo = '<div style="display:block;position:relative; min-width:400px;max-width:700px;">
		<div style="display:block; width: 100%; height:auto">
			<img src="' . URL_SITIO . 'img/header.png" style="width:100%;">
		</div>
		<div class="cuerpo" style="display:block; padding: 10px 50px;background-color:#fff; width:calc(100% - 100px);position: relative; min-height: 200px;">
			<h2 style="font: normal normal bold 18px/21px Arial;letter-spacing: 0px;color: #DA0000;">&iexcl;Hola ' . $nombre . '!</h2>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;color: #333333;">Tu pedido #' . $pedido . ' te est&aacute; esperando, pod&eacute;s completarlo ahora.</p>
			<br>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;">Cualquier solicitud, o consulta, no dejes de comunicarte con nosotros a trav&eacute;s de nuestro <a href="https://prontophot.com" style="font: normal normal bold 18px/21px Arial;color: #DA0000;">sitio web</a> o por <a href="#" style="font: normal normal bold 18px/21px Arial;color: #DA0000;">Whastapp</a></p>
			<br>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;color: #DA0000;">&iexcl;Muchas gracias!<br>Equipo Prontophot.</p>
		</div>
		<div class="pie" style="padding: 10px 50px;display:flex; width:calc(100% - 100px); background-color: #000; position:relative; height:20px;">
			<a href="#" target="_blank" style="display:inline-block; width:25px;"><img alt="logo fb" style="height:20px;" src="' . URL_SITIO . 'img/face.png"></a>
			<a href="#" target="_blank" style="display:inline-block; width:25px;"><img alt="logo instagram" style="height:20px;" src="' . URL_SITIO . 'img/insta.png"></a>
		</div>
	</div>';
    $asunto = 'Pedido listo Prontophot';
    enviar_mail($correo, $nombre, $asunto, $cuerpo, $cuerpo);
}
function notificarPedido($pedido, $items, $metodo)
{
    global $conectar;
    $cl = $conectar->query("SELECT c.email,c.nombre,c.apellido,p.entrega FROM pedidos p LEFT JOIN clientes c ON p.id_cliente=c.id WHERE p.id='$pedido'");
    $rowc = $cl->fetch_assoc();
    $metodoretiro = metodoRetiro($metodo);
    $correo = $rowc['email'];
    $nombre = $rowc['nombre'] . ' ' . $rowc['apellido'];
    $envio = 0;
    if ($rowc['entrega'] == 'urbano') {
        $envio = COSTO_ENVIO;
        $costoenvio = '<tfoot>
					<tr>
						<th colspan="2" style="text-align: left;padding: 10px 15px;border-top: 1px solid #E6E6E6; ">Envio</th>
						<th style="text-align: left; font-weight:bold;padding: 10px 15px;border-top: 1px solid #E6E6E6; ">$ ' . COSTO_ENVIO . '</th>
					</tr>
				</tfoot>';
    } else {
        $costoenvio = '';
        $envio = 0;
    }
    $total = 0;
    $it = '';
    foreach ($items as $item) {
        $it .= '<tr>
						<td style="text-align: left;padding: 10px 15px;">' . $item['producto'] . '</td>
						<td style="text-align: left;padding: 10px 15px;">' . $item['cantidad'] . '</td>
						<td style="text-align: left;padding: 10px 15px; font-weight:bold;">$ ' . $item['precio'] . '</td>
					</tr>';
        $total = $total + $item['precio'];
    }
    $total = ($total + $envio);
    $cuerpo = '<div style="display:block;position:relative; min-width:400px; max-width:700px;">
		<div style="display:block; width: 100%; height:auto">
			<img src="' . URL_SITIO . 'img/header.png" style="width:100%;">
		</div>
		<div class="cuerpo" style="display:block; padding: 10px 50px;background-color:#fff; width:calc(100% - 100px);position: relative; min-height: 200px;">
			<h2 style="font: normal normal bold 18px/21px Arial;letter-spacing: 0px;color: #DA0000;">&iexcl;Hola ' . $nombre . '!</h2>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;color: #333333;">Tu pedido #' . $pedido . ' ha sido realizado correctamente.</p>
			<table style="font: normal normal normal 16px/18px Arial; width:100%;">
				<thead  style="background: #F8F8F8 0% 0% no-repeat padding-box;">
					<tr>
						<th style="width:50%;text-align: left; font-weight:bold;padding: 10px 15px;">Producto</th>
						<th style="width:25%;text-align: left; font-weight:bold;padding: 10px 15px;">Cantidad</th>
						<th style="width:25%;text-align: left; font-weight:bold;padding: 10px 15px;">Precio</th>
					</tr>
				</thead>
				<tbody>' . $it . '</tbody>
                <tfoot>
					<tr>
						<th colspan="2" style="text-align: left;padding: 10px 15px;border-top: 1px solid #E6E6E6; ">Total</th>
						<th style="text-align: left; font-weight:bold;padding: 10px 15px;border-top: 1px solid #E6E6E6; ">$ ' . $total . '</th>
					</tr>
				</tfoot>	
                ' . $costoenvio . '
			</table>
			<p style="text-align: left;font: normal normal bold 16px/18px Arial;letter-spacing: 0px;">M&eacute;todo de env&iacute;o</p>
			<p style="text-align: left;font: normal normal normal 16px/29px Arial;letter-spacing: 0px;">' . $metodoretiro . '</p>
			<br>
			<br>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;">Nos comunicaremos cuando tu pedido se encuentre listo.</p>
			<p style="text-align: left;font: normal normal normal 18px/21px Arial;letter-spacing: 0px;color: #DA0000;">&iexcl;Muchas gracias!<br>Equipo Prontophot.</p>
		</div>
		<div class="pie" style="padding: 10px 50px;display:flex; width:calc(100% - 100px); background-color: #000; position:relative; height:20px;">
			<a href="#" target="_blank" style="display:inline-block; width:25px;"><img alt="logo fb" style="height:20px;" src="' . URL_SITIO . 'img/face.png"></a>
			<a href="#" target="_blank" style="display:inline-block; width:25px;"><img alt="logo instagram" style="height:20px;" src="' . URL_SITIO . 'img/insta.png"></a>
		</div>
	</div>';
    $asunto = 'Nuevo Pedido Prontophot';
    enviar_mail($correo, $nombre, $asunto, $cuerpo, $cuerpo);
}
