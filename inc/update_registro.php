<?php
include __DIR__.'/../conexion/conectar.inc.php';
include __DIR__.'/funciones.inc.php';
include __DIR__.'/seguridad.inc.php';
global $conectar;
$respuesta= new stdClass();
$id=$_POST['id'];
$nombre=$_POST['nombre'];
$apellido=$_POST['apellido'];
$dni=$_POST['dni'];
$email=$_POST['email'];

$direccion=$_POST['direccion'];
$provincia=$_POST['provincia'];
$ciudad=$_POST['ciudad'];
$cp=$_POST['cp'];
$telefono=$_POST['telefono'];

$query="UPDATE `clientes` SET `nombre`='$nombre',`apellido`='$apellido',`dni`='$dni',`email`='$email',`direccion`='$direccion',`provincia`='$provincia',`ciudad`='$ciudad',`cp`='$cp',`telefono`='$telefono' WHERE id='$id'";
$res=$conectar->query($query);
if($res){
    $respuesta->success=true;
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);