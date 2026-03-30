<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ini_set("log_errors", 1);
ini_set("error_log", "getnet_webhook.log");

include 'config.inc.php';
include '../conexion/conectar.inc.php';
global $conectar;

// Obtener el payload del webhook
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// Log del webhook recibido
error_log("GetNet Webhook received: " . $payload);

// Validar que se recibieron datos
if (!$data) {
    error_log("GetNet Webhook: Invalid JSON payload");
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload']);
    exit;
}

// Extraer datos según el formato real de GetNet
$payment_intent_id = isset($data['payment_intent_id']) ? $conectar->real_escape_string($data['payment_intent_id']) : null;
$checkout_id = isset($data['checkout_id']) ? $conectar->real_escape_string($data['checkout_id']) : null;
$order_id = isset($data['order_id']) ? $conectar->real_escape_string($data['order_id']) : null;

// Extraer datos del pago
$payment_id = null;
$status = null;
$amount = null;
$currency = 'ARS';

if (isset($data['payment']) && isset($data['payment']['result'])) {
    $payment_id = $conectar->real_escape_string($data['payment']['result']['payment_id']);
    $status = $conectar->real_escape_string($data['payment']['result']['status']);
    $amount = isset($data['payment']['amount']) ? $conectar->real_escape_string($data['payment']['amount']) : null;
    $currency = isset($data['payment']['currency']) ? $conectar->real_escape_string($data['payment']['currency']) : 'ARS';
}

// Validar campos requeridos
if (!$payment_id || !$order_id || !$status) {
    error_log("GetNet Webhook: Missing required fields - payment_id: $payment_id, order_id: $order_id, status: $status");
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Validar que el pedido existe
$pedido_check = $conectar->query("SELECT id FROM pedidos WHERE id='$order_id'");
if ($pedido_check->num_rows == 0) {
    error_log("GetNet Webhook: Order not found - order_id: $order_id");
    http_response_code(404);
    echo json_encode(['error' => 'Order not found']);
    exit;
}

// Verificar si ya existe un pago para este pedido y payment_id
$check_sql = "SELECT id FROM pagos WHERE id_pedido='$order_id' AND id_payment='$payment_id'";
$check_result = $conectar->query($check_sql);

if ($check_result->num_rows > 0) {
    // Ya existe, actualizar el status
    $update_sql = "UPDATE pagos SET status='$status' WHERE id_pedido='$order_id' AND id_payment='$payment_id'";
    $update_result = $conectar->query($update_sql);

    if ($update_result) {
        error_log("GetNet Webhook: Payment updated - order_id: $order_id, payment_id: $payment_id, status: $status");

        // Si el pago fue aprobado o autorizado, actualizar estado del pedido
        if ($status === 'approved' || $status === 'Authorized') {
            $conectar->query("UPDATE pedidos SET estado='approved' WHERE id='$order_id'");
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Payment updated']);
    } else {
        error_log("GetNet Webhook: Error updating payment - " . $conectar->error);
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} else {
    // No existe, crear nuevo registro
    $insert_sql = "INSERT INTO pagos (id_pedido, id_payment, status, creado) VALUES ('$order_id', '$payment_id', '$status', NOW())";
    $insert_result = $conectar->query($insert_sql);

    if ($insert_result) {
        error_log("GetNet Webhook: Payment created - order_id: $order_id, payment_id: $payment_id, status: $status");

        // Si el pago fue aprobado o autorizado, actualizar estado del pedido
        if ($status === 'approved' || $status === 'Authorized') {
            $conectar->query("UPDATE pedidos SET estado='confirmado' WHERE id='$order_id'");
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Payment created']);
    } else {
        error_log("GetNet Webhook: Error creating payment - " . $conectar->error);
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

exit;
