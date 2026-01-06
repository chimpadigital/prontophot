<?php
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;
$respuesta=new stdClass();
$id=$_POST['id'];
$tabla=$_POST['tabla'];
$query="DELETE FROM `$tabla` WHERE id='$id'";
$res=$conectar->query($query);
if ($res) {
    $respuesta->success=true;
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);