<?php
include __DIR__ . '/../conexion/conectar.inc.php';
global $conectar;

// Array con los datos a insertar
$impresiones = [
    // ===== FILA 1 (1 a 4) =====
    ['format' => 'polaroid', 'fila' => 1, 'desde' => 1,  'hasta' => 4,   'precio' => 10],
    ['format' => '10x15',    'fila' => 1, 'desde' => 1,  'hasta' => 4,   'precio' => 10],
    ['format' => '13x18',    'fila' => 1, 'desde' => 1,  'hasta' => 4,   'precio' => 10],
    ['format' => '15x20',    'fila' => 1, 'desde' => 1,  'hasta' => 4,   'precio' => 10],
    ['format' => '20x30',    'fila' => 1, 'desde' => 1,  'hasta' => 4,   'precio' => 10],
    ['format' => '25x38',    'fila' => 1, 'desde' => 1,  'hasta' => 4,   'precio' => 10],

    // ===== FILA 2 (5 a 10) =====
    ['format' => 'polaroid', 'fila' => 2, 'desde' => 5,  'hasta' => 10,  'precio' => 7899],
    ['format' => '10x15',    'fila' => 2, 'desde' => 5,  'hasta' => 10,  'precio' => 7899],
    ['format' => '13x18',    'fila' => 2, 'desde' => 5,  'hasta' => 10,  'precio' => 8399],
    ['format' => '15x20',    'fila' => 2, 'desde' => 5,  'hasta' => 10,  'precio' => 14990],
    ['format' => '20x30',    'fila' => 2, 'desde' => 5,  'hasta' => 10,  'precio' => 3699],
    ['format' => '25x38',    'fila' => 2, 'desde' => 5,  'hasta' => 10,  'precio' => 4469],

    // ===== FILA 3 (11 a 24) =====
    ['format' => 'polaroid', 'fila' => 3, 'desde' => 11, 'hasta' => 24,  'precio' => 4300],
    ['format' => '10x15',    'fila' => 3, 'desde' => 11, 'hasta' => 24,  'precio' => 4300],
    ['format' => '13x18',    'fila' => 3, 'desde' => 11, 'hasta' => 24,  'precio' => 4855],
    ['format' => '15x20',    'fila' => 3, 'desde' => 11, 'hasta' => 24,  'precio' => 6846],
    ['format' => '20x30',    'fila' => 3, 'desde' => 11, 'hasta' => 24,  'precio' => 2399],
    ['format' => '25x38',    'fila' => 3, 'desde' => 11, 'hasta' => 24,  'precio' => 3899],

    // ===== FILA 4 (25 a 49) =====
    ['format' => 'polaroid', 'fila' => 4, 'desde' => 25, 'hasta' => 49,  'precio' => 3350],
    ['format' => '10x15',    'fila' => 4, 'desde' => 25, 'hasta' => 49,  'precio' => 3350],
    ['format' => '13x18',    'fila' => 4, 'desde' => 25, 'hasta' => 49,  'precio' => 3950],
    ['format' => '15x20',    'fila' => 4, 'desde' => 25, 'hasta' => 49,  'precio' => 6645],
    ['format' => '20x30',    'fila' => 4, 'desde' => 25, 'hasta' => 49,  'precio' => 2469],
    ['format' => '25x38',    'fila' => 4, 'desde' => 25, 'hasta' => 49,  'precio' => 3169],

    // ===== FILA 5 (50 a 99) =====
    ['format' => 'polaroid', 'fila' => 5, 'desde' => 50, 'hasta' => 99,  'precio' => 2698],
    ['format' => '10x15',    'fila' => 5, 'desde' => 50, 'hasta' => 99,  'precio' => 2698],
    ['format' => '13x18',    'fila' => 5, 'desde' => 50, 'hasta' => 99,  'precio' => 2910],
    ['format' => '15x20',    'fila' => 5, 'desde' => 50, 'hasta' => 99,  'precio' => 4990],
    ['format' => '20x30',    'fila' => 5, 'desde' => 50, 'hasta' => 99,  'precio' => 2399],
    ['format' => '25x38',    'fila' => 5, 'desde' => 50, 'hasta' => 99,  'precio' => 3079],

    // ===== FILA 6 (100 a 500) =====
    ['format' => 'polaroid', 'fila' => 6, 'desde' => 100, 'hasta' => 500, 'precio' => 2055],
    ['format' => '10x15',    'fila' => 6, 'desde' => 100, 'hasta' => 500, 'precio' => 2055],
    ['format' => '13x18',    'fila' => 6, 'desde' => 100, 'hasta' => 500, 'precio' => 2499],
    ['format' => '15x20',    'fila' => 6, 'desde' => 100, 'hasta' => 500, 'precio' => 4799],
    ['format' => '20x30',    'fila' => 6, 'desde' => 100, 'hasta' => 500, 'precio' => 2269],
    ['format' => '25x38',    'fila' => 6, 'desde' => 100, 'hasta' => 500, 'precio' => 2990],
];

$insertados = 0;
$errores = [];

foreach ($impresiones as $impresion) {
    $format = $conectar->real_escape_string($impresion['format']);
    $fila = (int)$impresion['fila'];
    $desde = (int)$impresion['desde'];
    $hasta = (int)$impresion['hasta'];
    $precio = (float)$impresion['precio'];

    $fecha = date('Y-m-d H:i:s');

    $query = "INSERT INTO `impresiones` (`formato`, `fila`, `desde`, `hasta`, `precio`, `fecha`)
              VALUES ('$format', $fila, $desde, $hasta, $precio,  '$fecha')";

    if ($conectar->query($query)) {
        $insertados++;
        echo "✓ Insertado: $format (fila $fila, desde $desde hasta $hasta, precio $precio)<br>";
    } else {
        $errores[] = "Error al insertar $format: " . $conectar->error;
        echo "✗ Error al insertar $format: " . $conectar->error . "<br>";
    }
}

echo "<br><strong>Resumen:</strong><br>";
echo "Total insertados: $insertados de " . count($impresiones) . "<br>";

if (count($errores) > 0) {
    echo "<br><strong>Errores:</strong><br>";
    foreach ($errores as $error) {
        echo "- $error<br>";
    }
}
