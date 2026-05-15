<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ini_set("log_errors", 1);
ini_set("error_log", "getnet.log");
include 'config.inc.php';
include 'funciones.inc.php';
global $conectar;

$respuesta = new stdClass();
$metodo = $_POST['metodo'];
$desc = $_POST['desc'];
$desc = preg_replace("<br>", "\r\n", $desc);

$carro = $_SESSION['pronto']['cart'];
$total = 0;
$descuento = 0;
$porc = 0;

foreach ($carro as $id => $datos) {
    $cant = $datos['cantidad'];
    $cat = $datos['cat'];

    // Verificar si es un producto de revelado (precio ya es total)
    if (isset($datos['tipo']) && $datos['tipo'] === 'revelado') {
        $precio = floatval($datos['precio']);
    } elseif (isset($datos['precio']) && $datos['precio'] > 0) {
        // Precio del carrito (precio unitario)
        $precioUnitario = floatval($datos['precio']);
        $precio = ($cant * $precioUnitario);
    } else {
        // Obtener precio y descuento del producto
        $id_producto = isset($datos['id_original']) ? $datos['id_original'] : $id;
        $prod_res = $conectar->query("SELECT precio, descuento_final FROM productos WHERE id='$id_producto'");
        if ($prod_row = $prod_res->fetch_assoc()) {
            $precioUnitario = floatval($prod_row['precio']);
            $descuentoProducto = isset($prod_row['descuento_final']) && $prod_row['descuento_final'] > 0 ? floatval($prod_row['descuento_final']) : 0;

            // Aplicar descuento del producto si existe
            if ($descuentoProducto > 0) {
                $precioUnitario = $precioUnitario - ($precioUnitario * $descuentoProducto / 100);
            }
            $precio = ($cant * $precioUnitario);
        } else {
            $precio = 0;
        }
    }

    if (isset($_SESSION['prontoFront']['cupon'])) {
        if ($_SESSION['prontoFront']['cupon']['categoria'] == $cat) {
            $porc = intval($_SESSION['prontoFront']['cupon']['valor']);
            $descuento = ($precio * $porc) / 100;
        }
    }
    $total = $total + $precio;
}

unset($_SESSION['prontoFront']['cupon']);

// Incluir costo de envío en el total
$costoEnvio = isset($_SESSION['prontoFront']['envio']['costo']) ? floatval($_SESSION['prontoFront']['envio']['costo']) : 0;
$monto = $total - $descuento + $costoEnvio;

// Crear pedido
$param = array();
$entrega = $_SESSION['prontoFront']['envio']['tipo'];
$param['entrega'] = $entrega;
$param['envio'] = $_SESSION['prontoFront']['envio']['envio'];
$param['costo'] = $_SESSION['prontoFront']['envio']['costo'];
$param['idcliente'] = $_SESSION['prontoFront']['idcliente'];
$param['nombre'] = $_SESSION['prontoFront']['envio']['nombre'] . ' ' . $_SESSION['prontoFront']['envio']['apellido'];
$param['direccion'] = $_SESSION['prontoFront']['envio']['direccion'];
$param['cp'] = $_SESSION['prontoFront']['envio']['cp'];
$param['altura'] = $_SESSION['prontoFront']['envio']['altura'] ?? null;
$param['ciudad'] = $_SESSION['prontoFront']['envio']['ciudad'];
$param['provincia'] = $_SESSION['prontoFront']['envio']['provincia'];
$param['telefono'] = $_SESSION['prontoFront']['envio']['telefono'] . ' - ' . $_SESSION['prontoFront']['envio']['celular'];
$param['dni'] = $_SESSION['prontoFront']['envio']['dni'];
$param['email'] = $_SESSION['prontoFront']['envio']['email'];
$param['valor'] = $monto;
$param['metodo'] = $metodo;
$param['desc'] = $desc;
$param['metodo_envio_id'] = $_SESSION['prontoFront']['envio']['metodo_envio_id'] ?? null;

// Agregar datos de facturación desde el POST
if (isset($_POST['facturacion'])) {
    $param['facturacion'] = $_POST['facturacion'];
}

$pedido = crearPedido($param);

