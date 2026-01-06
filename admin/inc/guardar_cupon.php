<?php
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;
$respuesta= new stdClass();
$id=isset($_POST['id'])?$_POST['id']:'';
$nombre=$_POST['nombre'];
$descuento=$_POST['descuento'];
$desde=$_POST['desde'];
$hasta=$_POST['hasta'];
$seccion=$_POST['seccion'];
$categorias=serialize($_POST['categorias']);
if (empty($id)) {
    $query="INSERT INTO `cupones`(`categorias`,`seccion`,`nombre`, `descuento`, `desde`, `hasta`) VALUES ('$categorias','$seccion','$nombre','$descuento','$desde','$hasta')";
    $mensaje="Cupon agregado";
}else{
    $query="UPDATE `cupones` SET `categorias`='$categorias',`seccion`='$seccion',`nombre`='$nombre',`descuento`='$descuento',`desde`='$desde',`hasta`='$hasta' WHERE  `id`='$id'";
    $mensaje="Cupon actualizado";
}

$res=$conectar->query($query);
if($res){
    $respuesta->success=true;
    $respuesta->msg=$mensaje;
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);