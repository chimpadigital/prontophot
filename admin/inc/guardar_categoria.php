<?php
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;
$respuesta= new stdClass();
$id=$_POST['id'];
$cat=$_POST['cat'];
if (empty($id)) {
    $query="INSERT INTO `categorias`(`nombre`) VALUES ('$cat')";
    $mensaje="Categoria agregada";
}else{
    $query="UPDATE `categorias` SET `nombre`='$cat' WHERE `id`='$id'";
    $mensaje="Categoria actualizada";
}

$res=$conectar->query($query);
if($res){
    $respuesta->success=true;
    $respuesta->msg=$mensaje;
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);