<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Agregar columna color
$sql = "ALTER TABLE imagenes 
ADD COLUMN color VARCHAR(100) DEFAULT NULL COMMENT 'Color de la imagen del producto'";
if ($conectar->query($sql) === TRUE) {
    echo "Columna 'color' agregada exitosamente a la tabla imagenes.<br>";
} else {
    echo "Error al agregar columna 'color': " . $conectar->error . "<br>";
}

echo "<br>Actualización de tabla imagenes completada.";
?>
