<?php
session_start();
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;
include 'seguridad.inc.php';
$usuario=$_POST['usuario'];
$passwd=$_POST['password'];
$respuesta=new stdClass();
$res=$conectar->query("SELECT * FROM usuarios WHERE usuario='$usuario'");
if ($res) {
    $row=$res->fetch_assoc();
    if ($row['activo']=='1') {
        $original = $passwd;
        $codificado = $row['passwd'];
        $iguales = password_verify($original, $codificado);
        if ($iguales) {
            $respuesta->sid = session_id();
            $respuesta->success=true;
            $id=$row['id'];
            $nivel=$row['nivel'];
            $_SESSION['pronto']['token'] = creaToken($id, $nivel);
            session_write_close();
        }else{
            $respuesta->success=false;
            $respuesta->msg="Clave Incorrecta";
        }
    }else{
        $respuesta->success=false;
        $respuesta->msg="Usuario no activo";
    }
}else{
    $respuesta->success=false;
    $respuesta->msg="No existe el usuario";
}
echo json_encode($respuesta);