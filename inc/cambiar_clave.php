<?php
include ('../conexion/conectar.inc.php');
include ('funciones.inc.php');
global $conectar;
$respuesta= new stdClass();

$pass=password_hash($_POST['pass'], PASSWORD_DEFAULT);
$id=$_POST['id'];

$res=$conectar->query("UPDATE clientes SET passwd='$pass' WHERE id='$id'");
if($res){
    $respuesta->success=true;
}else{
    $respuesta->success=false;
    $respuesta->error=$conectar->error;
}
echo json_encode($respuesta);