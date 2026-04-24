<?php
/**
 * Endpoint AJAX para generar guía de Epresis desde el panel de administración
 */
session_start();
header('Content-Type: application/json');

include ('../../inc/epresis_guia.php');

$respuesta = ['success' => false];

// Verificar que se reciba el pedido_id
if (!isset($_POST['pedido_id'])) {
    $respuesta['error'] = 'ID de pedido no proporcionado';
    echo json_encode($respuesta);
    exit;
}

$pedido_id = intval($_POST['pedido_id']);

// Recopilar datos personalizados del modal si están presentes
$datos_personalizados = [];
$campos_opcionales = [
    // Datos del destinatario
    'empresa', 'calle', 'altura', 'piso', 'dpto', 'hora_desde', 'hora_hasta',
    'celular', 'cuit', 'contenido', 'info_adicional_1', 'info_adicional_2',
    // Configuración del envío
    'fragil', 'is_urgente', 'valida_stock', 'guia_agente', 'precinto',
    'codigo_ceco', 'contrareembolso', 'cobro_efectivo', 'cobro_cheque',
    'canal', 'codigo_expreso', 'observaciones'
];

foreach ($campos_opcionales as $campo) {
    if (isset($_POST[$campo]) && $_POST[$campo] !== '') {
        $datos_personalizados[$campo] = $_POST[$campo];
    }
}

// Generar la guía con datos personalizados
$resultado = generarGuiaEpresis($pedido_id, $datos_personalizados);

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
    $respuesta['data'] = $resultado->data ?? 'Error desconocido al generar la guía';
    if (isset($resultado->detalles)) {
        $respuesta['detalles'] = $resultado->detalles;
    }
}

echo json_encode($respuesta);
?>
