<?php
include ('../../conexion/conectar.inc.php');
global $conectar;

$respuesta = new stdClass();

if (isset($_POST['coeficiente'])) {
    $coeficiente = floatval($_POST['coeficiente']);

    // Validar que el coeficiente esté en un rango razonable
    if ($coeficiente < 1 || $coeficiente > 2) {
        $respuesta->success = false;
        $respuesta->error = 'El coeficiente debe estar entre 1 y 2';
        echo json_encode($respuesta);
        exit;
    }

    // Actualizar el registro con id 1
    $query = "UPDATE `tax` SET `coeficiente` = $coeficiente, `update_at` = NOW() WHERE `id` = 1";

    if ($conectar->query($query)) {
        $respuesta->success = true;
        $respuesta->fecha = date('d-m-Y H:i');
        $respuesta->coeficiente = $coeficiente;
    } else {
        $respuesta->success = false;
        $respuesta->error = $conectar->error;
    }
} else {
    $respuesta->success = false;
    $respuesta->error = 'No se recibió el coeficiente';
}

echo json_encode($respuesta);
