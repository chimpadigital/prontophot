<?php
header('Content-Type: application/json');
include_once ('../../conexion/conectar.inc.php');
global $conectar;

try {
    // Validar que se recibieron los datos
    if (!isset($_POST['texto'])) {
        echo json_encode(['success' => false, 'msg' => 'Datos incompletos']);
        exit;
    }

    $texto = trim($_POST['texto']);
    $link = isset($_POST['link']) ? trim($_POST['link']) : '';
    $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 0;

    // Validar que el texto no esté vacío
    if (empty($texto)) {
        echo json_encode(['success' => false, 'msg' => 'El texto no puede estar vacío']);
        exit;
    }

    // Verificar si ya existe el registro con id=1
    $checkQuery = "SELECT id FROM topbar WHERE id=1 LIMIT 1";
    $result = $conectar->query($checkQuery);

    if ($result && $result->num_rows > 0) {
        // Actualizar el registro existente
        $stmt = $conectar->prepare("UPDATE topbar SET texto=?, link=?, activo=? WHERE id=1");
        $stmt->bind_param("ssi", $texto, $link, $activo);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'msg' => 'Top Bar actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Error al actualizar: ' . $conectar->error]);
        }
        $stmt->close();
    } else {
        // Insertar nuevo registro con id=1
        $stmt = $conectar->prepare("INSERT INTO topbar (id, texto, link, activo) VALUES (1, ?, ?, ?)");
        $stmt->bind_param("ssi", $texto, $link, $activo);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'msg' => 'Top Bar creado correctamente']);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Error al crear: ' . $conectar->error]);
        }
        $stmt->close();
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => 'Error: ' . $e->getMessage()]);
}
