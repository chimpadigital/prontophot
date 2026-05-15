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

$pedido=crearPedido($param);
$imagenes=$_SESSION['archivos'];
pedidoImagenes($pedido->pedido, $imagenes);
if($pedido->success){
    $idpedido=$pedido->pedido;
    $respuesta->success=true;
    
    //
    $carro=$_SESSION['pronto']['cart'];
    $items=array();
    foreach($carro as $id=>$datos){
        $res=$conectar->query("SELECT p.nombre,p.descripcion, p.precio,(SELECT imagen FROM `imagenes` WHERE id_producto='$id' ORDER BY id ASC LIMIT 1) as imagen FROM productos p  WHERE p.id='$id' ");
        $row=$res->fetch_assoc();
        $cant=$datos['cantidad'];
        $precio=($cant * $row['precio']);
        $color = isset($datos['color']) ? $conectar->real_escape_string($datos['color']) : NULL;
        $item['producto']=$row['nombre'].' '.$datos['color'];
        $item['cantidad']=$cant;
        $item['precio']=$precio;

        // Guardar producto con color
        if($color !== NULL){
            $conectar->query("INSERT INTO `pedidos_detalle`( `id_pedido`, `id_producto`, `cantidad`, `color`) VALUES ('$idpedido','$id','$cant','$color')");
        } else {
            $conectar->query("INSERT INTO `pedidos_detalle`( `id_pedido`, `id_producto`, `cantidad`) VALUES ('$idpedido','$id','$cant')");
        }
        $items[]=$item;
    }
    
    //notificarPedido($idpedido, $items, $entrega);
    //
    
    $preference = new MercadoPago\Preference();
    
    // Crea un �tem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = $desc;
    $item->quantity = 1;
    $item->unit_price = $monto;
    $preference->items = array($item);
    $preference->back_urls = array(
        "success" => URL_SITIO."usuario/index_p4.php",
        "failure" => URL_SITIO."usuario/index_p4.php",
        "pending" => URL_SITIO."usuario/index_p4.php"
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