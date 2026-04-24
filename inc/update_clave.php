<?php
include ('../../conexion/conectar.inc.php');
include 'funciones.inc.php';
global $conectar;
$respuesta= new stdClass();
$code=$_POST['code'];
$pass=password_hash($_POST['pass'], PASSWORD_DEFAULT);
$res1=$conectar->query("SELECT * FROM clientes WHERE code='$code'");
if($res1->num_rows>0){
    $row=$res1->fetch_assoc();
    $id=$row['id'];
    $res=$conectar->query("UPDATE clientes SET passwd='$pass',code='' WHERE id='$id'");
    if($res){
        $respuesta->success=true;
    }else{
        $respuesta->success=false;
        $respuesta->msg="error al guardar, intente de nuevo o solicite un nuevo codigo";
    }
}else{
    $respuesta->success=false;
    $respuesta->msg="Codigo invalido, solicite de nuevo ";
}

echo json_encode($respuesta);