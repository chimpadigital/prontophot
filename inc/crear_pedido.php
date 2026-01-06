<?php
if(session_status() === PHP_SESSION_NONE) session_start();
ini_set("log_errors", 1);
ini_set("error_log", "crearpedido.log");
include 'config.inc.php';
include 'funciones.inc.php';
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
$param['ciudad']=$_SESSION['prontoFront']['envio']['ciudad'];
$param['provincia']=$_SESSION['prontoFront']['envio']['provincia'];
$param['telefono']=$_SESSION['prontoFront']['envio']['telefono'].' - '.$_SESSION['prontoFront']['envio']['celular'];
$param['dni']=$_SESSION['prontoFront']['envio']['dni'];
$param['email']=$_SESSION['prontoFront']['envio']['email'];
$param['valor']=$monto;
$param['metodo']=$metodo;
$param['desc']=$desc;
$pedido=crearPedido($param);

if($pedido->success){
    $idpedido=$pedido->pedido;
    $respuesta->success=true;
    $respuesta->pedido=$idpedido;
    $_SESSION['prontoFront']['pedido']=$idpedido;
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
    
    $_SESSION['prontoFront']['pedido']=$idpedido;
    
}else{
    $respuesta->success=false;
    error_log($pedido->error);
    $respuesta->error=$pedido->error;
}


echo json_encode($respuesta);
?>