if ($pedido->success) {
    $idpedido = $pedido->pedido;
    $respuesta->success = true;

    // Guardar detalles del pedido
    $carro = $_SESSION['pronto']['cart'];
    $items = array();

    foreach ($carro as $id => $datos) {
        $cant = $datos['cantidad'];

        // Verificar si es un producto de revelado
        if (isset($datos['tipo']) && $datos['tipo'] === 'revelado') {
            $precio = floatval($datos['precio']);
            $nombreProducto = isset($datos['descripcion']) ? $datos['descripcion'] : 'Revelado de fotos';
        } elseif (isset($datos['precio']) && $datos['precio'] > 0) {
            $precioUnitario = floatval($datos['precio']);
            $precio = ($cant * $precioUnitario);
            $nombreProducto = isset($datos['nombre']) ? $datos['nombre'] : 'Producto';
        } else {
            $id_producto = isset($datos['id_original']) ? $datos['id_original'] : $id;
            $res = $conectar->query("SELECT p.nombre,p.descripcion, p.precio, p.descuento_final,(SELECT imagen FROM `imagenes` WHERE id_producto='$id_producto' ORDER BY id ASC LIMIT 1) as imagen FROM productos p  WHERE p.id='$id_producto' ");
            if ($row = $res->fetch_assoc()) {
                // Aplicar descuento del producto si existe
                $precioUnitario = floatval($row['precio']);
                $descuentoProducto = isset($row['descuento_final']) && $row['descuento_final'] > 0 ? floatval($row['descuento_final']) : 0;
                if ($descuentoProducto > 0) {
                    $precioUnitario = $precioUnitario - ($precioUnitario * $descuentoProducto / 100);
                }
                $precio = ($cant * $precioUnitario);
                $nombreProducto = $row['nombre'];
            } else {
                $precio = 0;
                $nombreProducto = 'Producto';
            }
        }

        $color = isset($datos['color']) ? $conectar->real_escape_string($datos['color']) : NULL;
        $item['producto'] = $nombreProducto . ' ' . $datos['color'];
        $item['cantidad'] = $cant;
        $item['precio'] = $precio;

        // Guardar producto con color
        $id_producto_db = isset($datos['id_original']) ? $datos['id_original'] : $id;
        if ($color !== NULL) {
            $conectar->query("INSERT INTO `pedidos_detalle`( `id_pedido`, `id_producto`, `cantidad`, `color`) VALUES ('$idpedido','$id_producto_db','$cant','$color')");
        } else {
            $conectar->query("INSERT INTO `pedidos_detalle`( `id_pedido`, `id_producto`, `cantidad`) VALUES ('$idpedido','$id_producto_db','$cant')");
        }
        $items[] = $item;
    }

    if (isset($_SESSION['archivos'])) {
        $imagenes = $_SESSION['archivos'];
        pedidoImagenes($idpedido, $imagenes);
    }
    
    //notificarPedido($idpedido, $items, $entrega);
    // === GETNET INTEGRATION ===

    // Paso 1: Autenticación OAuth2
    $auth_data = array(
        'grant_type' => 'client_credentials',
        'client_id' => GETNET_CLIENT_ID,
        'client_secret' => GETNET_CLIENT_SECRET
    );

    $auth_ch = curl_init("https://api.globalgetnet.com/authentication/oauth2/access_token");
    curl_setopt($auth_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($auth_ch, CURLOPT_POST, true);
    curl_setopt($auth_ch, CURLOPT_POSTFIELDS, http_build_query($auth_data));
    curl_setopt($auth_ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json'
    ));

    $auth_response = curl_exec($auth_ch);
    $auth_http_code = curl_getinfo($auth_ch, CURLINFO_HTTP_CODE);
    curl_close($auth_ch);

    if ($auth_http_code != 200) {
        error_log("GetNet Auth Error: " . json_encode($auth_response));
        $respuesta->success = false;
        $respuesta->error = "Error de autenticación con GetNet";
        echo json_encode($respuesta);
        exit;
    }

    $auth_result = json_decode($auth_response);
    $access_token = $auth_result->access_token;



    // Paso 2: Crear payment intent con GetNet
    $facturacion = $_POST['facturacion'];

    // Convertir monto a centavos (GetNet usa centavos)
    $amount_cents = intval($monto * 100);

    // Construir array de productos para GetNet
    $products_array = array();
    foreach ($carro as $id => $datos) {
        $cant = $datos['cantidad'];

        // Verificar si es un producto de revelado
        if (isset($datos['tipo']) && $datos['tipo'] === 'revelado') {
            // Para revelado, el precio ya es el total, dividir por cantidad para obtener unitario
            $precioTotal = floatval($datos['precio']);
            $precioUnitario = $cant > 0 ? ($precioTotal / $cant) : $precioTotal;
            $nombreProducto = isset($datos['descripcion']) ? $datos['descripcion'] : 'Revelado de fotos';
            $descripcionProducto = $nombreProducto;
        } elseif (isset($datos['precio']) && $datos['precio'] > 0) {
            $precioUnitario = floatval($datos['precio']);
            $nombreProducto = isset($datos['nombre']) ? $datos['nombre'] : 'Producto';
            $descripcionProducto = $nombreProducto;
        } else {
            $id_producto = isset($datos['id_original']) ? $datos['id_original'] : $id;
            $res = $conectar->query("SELECT p.nombre, p.descripcion, p.precio, p.descuento_final FROM productos p WHERE p.id='$id_producto'");
            if ($row = $res->fetch_assoc()) {
                // Aplicar descuento del producto si existe
                $precioUnitario = floatval($row['precio']);
                $descuentoProducto = isset($row['descuento_final']) && $row['descuento_final'] > 0 ? floatval($row['descuento_final']) : 0;
                if ($descuentoProducto > 0) {
                    $precioUnitario = $precioUnitario - ($precioUnitario * $descuentoProducto / 100);
                }
                $nombreProducto = $row['nombre'];
                $descripcionProducto = $row['descripcion'] ? $row['descripcion'] : $row['nombre'];
            } else {
                $precioUnitario = 0;
                $nombreProducto = 'Producto';
                $descripcionProducto = 'Producto';
            }
        }

        // Convertir precio unitario a centavos
        $precio_centavos = intval($precioUnitario * 100);

        $products_array[] = array(
            'product_type' => 'physical_goods',
            'title' => $nombreProducto,
            'description' => $descripcionProducto,
            'value' => $precio_centavos,
            'quantity' => intval($cant)
        );
    }

    // Si hay costo de envío, agregarlo como un producto adicional
    if ($costoEnvio > 0) {
        $products_array[] = array(
            'product_type' => 'service',
            'title' => 'Costo de Envío',
            'description' => 'Envío: ' . $entrega,
            'value' => intval($costoEnvio * 100),
            'quantity' => 1
        );
    }

    error_log("GetNet Products Array: " . json_encode($products_array));
    error_log("GetNet Total Amount (cents): " . $amount_cents);
    error_log("GetNet DNI: " . preg_replace('/[^0-9]/', '', $facturacion['dni']));

    $payment_data = array(
        "mode" => "instant",
        'order_id' => strval($idpedido),
        'payment' => [
            "currency" => 'ARS',
            "amount" => $amount_cents
        ],
        'product' => $products_array,
        'customer' => array(
            'customer_id' => strval($_SESSION['prontoFront']['idcliente']),
            'first_name' => $facturacion['nombre'],
            'last_name' => $facturacion['apellido'],
            'name' => $facturacion['nombre'] . ' ' . $facturacion['apellido'],
            'email' => $facturacion['email'],
            'document_type' => 'dni',
            'document_number' => preg_replace('/[^0-9]/', '', $facturacion['dni']),
            'phone_number' => preg_replace('/[^0-9]/', '', $facturacion['telefono']),
            "gender" => "Male",
            "checked_email" => false,
            'billing_address' => array(
                'street' => $facturacion['direccion'],
                'number' => strval($facturacion['altura']),
                'complement' => 'N/A',
                'district' => $facturacion['ciudad'],
                'city' => $facturacion['ciudad'],
                'state' => $facturacion['ciudad'], 
                'country' => 'AR',
                'postal_code' => preg_replace('/[^0-9]/', '', $facturacion['cp'])
            )
        ),
    );

    $payment_ch = curl_init(GETNET_API . "/digital-checkout/v1/payment-intent");
    curl_setopt($payment_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($payment_ch, CURLOPT_POST, true);
    curl_setopt($payment_ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
    curl_setopt($payment_ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ));

    $payment_response = curl_exec($payment_ch);
    $payment_http_code = curl_getinfo($payment_ch, CURLINFO_HTTP_CODE);
    curl_close($payment_ch);

    error_log("GetNet Payment Response: " . $payment_response);
    error_log("GetNet HTTP Code: " . $payment_http_code);

    if ($payment_http_code != 200 && $payment_http_code != 201) {
        error_log("GetNet Payment Error: " . $payment_response);
        $respuesta->success = false;
        $respuesta->error = "Error al crear el pago con GetNet";
        echo json_encode($respuesta);
        exit;
    }

    $payment_result = json_decode($payment_response);

    // Guardar checkout_id en la sesión para el callback
    $_SESSION['getnet_pedido_id'] = $idpedido;

    $respuesta->payment_intent_id = $payment_result->payment_intent_id;
    $respuesta->redirect_url = $payment_result->redirect_url;
    $respuesta->pedido = $idpedido;
    $respuesta->getnet = $payment_result;
} else {
    $respuesta->success = false;
    error_log($pedido->error);
    $respuesta->error = $pedido->error;
}

echo json_encode($respuesta);
