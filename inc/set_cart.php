<?php
session_start();
header('Content-Type: application/json');

$id = isset($_POST['id']) ? trim($_POST['id']) : null;
$cant = isset($_POST['cant']) ? intval($_POST['cant']) : 1;
$color = isset($_POST['color']) ? trim(strtolower($_POST['color'])) : 'sin_color';
$categoria = isset($_POST['cat']) ? trim($_POST['cat']) : '';
$precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;

if (!$id) {
    echo json_encode(['success' => false, 'msg' => 'Producto no válido']);
    exit;
}

$hoy = time();
$_SESSION['pronto']['cart_creado'] = $hoy;
$_SESSION['pronto']['cart_expira'] = $hoy + 1800;

// Verificar si el producto con el mismo color ya existe en el carrito
if (isset($_SESSION['pronto']['cart'][$id])) {
    $color_existente = isset($_SESSION['pronto']['cart'][$id]['color']) ? trim(strtolower($_SESSION['pronto']['cart'][$id]['color'])) : '';

    // Debug log (temporal)
    //error_log("DEBUG - ID: $id, Color nuevo: '$color', Color existente: '$color_existente'");

    // Mismo producto y mismo color - devolver error
    if ($color_existente === $color) {
        //error_log("DEBUG - Producto duplicado detectado!");
        echo json_encode(['success' => false, 'msg' => 'Ya tienes este artículo en tu carrito']);
        exit;
    }

    // Si es el mismo producto pero diferente color, usar un ID único
    if ($color_existente !== $color) {
        $id_original = $id;
        $contador = 1;

        // Buscar un ID disponible para este producto con diferente color
        while (isset($_SESSION['pronto']['cart'][$id])) {
            $id = $id_original . '_' . $contador;
            $contador++;

            // Verificar si este nuevo ID ya existe con el mismo color
            if (isset($_SESSION['pronto']['cart'][$id])) {
                $color_en_bucle = isset($_SESSION['pronto']['cart'][$id]['color']) ? trim(strtolower($_SESSION['pronto']['cart'][$id]['color'])) : '';
                if ($color_en_bucle === $color) {
                    echo json_encode(['success' => false, 'msg' => 'Ya tienes este artículo en tu carrito']);
                    exit;
                }
            }

            // Si no existe, salir del bucle
            if (!isset($_SESSION['pronto']['cart'][$id])) {
                break;
            }
        }
    }
}

$_SESSION['pronto']['cart'][$id]['color'] = $color;
$_SESSION['pronto']['cart'][$id]['cantidad'] = $cant;
$_SESSION['pronto']['cart'][$id]['cat'] = $categoria;
$_SESSION['pronto']['cart'][$id]['precio'] = $precio;
$_SESSION['pronto']['cart'][$id]['id_original'] = isset($_POST['id']) ? $_POST['id'] : $id;

echo json_encode(['success' => true, 'msg' => 'Producto agregado al carrito']);

