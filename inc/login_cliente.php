<?php
session_start();
include ('../../conexion/conectar.inc.php');
include 'funciones.inc.php';
include 'seguridad.inc.php';
global $conectar;
$respuesta= new stdClass();


$email=$_POST['email'];

$pass=$_POST['passwd'];



$query="SELECT * FROM clientes WHERE email='$email'";
$res=$conectar->query($query);
if($res->num_rows>0){
    $row=$res->fetch_assoc();
    if($row['activo']==1){
        $original = $pass;
        $codificado = $row['passwd'];
        $iguales = password_verify($original, $codificado);
        if ($iguales) {
            $respuesta->success=true;
            $id=$row['id'];
            $nivel=$row['nivel'];
            $_SESSION['prontoFront']['token']=creaToken($id, $nivel);
        }else{
            $respuesta->success=false;
            $respuesta->msg='La clave es erronea';
        }
    }else{
        $respuesta->success=false;
        $respuesta->msg='Cuenta no activa, revise su correo para activarla';
    }
}else{
    $respuesta->success=false;
    $respuesta->msg='No existe la Cuenta';
}
echo json_encode($respuesta);