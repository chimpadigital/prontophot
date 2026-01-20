<?php
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;

echo "<h3>Agregando columna 'descuento_final' a la tabla productos...</h3>";

// Verificar si la columna ya existe
$checkQuery = "SHOW COLUMNS FROM `productos` LIKE 'descuento_final'";
$result = $conectar->query($checkQuery);

if ($result->num_rows > 0) {
    echo "⚠️ La columna 'descuento_final' ya existe en la tabla productos.<br>";
} else {
    // Agregar la columna descuento_final después de la columna descuento
    $alterQuery = "ALTER TABLE `productos` ADD COLUMN `descuento_final` DECIMAL(5,2) NOT NULL DEFAULT 0 AFTER `descuento`";

    if ($conectar->query($alterQuery)) {
        echo "✓ Columna 'descuento_final' agregada exitosamente a la tabla productos<br>";
        echo "- Tipo: DECIMAL(5,2)<br>";
        echo "- Valor por defecto: 0<br>";
        echo "- Posición: después de 'descuento'<br>";
    } else {
        echo "✗ Error al agregar la columna: " . $conectar->error . "<br>";
    }
}

echo "<br><strong>Proceso completado.</strong>";
