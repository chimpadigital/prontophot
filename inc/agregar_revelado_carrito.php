<?php
session_start();
header('Content-Type: application/json');

try {
    // Verificar que existan archivos de revelado en sesión
    if (!isset($_SESSION['archivos']) || count($_SESSION['archivos']) == 0) {
        echo json_encode(['success' => false, 'msg' => 'No hay archivos de revelado en la sesión']);
        exit;
    }

    // Obtener ID del producto especial de revelado
    include_once ('../conexion/conectar.inc.php');
    global $conectar;

    $queryProducto = $conectar->query("SELECT id FROM productos WHERE tipo_producto='revelado' LIMIT 1");
    if (!$queryProducto || $queryProducto->num_rows == 0) {
        echo json_encode(['success' => false, 'msg' => 'Producto de revelado no encontrado. Ejecutar migración primero.']);
        exit;
    }

    $productoRevelado = $queryProducto->fetch_assoc();
    $id_producto_revelado = $productoRevelado['id'];

    // Calcular el costo total del revelado basado en las impresiones
    $costototal = 0;
    $tama = array();
    foreach ($_SESSION['archivos'] as $key => $impre) {
        $tam = $impre['tamano'];
        $c = (int)$impre['cantidad'];
        if (isset($tama[$tam])) {
            $tama[$tam] = $tama[$tam] + $c;
        } else {
            $tama[$tam] = $c;
        }
    }

    foreach ($tama as $tam => $cant) {
        $query = "SELECT * FROM `impresiones` WHERE formato='$tam' AND desde<='$cant' AND hasta>='$cant' ORDER BY id DESC LIMIT 1";
        $calc = $conectar->query($query);
        if ($calc && $calc->num_rows > 0) {
            $row = $calc->fetch_assoc();
            $p = $row['precio'];
            $precio = ($p * $cant);
            $costototal = $costototal + $precio;
        }
    }

    // Preparar datos del carrito
    $hoy = time();
    $_SESSION['pronto']['cart_creado'] = $hoy;
    $_SESSION['pronto']['cart_expira'] = $hoy + 1800;

    // Contar total de fotos
    $total_fotos = 0;
    foreach ($_SESSION['archivos'] as $img) {
        $total_fotos += intval($img['cantidad']);
    }

    // Preparar resumen de impresiones para descripción
    $resumen_impresiones = [];
    foreach ($_SESSION['archivos'] as $img) {
        $key = $img['tamano'] . '_' . $img['acabado'];
        if (!isset($resumen_impresiones[$key])) {
            $resumen_impresiones[$key] = [
                'tamano' => $img['tamano'],
                'acabado' => $img['acabado'],
                'cantidad' => 0
            ];
        }
        $resumen_impresiones[$key]['cantidad'] += intval($img['cantidad']);
    }

    // Crear descripción
    $descripcion = '';
    foreach ($resumen_impresiones as $item) {
        $descripcion .= $item['cantidad'] . ' fotos ' . $item['tamano'] . ' ' . $item['acabado'] . ' | ';
    }
    $descripcion = rtrim($descripcion, ' | ');

    // Agregar al carrito
    $_SESSION['pronto']['cart'][$id_producto_revelado] = [
        'color' => 'N/A',
        'cantidad' => $total_fotos,
        'cat' => 'revelado',
        'precio' => $costototal,
        'tipo' => 'revelado',
        'id_original' => $id_producto_revelado,
        'descripcion' => $descripcion,
        'datos_revelado' => [
            'imagenes' => $_SESSION['archivos']
        ]
    ];

    // Limpiar datos de revelado de la sesión de envío (se calcularán de nuevo en checkout)
    // Pero mantener $_SESSION['archivos'] por si el usuario quiere editarlo

    echo json_encode([
        'success' => true,
        'msg' => 'Revelado agregado al carrito exitosamente',
        'total_fotos' => $total_fotos,
        'id_producto' => $id_producto_revelado
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => 'Error: ' . $e->getMessage()]);
}
