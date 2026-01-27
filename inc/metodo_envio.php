<?php
session_start();
header('Content-Type: application/json');

include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;

// Verificar si cURL está disponible
if (!function_exists('curl_init')) {
    echo json_encode(['success' => false, 'error' => 'cURL no está disponible en este servidor']);
    exit;
}

// Verificar que se reciban los datos necesarios
if (!isset($_POST['cp_destino'])) {
    echo json_encode(['success' => false, 'error' => 'Código postal de destino es requerido']);
    exit;
}

$cp_destino = $_POST['cp_destino'];

// Obtener productos del carrito con sus dimensiones y peso
$productos_carrito = [];
if (isset($_SESSION['pronto']['cart'])) {
    $carro = $_SESSION['pronto']['cart'];
    foreach($carro as $id_producto => $datos){
        $res = $conectar->query("SELECT ancho, alto, profundidad, peso FROM productos WHERE id='$id_producto'");
        if($row = $res->fetch_assoc()){
            $productos_carrito[] = [
                'id' => $id_producto,
                'cantidad' => $datos['cantidad'],
                'ancho' => floatval($row['ancho'] ?? 0.25),
                'alto' => floatval($row['alto'] ?? 0.25),
                'profundidad' => floatval($row['profundidad'] ?? 0.25),
                'peso' => floatval($row['peso'] ?? 0.5)
            ];
        }
    }
}

if (empty($productos_carrito)) {
    echo json_encode(['success' => false, 'error' => 'No hay productos en el carrito']);
    exit;
}

// Configuración de Epresis
$api_token = "UWJ1W5ZZXchvkNifYzQH0kYBSK2JCHiy6UTOrE06wq4ooN8KLCVoT2rJcr0r";
$sucursal = "1104190026680";
$url = "https://epresis-desa.epsared.com.ar/api/v2/precio-servicio.json";

// Preparar los datos para la petición
$postFields = [
    'api_token' => $api_token,
    'sucursal' => $sucursal,
    'cp_destino' => $cp_destino
];

// Agregar productos
foreach ($productos_carrito as $index => $producto) {
    $postFields["productos[$index][bultos]"] = $producto['cantidad'];
    $postFields["productos[$index][peso]"] = $producto['peso'];
    $postFields["productos[$index][dimensiones][alto]"] = $producto['alto'];
    $postFields["productos[$index][dimensiones][largo]"] = $producto['profundidad'];
    $postFields["productos[$index][dimensiones][profundidad]"] = $producto['ancho'];
}

// Realizar petición con cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Verificar errores de cURL
if ($curlError) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión: ' . $curlError]);
    exit;
}

// Verificar código HTTP
if ($httpCode !== 200) {
    echo json_encode(['success' => false, 'error' => 'Error HTTP: ' . $httpCode]);
    exit;
}

// Decodificar respuesta
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'error' => 'Error al decodificar respuesta']);
    exit;
}

// Procesar respuesta de Epresis
if (isset($data[0]['precio'])) {
    $precio = $data[0]['precio'];

    if ($precio['status'] === 'ok') {
        $costoFlete = $precio['importe_total_flete'] ?? $precio['retiro_precio'] ?? 0;
        $fechaPactada = $precio['fecha_pactada'] ?? '-';

        echo json_encode([
            'success' => true,
            'costo' => $costoFlete,
            'fecha' => $fechaPactada,
            'data' => $precio
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => $precio['mensaje'] ?? 'Error al calcular envío'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Respuesta inválida del servidor']);
}
?>
