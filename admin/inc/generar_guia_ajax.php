<?php
/**
 * Endpoint AJAX para generar guía de Epresis desde el panel de administración
 */
session_start();
header('Content-Type: application/json');

include __DIR__.'/../../inc/epresis_guia.php';

$respuesta = ['success' => false];

// Verificar que se reciba el pedido_id
if (!isset($_POST['pedido_id'])) {
    $respuesta['error'] = 'ID de pedido no proporcionado';
    echo json_encode($respuesta);
    exit;
}

$pedido_id = intval($_POST['pedido_id']);

// Generar la guía
$resultado = generarGuiaEpresis($pedido_id);

// Retornar el resultado
if ($resultado->success) {
    $respuesta['success'] = true;
    $respuesta['guia'] = $resultado->guia;
    $respuesta['importe'] = $resultado->importe;
    $respuesta['remito'] = $resultado->remito;
    $respuesta['sub_zona_destino'] = $resultado->sub_zona_destino;
    $respuesta['zona'] = $resultado->zona ?? '';

    if (isset($resultado->ya_existe) && $resultado->ya_existe) {
        $respuesta['mensaje'] = 'Guía ya existente';
    } else {
        $respuesta['mensaje'] = 'Guía generada exitosamente';
    }
} else {
    $respuesta['error'] = $resultado->error ?? 'Error desconocido al generar la guía';
    if (isset($resultado->detalles)) {
        $respuesta['detalles'] = $resultado->detalles;
    }
}

echo json_encode($respuesta);
?>
