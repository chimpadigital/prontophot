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

$costoCalculado = floatval($_POST['costo']);
$fecha = $_POST['fecha'] ?? '';
$costo = $costoCalculado; // Por defecto, el costo es el calculado

// Recalcular el valor total del pedido
$subtotal = 0;
$puedeContinuar = false;

// Para carrito de productos
if (isset($_SESSION['pronto']['cart']) && isset($_SESSION['prontoFront']['monto'])) {
    include __DIR__.'/../conexion/conectar.inc.php';
    global $conectar;

    $cart = $_SESSION['pronto']['cart'];

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
    $puedeContinuar = true;
}
// Para revelado de fotos
elseif (isset($_SESSION['archivos'])) {
    include __DIR__.'/../conexion/conectar.inc.php';
    global $conectar;

    $tama = array();
    foreach ($_SESSION['archivos'] as $key => $impre) {
        $tam = $impre['tamano'];
        $c = (int)$impre['cantidad'];
        if(isset($tama[$tam])){
            $tama[$tam] = $tama[$tam] + $c;
        } else {
            $tama[$tam] = $c;
        }
    }

    foreach ($tama as $tam => $cant) {
        $query = "SELECT * FROM `impresiones` WHERE formato='$tam' AND desde<='$cant' AND hasta>='$cant' ORDER BY id DESC LIMIT 1";
        $calc = $conectar->query($query);
        if($row = $calc->fetch_assoc()) {
            $p = $row['precio'];
            $precio = ($p * $cant);
            $subtotal += $precio;
        }
    }
    $puedeContinuar = true;
}

if ($puedeContinuar) {

    // Aplicar cupón si existe
    $descuentoCupon = 0;
    if (isset($_SESSION['prontoFront']['cupon'])) {
        $porc = intval($_SESSION['prontoFront']['cupon']['valor']);
        $descuentoCupon = ($subtotal * $porc) / 100;
    }

    // Verificar si aplica envío gratis por total del carrito (para cualquier método de envío)
    if (isset($_SESSION['prontoFront']['envio']['metodo_envio_id'])) {
        $metodo_id = intval($_SESSION['prontoFront']['envio']['metodo_envio_id']);
        // Obtener valor_gratis del método de envío seleccionado
        $metodo_query = $conectar->query("SELECT valor_gratis FROM metodos_envio WHERE id='$metodo_id'");
        if ($metodo_row = $metodo_query->fetch_assoc()) {
            $valor_gratis = floatval($metodo_row['valor_gratis']);
            // Si el subtotal del carrito supera el umbral, envío gratis
            if ($valor_gratis > 0 && $subtotal >= $valor_gratis) {
                $costo = 0;
            }
        }
    }

    // Actualizar el costo en la sesión (puede ser 0 si aplica envío gratis)
    $_SESSION['prontoFront']['envio']['costo'] = $costo;

    if (!empty($fecha)) {
        $_SESSION['prontoFront']['envio']['epresis_fecha'] = $fecha;
    }

    // Calcular total con el costo de envío (gratis o no)
    $total = $subtotal + $costo - $descuentoCupon;
    $_SESSION['prontoFront']['valor'] = $total;

    $respuesta['success'] = true;
    $respuesta['subtotal'] = $subtotal;
    $respuesta['costo_envio'] = $costo;
    $respuesta['descuento'] = $descuentoCupon;
    $respuesta['total'] = $total;
    $respuesta['valor_gratis_aplicado'] = ($costo == 0 && $costoCalculado > 0); // Indica si se aplicó envío gratis
} else {
    $respuesta['error'] = 'No se pudo recalcular el total';
    $respuesta['success'] = false;
}

echo json_encode($respuesta);
?>
