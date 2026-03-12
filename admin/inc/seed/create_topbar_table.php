<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

echo "<h3>Creando tabla 'topbar'...</h3>";

// Verificar si la tabla ya existe
$checkTableQuery = "SHOW TABLES LIKE 'topbar'";
$result = $conectar->query($checkTableQuery);

if ($result->num_rows > 0) {
    echo "⚠️ La tabla 'topbar' ya existe en la base de datos.<br>";
} else {
    // Crear tabla topbar
    $createTableQuery = "CREATE TABLE `topbar` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `texto` VARCHAR(255) NOT NULL,
      `link` VARCHAR(500) NULL,
      `activo` TINYINT(1) NOT NULL DEFAULT 1,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if ($conectar->query($createTableQuery)) {
        echo "✓ Tabla 'topbar' creada exitosamente<br><br>";

        // Insertar registro por defecto
        $insertDefaultQuery = "INSERT INTO `topbar` (`id`, `texto`, `link`, `activo`) VALUES (1, 'Bienvenido a Prontophot', '', 1)";

        if ($conectar->query($insertDefaultQuery)) {
            echo "✓ Registro por defecto insertado exitosamente<br><br>";
        } else {
            echo "⚠️ Error al insertar registro por defecto: " . $conectar->error . "<br><br>";
        }

        echo "<strong>Estructura de la tabla:</strong><br>";
        echo "- id: INT AUTO_INCREMENT PRIMARY KEY<br>";
        echo "- texto: VARCHAR(255) (texto a mostrar en el topbar)<br>";
        echo "- link: VARCHAR(500) (URL opcional para hacer clickeable el topbar)<br>";
        echo "- activo: TINYINT(1) (1 = activo, 0 = inactivo, default 1)<br>";
        echo "- created_at: TIMESTAMP (fecha de creación)<br>";
        echo "- updated_at: TIMESTAMP (fecha de última actualización)<br>";
    } else {
        echo "✗ Error al crear la tabla 'topbar': " . $conectar->error . "<br>";
    }
}

echo "<br><strong>Proceso completado.</strong>";
