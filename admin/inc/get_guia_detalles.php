<?php
/**
 * API para obtener detalles de una guía de Epresis
 */
session_start();
header('Content-Type: application/json');

include '../../conexion/conectar.inc.php';
global $conectar;

$respuesta = ['success' => false];

if (!isset($_GET['id'])) {
    $respuesta['error'] = 'ID no proporcionado';
    echo json_encode($respuesta);
    exit;
}

$id = intval($_GET['id']);

$query = $conectar->query("
    SELECT
        eg.*,
        p.id as pedido_numero,
        p.nombre as cliente_nombre,
        p.direccion,
        p.ciudad,
        p.provincia,
        p.cp,
        p.total as pedido_total
    FROM epresis_guias eg
    LEFT JOIN pedidos p ON eg.pedido_id = p.id
    WHERE eg.id = '$id'
");

if ($query && $query->num_rows > 0) {
    $guia = $query->fetch_assoc();
    $respuesta['success'] = true;
    $respuesta['guia'] = $guia;
} else {
    $respuesta['error'] = 'Guía no encontrada';
}

echo json_encode($respuesta);
?>
