<?php
session_start();
include '../../inc/seguridad.inc.php';

if (isset($_SESSION['prontoFront']['token'])) {
    $token = $_SESSION['prontoFront']['token'];
    $key = "Pronto";
    $ingresar = verificarToken($token, $key);
    if ($ingresar->success == false) {
        echo json_encode(['success' => false, 'error' => 'Token inválido']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $valor = $_POST['valor'] ?? null;
    $valor_gratis = $_POST['valor_gratis'] ?? null;

    if (!$id || !$nombre || !$valor) {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        exit();
    }

    $stmt = $conectar->prepare("UPDATE metodos_envio SET nombre = ?, valor = ?, valor_gratis = ? WHERE id = ?");
    $stmt->bind_param("sdi", $nombre, $valor, $valor_gratis, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conectar->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>