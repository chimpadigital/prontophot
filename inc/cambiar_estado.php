<?php
include ('../conexion/conectar.inc.php');
include ('funciones.inc.php');
global $conectar;
$respuesta= new stdClass();

$pedido=$_POST['pedido'];
$estado=$_POST['estado'];

$query="UPDATE pedidos SET estado_pedido='$estado' WHERE id='$pedido' ";
$res=$conectar->query($query);
if($res){
    $respuesta->success=true;
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);