<?php 
if(session_status() === PHP_SESSION_NONE) session_start();
ini_set("log_errors", 1);
ini_set("error_log", "crearpago.log");
include 'config.inc.php';
require 'vendor/autoload.php';
include 'funciones.inc.php';
global $conectar;
MercadoPago\SDK::setAccessToken(ML_TOKEN);
$respuesta=new stdClass();
$metodo=$_POST['metodo'];
$desc=$_POST['desc'];
$desc = preg_replace("<br>","\r\n",$desc);
$monto=$_SESSION['prontoFront']['valor'];
if (isset($_SESSION['prontoFront']['cupon'])) {
    $descuento=$_SESSION['prontoFront']['cupon']['valor'];
    $dm=($monto * $descuento)/100;
    unset($_SESSION['prontoFront']['cupon']);
}else{
    $dm=0;
}
unset($_SESSION['prontoFront']['cupon']);
$monto=$monto-$dm;
//crear pedido
$param=array();
$entrega=$_SESSION['prontoFront']['envio']['tipo'];
$param['entrega']=$entrega;
$param['envio']=$_SESSION['prontoFront']['envio']['envio'];
$param['costo']=$_SESSION['prontoFront']['envio']['costo'];
$param['idcliente']=$_SESSION['prontoFront']['idcliente'];
$param['nombre']=$_SESSION['prontoFront']['envio']['nombre'].' '.$_SESSION['prontoFront']['envio']['apellido'];
$param['direccion']=$_SESSION['prontoFront']['envio']['direccion'];
$param['cp']=$_SESSION['prontoFront']['envio']['cp'];
$param['altura']=$_SESSION['prontoFront']['envio']['altura'] ?? null;
$param['ciudad']=$_SESSION['prontoFront']['envio']['ciudad'];
$param['provincia']=$_SESSION['prontoFront']['envio']['provincia'];
$param['telefono']=$_SESSION['prontoFront']['envio']['telefono'].' - '.$_SESSION['prontoFront']['envio']['celular'];
$param['dni']=$_SESSION['prontoFront']['envio']['dni'];
$param['email']=$_SESSION['prontoFront']['envio']['email'];
$param['valor']=$monto;
$param['metodo']=$metodo;
$param['desc']=$desc;
$param['metodo_envio_id']=$_SESSION['prontoFront']['envio']['metodo_envio_id'] ?? null;
$param['epresis_tiempo_entrega']=$_SESSION['prontoFront']['envio']['epresis_fecha'] ?? null;

// Agregar datos de facturación desde el POST
if(isset($_POST['facturacion'])){
    $param['facturacion']=$_POST['facturacion'];
}

$pedido=crearPedido($param);



if($pedido->success){
    $idpedido=$pedido->pedido;
    $respuesta->success=true;
    //
    $imagenes=$_SESSION['archivos'];
    $items=array();
    foreach ($imagenes as $imagen){
        $item['producto']='Impresion '.$imagen['tamano'].' '.$imagen['acabado'];
        $cant=$imagen['cantidad'];
        $item['cantidad']=$cant;
        $idimp=$imagen['idimpresion'];
        $calc=$conectar->query("SELECT * FROM `impresiones` WHERE id='$idimp'");
        $rowi=$calc->fetch_assoc();
        $p=$rowi['precio'];
        $precio=($p * $cant);
        $item['precio']=$precio;
        $items[]=$item;
    }
    notificarPedido($idpedido, $items, $entrega);
    pedidoImagenes($idpedido, $imagenes);
    //
    $preference = new MercadoPago\Preference();
    
    // Crea un �tem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = $desc;
    $item->quantity = 1;
    $item->unit_price = $monto;
    $preference->items = array($item);
    $preference->back_urls = array(
        "success" => URL_SITIO."revelado-paso4",
        "failure" => URL_SITIO."revelado-paso4",
        "pending" => URL_SITIO."revelado-paso4"
    );
    $preference->auto_return = "approved"; 
    $preference->binary_mode = true;
    $preference->external_reference =$idpedido;
    $preference->save();
    
    $idventa=$preference->id;
    //$link=$preference->sandbox_init_point;
    $link=$preference->init_point;
    //print_r($preference);
    $respuesta->id=$idventa;
    $respuesta->url=$link;
    
    
}else{
    $respuesta->success=false;
    error_log($pedido->error);
    $respuesta->error=$pedido->error;
}


echo json_encode($respuesta);
?>