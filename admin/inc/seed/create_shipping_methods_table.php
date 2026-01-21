<?php
include_once __DIR__ . '/../../conexion/conectar.inc.php';
global $conectar;

echo "<h3>Creando tabla 'shipping_methods'...</h3>";

// Verificar si la tabla ya existe
$checkTableQuery = "SHOW TABLES LIKE 'metodos_envio'";
$result = $conectar->query($checkTableQuery);

if ($result->num_rows > 0) {
    echo "⚠️ La tabla 'metodos_envio' ya existe en la base de datos.<br>";
} else {
    // Crear tabla shipping_methods
    $createTableQuery = "CREATE TABLE `metodos_envio` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `nombre` VARCHAR(255) NOT NULL,
      `valor` DECIMAL(10,2) NOT NULL,
      `cp` VARCHAR(10) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if ($conectar->query($createTableQuery)) {
        echo "✓ Tabla 'metodos_envio' creada exitosamente<br><br>";
        echo "<strong>Estructura de la tabla:</strong><br>";
        echo "- id: INT AUTO_INCREMENT PRIMARY KEY<br>";
        echo "- nombre: VARCHAR(255) (nombre del método de envío)<br>";
        echo "- valor: DECIMAL(10,2) (costo del envío)<br>";
        echo "- cp: VARCHAR(10) (código postal, nullable)<br>";

        // Insertar dato inicial
        $insertQuery = "INSERT INTO `metodos_envio` (`nombre`, `valor`, `cp`)
                       VALUES ('Casco Urbano La Plata', 250, NULL)";

        if ($conectar->query($insertQuery)) {
            echo "<br>✓ Dato inicial insertado: 'Casco Urbano La Plata' (valor: 250, cp: null)<br>";
        } else {
            echo "<br>✗ Error al insertar dato inicial: " . $conectar->error . "<br>";
        }

        // Insertar dato epresis
        $insertQuery = "INSERT INTO `metodos_envio` (`nombre`, `valor`, `cp`)
                       VALUES ('EPSA Correo', 0, NULL)";

        if ($conectar->query($insertQuery)) {
            echo "<br>✓ Dato inicial insertado: 'Casco Urbano La Plata' (valor: 250, cp: null)<br>";
        } else {
            echo "<br>✗ Error al insertar dato inicial: " . $conectar->error . "<br>";
        }
    } else {
        echo "✗ Error al crear la tabla 'shipping_methods': " . $conectar->error . "<br>";
    }
}

echo "<br><strong>Proceso completado.</strong>";
