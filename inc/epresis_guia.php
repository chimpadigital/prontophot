<?php
/**
 * Archivo para generar guías de envío con Epresis
 * Se llama automáticamente cuando se confirma un pago de Mercado Pago
 */

if(session_status() === PHP_SESSION_NONE) session_start();

include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;

/**
 * Genera una guía de envío en Epresis
 * @param int $pedido_id ID del pedido
 * @return object Respuesta con success, guia, importe, remito, etc.
 */
function generarGuiaEpresis($pedido_id) {
    global $conectar;

    $respuesta = new stdClass();
    $respuesta->success = false;

    // Obtener datos del pedido
    $pedido_query = $conectar->query("
        SELECT p.*, c.email as cliente_email, c.telefono as cliente_telefono, c.celular as cliente_celular
        FROM pedidos p
        LEFT JOIN clientes c ON p.id_cliente = c.id
        WHERE p.id = '$pedido_id'
    ");

    if (!$pedido_query || $pedido_query->num_rows === 0) {
        $respuesta->error = 'Pedido no encontrado';
        return $respuesta;
    }

    $pedido = $pedido_query->fetch_assoc();

    // Verificar que sea un envío con Epresis (metodo_envio_id = 2)
    if ($pedido['metodo_envio_id'] != 2) {
        $respuesta->error = 'El pedido no utiliza envío Epresis';
        return $respuesta;
    }

    // Verificar si ya existe una guía para este pedido
    $guia_existente = $conectar->query("SELECT * FROM epresis_guias WHERE pedido_id = '$pedido_id'");
    if ($guia_existente && $guia_existente->num_rows > 0) {
        $guia_data = $guia_existente->fetch_assoc();
        $respuesta->success = true;
        $respuesta->ya_existe = true;
        $respuesta->guia = $guia_data['codigo_guia'];
        $respuesta->importe = $guia_data['importe'];
        $respuesta->remito = $guia_data['remito'];
        $respuesta->sub_zona_destino = $guia_data['sub_zona_destino'];
        return $respuesta;
    }

    // Obtener productos del pedido con dimensiones
    $productos_query = $conectar->query("
        SELECT pd.cantidad, p.nombre, p.ancho, p.alto, p.profundidad, p.peso
        FROM pedidos_detalle pd
        LEFT JOIN productos p ON pd.id_producto = p.id
        WHERE pd.id_pedido = '$pedido_id'
    ");

    $productos = [];
    while ($prod = $productos_query->fetch_assoc()) {
        $productos[] = [
            'bultos' => intval($prod['cantidad']),
            'peso' => floatval($prod['peso'] ?? 0.5),
            'descripcion' => $prod['nombre'],
            'dimensiones' => [
                'alto' => floatval($prod['alto'] ?? 0.25),
                'largo' => floatval($prod['profundidad'] ?? 0.25),
                'profundidad' => floatval($prod['ancho'] ?? 0.25)
            ]
        ];
    }

    if (empty($productos)) {
        $respuesta->error = 'No se encontraron productos en el pedido';
        return $respuesta;
    }

    // Parsear dirección (separar calle y altura)
    $direccion_completa = $pedido['direccion'];
    $altura = '';
    $calle = $direccion_completa;

    // Intentar extraer el número de la dirección
    if (preg_match('/^(.+?)[\s,]+(\d+)/', $direccion_completa, $matches)) {
        $calle = trim($matches[1]);
        $altura = trim($matches[2]);
    }

    // Configuración de Epresis
    $api_token = "UWJ1W5ZZXchvkNifYzQH0kYBSK2JCHiy6UTOrE06wq4ooN8KLCVoT2rJcr0r";
    $sucursal = "1104190026680";
    $url = "https://epresis-desa.epsared.com.ar/api/v2/guias.json";

    // Preparar datos para la guía
    $guia_data = [
        'api_token' => $api_token,
        'codigo_sucursal' => $sucursal,
        'codigo_servicio' => 'ESTANDAR', // Ajustar según necesidad
        'internacional' => 0,
        'isInversa' => 0,
        'pago_en' => 'ORIGEN', // El pago ya se realizó
        'tipo_operacion' => 'ENTREGA',
        'is_urgente' => 0,
        'remito' => 'PED-' . $pedido_id,
        'observaciones' => $pedido['descripcion'] ?? '',
        'valor_declarado' => floatval($pedido['total']),
        // Datos del comprador
        'comprador' => [
            'destinatario' => $pedido['nombre'],
            'calle' => $calle,
            'altura' => $altura ?: 'S/N',
            'localidad' => $pedido['ciudad'],
            'provincia' => $pedido['provincia'],
            'cp' => $pedido['cp'],
            'email' => $pedido['cliente_email'] ?? $pedido['email'] ?? '',
            'celular' => $pedido['cliente_celular'] ?? $pedido['telefono'] ?? '',
        ]
    ];

    // Agregar productos
    foreach ($productos as $index => $producto) {
        $guia_data["productos[$index][bultos]"] = $producto['bultos'];
        $guia_data["productos[$index][peso]"] = $producto['peso'];
        $guia_data["productos[$index][descripcion]"] = $producto['descripcion'];
        $guia_data["productos[$index][dimensiones][alto]"] = $producto['dimensiones']['alto'];
        $guia_data["productos[$index][dimensiones][largo]"] = $producto['dimensiones']['largo'];
        $guia_data["productos[$index][dimensiones][profundidad]"] = $producto['dimensiones']['profundidad'];
    }

    // Flatten el array de comprador
    foreach ($guia_data['comprador'] as $key => $value) {
        $guia_data["comprador[$key]"] = $value;
    }
    unset($guia_data['comprador']);

    // Realizar petición con cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($guia_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Verificar errores de cURL
    if ($curlError) {
        $respuesta->error = 'Error de conexión: ' . $curlError;
        return $respuesta;
    }

    // Verificar código HTTP
    if ($httpCode !== 200 && $httpCode !== 201) {
        $respuesta->error = 'Error HTTP: ' . $httpCode . ' - ' . $response;
        return $respuesta;
    }

    // Decodificar respuesta
    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $respuesta->error = 'Error al decodificar respuesta: ' . json_last_error_msg();
        return $respuesta;
    }

    // Procesar respuesta exitosa
    if (isset($data['guia'])) {
        $codigo_guia = $data['guia'];
        $importe = $data['importe'] ?? 0;
        $remito = $data['remito'] ?? '';
        $sub_zona = $data['sub_zona_destino'] ?? '';
        $zona = $data['zona'] ?? '';

        // Guardar guía en la base de datos
        $sql = "INSERT INTO epresis_guias (
            pedido_id,
            codigo_guia,
            importe,
            remito,
            sub_zona_destino,
            zona,
            respuesta_json,
            fecha_creacion
        ) VALUES (
            '$pedido_id',
            '$codigo_guia',
            '$importe',
            '$remito',
            '$sub_zona',
            '$zona',
            '" . $conectar->real_escape_string(json_encode($data)) . "',
            NOW()
        )";

        if ($conectar->query($sql)) {
            $respuesta->success = true;
            $respuesta->guia = $codigo_guia;
            $respuesta->importe = $importe;
            $respuesta->remito = $remito;
            $respuesta->sub_zona_destino = $sub_zona;
            $respuesta->zona = $zona;
        } else {
            $respuesta->error = 'Error al guardar guía: ' . $conectar->error;
        }
    } else {
        $respuesta->error = $data['error'] ?? $data['mensaje'] ?? 'Error desconocido al generar guía';
        $respuesta->detalles = $data;
    }

    return $respuesta;
}

/**
 * Obtener información de una guía existente
 * @param int $pedido_id ID del pedido
 * @return object|null Datos de la guía o null si no existe
 */
function obtenerGuiaEpresis($pedido_id) {
    global $conectar;

    $query = $conectar->query("SELECT * FROM epresis_guias WHERE pedido_id = '$pedido_id'");

    if ($query && $query->num_rows > 0) {
        return $query->fetch_assoc();
    }

    return null;
}
?>
