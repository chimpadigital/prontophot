<?php
if(session_status() === PHP_SESSION_NONE) session_start();
ini_set("log_errors", 1);
ini_set("error_log", "getnet.log");
include 'config.inc.php';
include 'funciones.inc.php';
global $conectar;

$respuesta = new stdClass();
$metodo = $_POST['metodo'];

// Calcular costo total del revelado
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

$taman = array();
foreach ($tama as $tam => $cant) {
    $query = "SELECT * FROM `impresiones` WHERE formato='$tam' AND desde<='$cant' AND hasta>='$cant' ORDER BY id DESC LIMIT 1";
    $calc = $conectar->query($query);
    $row = $calc->fetch_assoc();
    $p = $row['precio'];
    $idimp = $row['id'];
    $taman[$tam] = $idimp;
    $precio = ($p * $cant);
    $costototal = $costototal + $precio;
}

// Aplicar descuento si existe
if (isset($_SESSION['prontoFront']['cupon'])) {
    $desc = $_SESSION['prontoFront']['cupon']['valor'];
    $md = ($costototal * $desc) / 100;
} else {
    $md = 0;
}

// Incluir costo de envío
$costoEnvio = isset($_SESSION['prontoFront']['envio']['costo']) ? floatval($_SESSION['prontoFront']['envio']['costo']) : 0;
$monto = $costototal + $costoEnvio - $md;

// Crear pedido
$param = array();
$entrega = $_SESSION['prontoFront']['envio']['tipo'];
$param['entrega'] = $entrega;
$param['envio'] = $_SESSION['prontoFront']['envio']['envio'];
$param['costo'] = $_SESSION['prontoFront']['envio']['costo'];
$param['idcliente'] = $_SESSION['prontoFront']['idcliente'];
$param['nombre'] = $_SESSION['prontoFront']['envio']['nombre'].' '.$_SESSION['prontoFront']['envio']['apellido'];
$param['direccion'] = $_SESSION['prontoFront']['envio']['direccion'];
$param['cp'] = $_SESSION['prontoFront']['envio']['cp'];
$param['altura'] = $_SESSION['prontoFront']['envio']['altura'] ?? null;
$param['ciudad'] = $_SESSION['prontoFront']['envio']['ciudad'];
$param['provincia'] = $_SESSION['prontoFront']['envio']['provincia'];
$param['telefono'] = $_SESSION['prontoFront']['envio']['telefono'].' - '.$_SESSION['prontoFront']['envio']['celular'];
$param['dni'] = $_SESSION['prontoFront']['envio']['dni'];
$param['email'] = $_SESSION['prontoFront']['envio']['email'];
$param['valor'] = $monto;
$param['metodo'] = $metodo;
$param['desc'] = 'Impresion Imagenes';
$param['metodo_envio_id'] = $_SESSION['prontoFront']['envio']['metodo_envio_id'] ?? null;

// Agregar datos de facturación desde el POST
if(isset($_POST['facturacion'])){
    $param['facturacion'] = $_POST['facturacion'];
}

$pedido = crearPedido($param);

if($pedido->success){
    $idpedido = $pedido->pedido;
    $respuesta->success = true;

    // Guardar imágenes del revelado
    if (isset($_SESSION['archivos']) && !empty($_SESSION['archivos'])) {
        error_log("=== Llamando a pedidoImagenes() desde crear_pago_getnet_revelado.php ===");
        error_log("ID Pedido: " . $idpedido);
        error_log("Cantidad de archivos: " . count($_SESSION['archivos']));

        pedidoImagenes($idpedido, $_SESSION['archivos']);
    } else {
        error_log("ERROR: No hay archivos en SESSION['archivos']");
        $respuesta->error = "No se encontraron imágenes para procesar";
    }

    // === GETNET INTEGRATION ===

    // Paso 1: Autenticación OAuth2
    $auth_data = array(
        'scope' => 'oob',
        'grant_type' => 'client_credentials'
    );

    $auth_ch = curl_init(GETNET_AUTH_URL);
    curl_setopt($auth_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($auth_ch, CURLOPT_POST, true);
    curl_setopt($auth_ch, CURLOPT_POSTFIELDS, http_build_query($auth_data));
    curl_setopt($auth_ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Basic ' . base64_encode(GETNET_CLIENT_ID . ':' . GETNET_CLIENT_SECRET),
        'Content-Type: application/x-www-form-urlencoded'
    ));

    $auth_response = curl_exec($auth_ch);
    $auth_http_code = curl_getinfo($auth_ch, CURLINFO_HTTP_CODE);
    curl_close($auth_ch);

    if($auth_http_code != 200){
        error_log("GetNet Auth Error: " . $auth_response);
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

    $payment_data = array(
        'seller_id' => GETNET_SELLER_ID,
        'amount' => $amount_cents,
        'currency' => 'BRL', // GetNet usa BRL (Real Brasileño)
        'order' => array(
            'order_id' => strval($idpedido),
            'sales_tax' => 0,
            'product_type' => 'service'
        ),
        'customer' => array(
            'customer_id' => strval($_SESSION['prontoFront']['idcliente']),
            'first_name' => $facturacion['nombre'],
            'last_name' => $facturacion['apellido'],
            'name' => $facturacion['nombre'] . ' ' . $facturacion['apellido'],
            'email' => $facturacion['email'],
            'document_type' => 'CPF',
            'document_number' => preg_replace('/[^0-9]/', '', $facturacion['dni']),
            'phone_number' => preg_replace('/[^0-9]/', '', $facturacion['telefono']),
            'billing_address' => array(
                'street' => $facturacion['direccion'],
                'number' => strval($facturacion['altura']),
                'complement' => '',
                'district' => $facturacion['ciudad'],
                'city' => $facturacion['ciudad'],
                'state' => 'SP', // Adaptado para Brasil
                'country' => 'Brasil',
                'postal_code' => preg_replace('/[^0-9]/', '', $facturacion['cp'])
            )
        ),
        'callback_url' => URL_SITIO . 'inc/getnet_callback.php',
        'success_url' => URL_SITIO . 'revelado-paso4',
        'error_url' => URL_SITIO . 'revelado-paso4'
    );

    $payment_ch = curl_init(GETNET_CHECKOUT_URL);
    curl_setopt($payment_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($payment_ch, CURLOPT_POST, true);
    curl_setopt($payment_ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
    curl_setopt($payment_ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json',
        'seller_id: ' . GETNET_SELLER_ID
    ));

    $payment_response = curl_exec($payment_ch);
    $payment_http_code = curl_getinfo($payment_ch, CURLINFO_HTTP_CODE);
    curl_close($payment_ch);

    error_log("GetNet Payment Response: " . $payment_response);
    error_log("GetNet HTTP Code: " . $payment_http_code);

    if($payment_http_code != 200 && $payment_http_code != 201){
        error_log("GetNet Payment Error: " . $payment_response);
        $respuesta->success = false;
        $respuesta->error = "Error al crear el pago con GetNet";
        echo json_encode($respuesta);
        exit;
    }

    $payment_result = json_decode($payment_response);

    // Guardar checkout_id en la sesión para el callback
    $_SESSION['getnet_checkout_id'] = $payment_result->checkout_id;
    $_SESSION['getnet_pedido_id'] = $idpedido;

    $respuesta->checkout_id = $payment_result->checkout_id;
    $respuesta->pedido = $idpedido;

} else {
    $respuesta->success = false;
    error_log($pedido->error);
    $respuesta->error = $pedido->error;
}

echo json_encode($respuesta);
?>
