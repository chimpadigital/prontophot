<?php
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;

echo "<h3>Creando tabla 'tax'...</h3>";

// Crear tabla tax
$createTableQuery = "CREATE TABLE IF NOT EXISTS `tax` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `coeficiente` DECIMAL(10,2) NOT NULL,
  `update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conectar->query($createTableQuery)) {
    echo "✓ Tabla 'tax' creada exitosamente<br><br>";
} else {
    echo "✗ Error al crear la tabla 'tax': " . $conectar->error . "<br><br>";
    exit;
}

// Insertar primer valor
echo "<h3>Insertando valor inicial...</h3>";

$insertQuery = "INSERT INTO `tax` (`id`, `coeficiente`, `update_at`)
                VALUES (1, 1.21, NOW())";

if ($conectar->query($insertQuery)) {
    echo "✓ Registro insertado exitosamente<br>";
    echo "- ID: 1<br>";
    echo "- Coeficiente: 1.21<br>";
    echo "- Update_at: " . date('Y-m-d H:i:s') . "<br>";
} else {
    echo "✗ Error al insertar el registro: " . $conectar->error . "<br>";
}

echo "<br><strong>Proceso completado.</strong>";
