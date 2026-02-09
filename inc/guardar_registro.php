<?php
include __DIR__.'/../conexion/conectar.inc.php';
include __DIR__.'/funciones.inc.php';
include __DIR__.'/seguridad.inc.php';
global $conectar;
$respuesta= new stdClass();

$nombre=$_POST['nombre'];
$apellido=$_POST['apellido'];
$dni=$_POST['dni'];
$cuil=$_POST['cuil'] ?? '';
$email=$_POST['email'];
$pass=password_hash($_POST['passwd'],  PASSWORD_DEFAULT);

$direccion=$_POST['direccion'];
$provincia=$_POST['provincia'];
$ciudad=$_POST['ciudad'];
$cp=$_POST['cp'];
$altura=$_POST['altura'];
$telefono=$_POST['telefono'];
$code=generarCodigo(64);
$existe=existeCorreo($email);

if($existe->success==false){
    $query="INSERT INTO `clientes`(`nombre`, `apellido`, `dni`, `cuil`, `email`, `passwd`, `direccion`, `provincia`, `ciudad`, `cp`, `altura`, `telefono`, `code`, `activo`, `nivel`) VALUES ('$nombre','$apellido','$dni','$cuil','$email','$pass','$direccion','$provincia','$ciudad','$cp','$altura','$telefono','$code','1','0')";
    $res=$conectar->query($query);
    if($res){
        $respuesta->success=true;
        $id=$conectar->insert_id;
        $nivel='0';
        $_SESSION['prontoFront']['token']=creaToken($id, $nivel);
    }else{
        $respuesta->success=false;
        $respuesta->msg='Error al crear la cuenta, intente de nuevo';
    }
}else{
    $respuesta->success=false;
    $respuesta->msg='Email ya registrado';
}
echo json_encode($respuesta);