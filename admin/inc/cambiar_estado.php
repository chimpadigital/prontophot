<?php
include ('../../conexion/conectar.inc.php');
include '';
global $conectar;
$respuesta=new stdClass();
$id=$_POST['id'];

$query="UPDATE pedidos SET estado='1' WHERE id='$id'";
$res=$conectar->query($query);
if ($res) {
    $respuesta->success=true;
    $prod=$conectar>query("SELECT * FROM pedidos_detalle WHERE id_pedido='$id'");
    while ($row=$prod->fetch_assoc()) {
        $idp=$row['id_producto'];
        $cant=$row['cantidad'];
        $conectar->query("UPDATE `productos` SET `stock`=stock-'$cant' WHERE id=$idp");
    }
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);