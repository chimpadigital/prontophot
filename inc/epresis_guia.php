<?php
include('../conexion/conectar.inc.php');
global $conectar;

/**
 * Genera una guía de envío en Epresis
 * @param int $pedido_id ID del pedido
 * @param array $datos_personalizados Datos opcionales del modal (destinatario, calle, altura, etc.)
 * @return object Respuesta con success, guia, importe, remito, etc.
 */
function generarGuiaEpresis($pedido_id, $datos_personalizados = [])
{
    global $conectar;

    $respuesta = new stdClass();
    $respuesta->success = false;

    // Obtener datos del pedido
    $pedido_query = $conectar->query("
        SELECT p.*, c.email as cliente_email, c.telefono as cliente_telefono
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

    // Parsear dirección del pedido
    $direccion_completa = $pedido['direccion'];
    $altura_auto = '';
    $calle_auto = $direccion_completa;

    // Intentar extraer el número de la dirección automáticamente
    if (preg_match('/^(.+?)[\s,]+(\d+)/', $direccion_completa, $matches)) {
        $calle_auto = trim($matches[1]);
        $altura_auto = trim($matches[2]);
    }

    // Usar datos del modal si están disponibles, sino datos del pedido
    $calle = !empty($datos_personalizados['calle']) ? $datos_personalizados['calle'] : $calle_auto;
    $altura = !empty($datos_personalizados['altura']) ? $datos_personalizados['altura'] : ($altura_auto ? 0 : 0);
    $piso = !empty($datos_personalizados['piso']) ? $datos_personalizados['piso'] : '';
    $dpto = !empty($datos_personalizados['dpto']) ? $datos_personalizados['dpto'] : '';
    $celular = !empty($datos_personalizados['celular']) ? $datos_personalizados['celular'] : ($pedido['telefono'] ?? '');
    $observaciones = !empty($datos_personalizados['observaciones']) ? $datos_personalizados['observaciones'] : ($pedido['descripcion'] ?? '');

    // Campos opcionales del comprador
    $empresa = $datos_personalizados['empresa'] ?? '';
    $hora_desde = $datos_personalizados['hora_desde'] ?? '';
    $hora_hasta = $datos_personalizados['hora_hasta'] ?? '';
    $cuit = $datos_personalizados['cuit'] ?? '';
    $contenido = $datos_personalizados['contenido'] ?? '';
    $info_adicional_1 = $datos_personalizados['info_adicional_1'] ?? '';
    $info_adicional_2 = $datos_personalizados['info_adicional_2'] ?? '';

    // Datos autocompletados desde el pedido (NO editables en el modal)
    $destinatario = $pedido['nombre'];
    $localidad = $pedido['ciudad'];
    $provincia = $pedido['provincia'];
    $cp = $pedido['cp'];
    $email = $pedido['cliente_email'] ?? $pedido['email'] ?? '';

    // Configuración de envío
    $codigo_servicio = 187;
    $remito = 'PED-' . $pedido_id;
    $valor_declarado = floatval($pedido['total']);
    $is_urgente = isset($datos_personalizados['is_urgente']) ? intval($datos_personalizados['is_urgente']) : 0;
    $fragil = isset($datos_personalizados['fragil']) ? intval($datos_personalizados['fragil']) : 0;
    $valida_stock = isset($datos_personalizados['valida_stock']) ? intval($datos_personalizados['valida_stock']) : 0;

    // Tiempo estimado de entrega desde el pedido
    $tiempo_entrega = !empty($pedido['epresis_tiempo_entrega']) ? 'Llega el ' . $pedido['epresis_tiempo_entrega'] : '';

    // Campos opcionales del envío
    $guia_agente = $datos_personalizados['guia_agente'] ?? '';
    $precinto = $datos_personalizados['precinto'] ?? '';
    $codigo_ceco = $datos_personalizados['codigo_ceco'] ?? '';
    $contrareembolso = isset($datos_personalizados['contrareembolso']) ? floatval($datos_personalizados['contrareembolso']) : null;
    $cobro_efectivo = isset($datos_personalizados['cobro_efectivo']) ? floatval($datos_personalizados['cobro_efectivo']) : null;
    $cobro_cheque = isset($datos_personalizados['cobro_cheque']) ? floatval($datos_personalizados['cobro_cheque']) : null;
    $canal = $datos_personalizados['canal'] ?? '';
    $codigo_expreso = $datos_personalizados['codigo_expreso'] ?? '';

    // Configuración de Epresis
    $api_token = "UWJ1W5ZZXchvkNifYzQH0kYBSK2JCHiy6UTOrE06wq4ooN8KLCVoT2rJcr0r";
    $sucursal = "1104190026680";
    $url = "https://epresis-desa.epsared.com.ar/api/v2/guias.json";

    // Preparar datos para la guía
    $guia_data = [
        'api_token' => $api_token,
        'codigo_sucursal' => $sucursal,
        'codigo_servicio' => $codigo_servicio,
        "internacional" => false,
        'isInversa' => false,
        'pago_en' => 'ORIGEN',
        'tipo_operacion' => 'ENTREGA',
        'is_urgente' => $is_urgente,
        'remito' => $remito,
        'observaciones' => $observaciones,
        'valor_declarado' => $valor_declarado,
        // Datos del comprador
        'comprador' => [
            'destinatario' => $destinatario,
            'calle' => $calle,
            'altura' => $altura,
            'localidad' => $localidad,
            'provincia' => $provincia,
            'cp' => $cp,
            'email' => $email,
            'celular' => $celular,
        ]
    ];

    // Agregar campos opcionales del comprador solo si tienen valor
    if ($empresa) $guia_data['comprador']['empresa'] = $empresa;
    if ($piso) $guia_data['comprador']['piso'] = $piso;
    if ($dpto) $guia_data['comprador']['dpto'] = $dpto;
    if ($hora_desde) $guia_data['comprador']['hora_desde'] = $hora_desde;
    if ($hora_hasta) $guia_data['comprador']['hora_hasta'] = $hora_hasta;
    if ($cuit) $guia_data['comprador']['cuit'] = $cuit;
    if ($contenido) $guia_data['comprador']['contenido'] = $contenido;
    if ($info_adicional_1) $guia_data['comprador']['info_adicional_1'] = $info_adicional_1;
    if ($info_adicional_2) $guia_data['comprador']['info_adicional_2'] = $info_adicional_2;

    // Agregar campos opcionales del envío solo si tienen valor
    if ($tiempo_entrega) $guia_data['tiempo'] = $tiempo_entrega;
    if ($fragil) $guia_data['fragil'] = $fragil;
    if ($valida_stock) $guia_data['valida_stock'] = $valida_stock;
    if ($guia_agente) $guia_data['guia_agente'] = $guia_agente;
    if ($precinto) $guia_data['precinto'] = $precinto;
    if ($codigo_ceco) $guia_data['codigo_ceco'] = $codigo_ceco;
    if ($contrareembolso !== null) $guia_data['contrareembolso'] = $contrareembolso;
    if ($cobro_efectivo !== null) $guia_data['cobro_efectivo'] = $cobro_efectivo;
    if ($cobro_cheque !== null) $guia_data['cobro_cheque'] = $cobro_cheque;
    if ($canal) $guia_data['canal'] = $canal;
    if ($codigo_expreso) $guia_data['codigo_expreso'] = $codigo_expreso;

    // Agregar productos como array de objetos
    $guia_data['productos'] = [];
    foreach ($productos as $producto) {
        $guia_data['productos'][] = [
            'bultos' => $producto['bultos'],
            'peso' => $producto['peso'],
            'descripcion' => $producto['descripcion'],
            'dimensiones' => [
                'alto' => $producto['dimensiones']['alto'],
                'largo' => $producto['dimensiones']['largo'],
                'profundidad' => $producto['dimensiones']['profundidad']
            ]
        ];
    }

    // Enviar como JSON
    $jsonData = json_encode($guia_data);

    // Realizar petición con cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);

    $response = curl_exec($ch);
    $response = curl_exec($ch);
    $response = ltrim($response, "\xEF\xBB\xBF");
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Verificar errores de cURL
    if ($curlError) {
        $respuesta->error = 'Error de conexión: ' . $curlError;
        $respuesta->data = $guia_data;
        return $respuesta;
    }

    // Verificar código HTTP
    if ($httpCode !== 200 && $httpCode !== 201) {
        $respuesta->error = 'Error HTTP: ' . $httpCode . ' - ' . $response;
        $respuesta->data = $guia_data;
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
function obtenerGuiaEpresis($pedido_id)
{
    global $conectar;

    $query = $conectar->query("SELECT * FROM epresis_guias WHERE pedido_id = '$pedido_id'");

    if ($query && $query->num_rows > 0) {
        return $query->fetch_assoc();
    }

    return null;
}
