<?php
session_start();
include '../inc/seguridad.inc.php';
if (isset($_SESSION['prontoFront']['token'])) {
    $token=$_SESSION['prontoFront']['token'];
    $key="Pronto";
    $ingresar=verificarToken($token, $key);
    echo date('Y-m-d h:i:s').'<br>';
    echo date('Y-m-d h:i:s',$ingresar->venc);
    echo '<pre>';
    print_r($ingresar);
    echo '</pre>';
}