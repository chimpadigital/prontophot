<?php
session_start();
header('Content-Type: application/json');

// Log para debugging
error_log("=== obtener_fotos_revelado.php ===");
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION cart: " . print_r($_SESSION['pronto']['cart'] ?? 'NO EXISTE', true));

try {
    // Verificar que se reciba el ID del producto
    if (!isset($_POST['producto_id'])) {
        error_log("ERROR: ID de producto no proporcionado");
        echo json_encode(['success' => false, 'error' => 'ID de producto no proporcionado', 'debug' => 'POST vacío']);
        exit;
    }

    $producto_id = $_POST['producto_id'];
    error_log("Producto ID recibido: " . $producto_id);

    // Verificar que existe el carrito
    if (!isset($_SESSION['pronto']['cart'])) {
        error_log("ERROR: No existe SESSION['pronto']['cart']");
        echo json_encode(['success' => false, 'error' => 'No hay carrito en la sesión', 'debug' => 'SESSION[pronto][cart] no existe']);
        exit;
    }

    if (!isset($_SESSION['pronto']['cart'][$producto_id])) {
        error_log("ERROR: Producto $producto_id no encontrado en carrito");
        error_log("IDs disponibles en carrito: " . implode(', ', array_keys($_SESSION['pronto']['cart'])));
        echo json_encode([
            'success' => false,
            'error' => 'Producto no encontrado en el carrito',
            'debug' => 'Producto ID: ' . $producto_id,
            'ids_disponibles' => array_keys($_SESSION['pronto']['cart'])
        ]);
        exit;
    }

    $producto = $_SESSION['pronto']['cart'][$producto_id];
    error_log("Producto encontrado: " . print_r($producto, true));

    // Verificar que es un producto de revelado
    if (!isset($producto['tipo']) || $producto['tipo'] !== 'revelado') {
        error_log("ERROR: Producto no es de tipo revelado. Tipo: " . ($producto['tipo'] ?? 'NO DEFINIDO'));
        echo json_encode([
            'success' => false,
            'error' => 'El producto no es de tipo revelado',
            'debug' => 'Tipo actual: ' . ($producto['tipo'] ?? 'NO DEFINIDO')
        ]);
        exit;
    }

    // Obtener las imágenes
    if (!isset($producto['datos_revelado'])) {
        error_log("ERROR: No existe datos_revelado");
        echo json_encode([
            'success' => false,
            'error' => 'No existe el campo datos_revelado',
            'debug' => 'Campos disponibles: ' . implode(', ', array_keys($producto))
        ]);
        exit;
    }

    if (!isset($producto['datos_revelado']['imagenes']) || empty($producto['datos_revelado']['imagenes'])) {
        error_log("ERROR: No hay imágenes en datos_revelado");
        echo json_encode([
            'success' => false,
            'error' => 'No hay imágenes en este revelado',
            'debug' => 'datos_revelado existe pero imagenes está vacío o no existe'
        ]);
        exit;
    }

    $imagenes = $producto['datos_revelado']['imagenes'];
    $fotos = [];
    $total_fotos = 0;
    $resumen = [];

    foreach ($imagenes as $imagen) {
        $fotos[] = [
            'archivo' => $imagen['archivo'],
            'tamano' => $imagen['tamano'],
            'acabado' => $imagen['acabado'],
            'cantidad' => intval($imagen['cantidad'])
        ];

        $total_fotos += intval($imagen['cantidad']);

        // Agrupar por tipo para el resumen
        $key = $imagen['tamano'] . ' ' . $imagen['acabado'];
        if (!isset($resumen[$key])) {
            $resumen[$key] = 0;
        }
        $resumen[$key] += intval($imagen['cantidad']);
    }

    echo json_encode([
        'success' => true,
        'fotos' => $fotos,
        'total_fotos' => $total_fotos,
        'resumen' => $resumen
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener las fotos: ' . $e->getMessage()]);
}
?>
