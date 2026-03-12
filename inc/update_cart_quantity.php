<?php
session_start();
header('Content-Type: application/json');

try {
    // Validar que se recibieron los datos necesarios
    if (!isset($_POST['id']) || !isset($_POST['action'])) {
        echo json_encode(['success' => false, 'msg' => 'Datos incompletos']);
        exit;
    }

    $id = $_POST['id'];
    $action = $_POST['action']; // 'increase' o 'decrease'

    // Verificar que existe el carrito
    if (!isset($_SESSION['pronto']['cart'])) {
        echo json_encode(['success' => false, 'msg' => 'Carrito vacío']);
        exit;
    }

    // Verificar que el producto existe en el carrito
    if (!isset($_SESSION['pronto']['cart'][$id])) {
        echo json_encode(['success' => false, 'msg' => 'Producto no encontrado en el carrito']);
        exit;
    }

    $cantidadActual = $_SESSION['pronto']['cart'][$id]['cantidad'];

    if ($action === 'increase') {
        // Incrementar cantidad
        $_SESSION['pronto']['cart'][$id]['cantidad'] = $cantidadActual + 1;
        echo json_encode([
            'success' => true,
            'msg' => 'Cantidad incrementada',
            'cantidad' => $_SESSION['pronto']['cart'][$id]['cantidad']
        ]);
    } elseif ($action === 'decrease') {
        // Decrementar cantidad (mínimo 1)
        if ($cantidadActual > 1) {
            $_SESSION['pronto']['cart'][$id]['cantidad'] = $cantidadActual - 1;
            echo json_encode([
                'success' => true,
                'msg' => 'Cantidad decrementada',
                'cantidad' => $_SESSION['pronto']['cart'][$id]['cantidad']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'La cantidad mínima es 1. Use el botón eliminar para quitar el producto.'
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'msg' => 'Acción no válida']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => 'Error: ' . $e->getMessage()]);
}
