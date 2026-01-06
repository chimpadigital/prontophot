<?php
session_start();
$id=$_POST['id'];
$respuesta=new stdClass();
if (isset($_SESSION['pronto']['cart'][$id])) {
    unset($_SESSION['pronto']['cart'][$id]);
}

$respuesta->success=true;
echo json_encode($respuesta);