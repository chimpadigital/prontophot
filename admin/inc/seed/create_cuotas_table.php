<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

echo "<h3>Creando tabla 'cuotas'...</h3>";

// Verificar si la tabla ya existe
$checkTableQuery = "SHOW TABLES LIKE 'cuotas'";
$result = $conectar->query($checkTableQuery);

if ($result->num_rows > 0) {
    echo "⚠️ La tabla 'cuotas' ya existe en la base de datos.<br>";
} else {
    // Crear tabla cuotas
    $createTableQuery = "CREATE TABLE `cuotas` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `cantidad` INT NOT NULL,
      `precio` DECIMAL(10,2) NOT NULL,
      `interes` DECIMAL(5,2) NOT NULL DEFAULT 0,
      `sin_interes` TINYINT(1) NOT NULL DEFAULT 0,
      `producto_id` INT NOT NULL,
      PRIMARY KEY (`id`),
      INDEX `idx_producto_id` (`producto_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if ($conectar->query($createTableQuery)) {
        echo "✓ Tabla 'cuotas' creada exitosamente<br><br>";
        echo "<strong>Estructura de la tabla:</strong><br>";
        echo "- id: INT AUTO_INCREMENT PRIMARY KEY<br>";
        echo "- cantidad: INT (cantidad de cuotas)<br>";
        echo "- precio: DECIMAL(10,2) (precio por cuota)<br>";
        echo "- interes: DECIMAL(5,2) (porcentaje de interés, default 0)<br>";
        echo "- sin_interes: TINYINT(1) (1 = sin interés, 0 = con interés, default 0)<br>";
        echo "- producto_id: INT (ID del producto relacionado)<br>";
        echo "- INDEX: idx_producto_id (para búsquedas rápidas por producto)<br>";
    } else {
        echo "✗ Error al crear la tabla 'cuotas': " . $conectar->error . "<br>";
    }
}

echo "<br><strong>Proceso completado.</strong>";
