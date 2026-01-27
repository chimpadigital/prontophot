<?php
session_start();
header('Content-Type: application/json');

$respuesta = ['success' => false];

// Verificar que se reciban los datos necesarios
if (!isset($_POST['costo'])) {
    $respuesta['error'] = 'Costo de envío no proporcionado';
    echo json_encode($respuesta);
    exit;
}

$costo = floatval($_POST['costo']);
$fecha = $_POST['fecha'] ?? '';

// Actualizar los datos de envío en la sesión
$_SESSION['prontoFront']['envio']['costo'] = $costo;

if (!empty($fecha)) {
    $_SESSION['prontoFront']['envio']['epresis_fecha'] = $fecha;
}

// Recalcular el valor total del pedido
if (isset($_SESSION['pronto']['cart']) && isset($_SESSION['prontoFront']['monto'])) {
    include __DIR__.'/../conexion/conectar.inc.php';
    global $conectar;

    $cart = $_SESSION['pronto']['cart'];
    $subtotal = 0;

    foreach ($cart as $id => $item) {
        $cant = $item['cantidad'];

        // Obtener precio y descuento del producto
        $prod_res = $conectar->query("SELECT precio, descuento_final FROM productos WHERE id='$id'");
        $prod_row = $prod_res->fetch_assoc();
        $precioUnitario = $prod_row['precio'];
        $descuento = isset($prod_row['descuento_final']) && $prod_row['descuento_final'] > 0 ? $prod_row['descuento_final'] : 0;

        // Aplicar descuento si existe
        if($descuento > 0){
            $precioUnitario = $prod_row['precio'] - ($prod_row['precio'] * $descuento / 100);
        }

        $subtotal += ($precioUnitario * $cant);
    }

    // Aplicar cupón si existe
    $descuentoCupon = 0;
    if (isset($_SESSION['prontoFront']['cupon'])) {
        $porc = intval($_SESSION['prontoFront']['cupon']['valor']);
        $descuentoCupon = ($subtotal * $porc) / 100;
    }

    // Calcular total con el nuevo costo de envío
    $total = $subtotal + $costo - $descuentoCupon;
    $_SESSION['prontoFront']['valor'] = $total;

    $respuesta['success'] = true;
    $respuesta['subtotal'] = $subtotal;
    $respuesta['costo_envio'] = $costo;
    $respuesta['descuento'] = $descuentoCupon;
    $respuesta['total'] = $total;
} else {
    $respuesta['error'] = 'No se pudo recalcular el total';
}

echo json_encode($respuesta);
?>
