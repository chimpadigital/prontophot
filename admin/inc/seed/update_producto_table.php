<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Agregar columna descuento_final
$sql1 = "ALTER TABLE productos 
ADD COLUMN descuento_final DECIMAL(10,2) DEFAULT 0 COMMENT 'Porcentaje de descuento final'";
if ($conectar->query($sql1) === TRUE) {
    echo "Columna 'descuento_final' agregada exitosamente a la tabla productos.<br>";
} else {
    echo "Error al agregar columna 'descuento_final': " . $conectar->error . "<br>";
}

// Agregar columna video
$sql2 = "ALTER TABLE productos ADD COLUMN video VARCHAR(200) DEFAULT NULL COMMENT 'URL del video del producto'";
if ($conectar->query($sql2) === TRUE) {
    echo "Columna 'video' agregada exitosamente a la tabla productos.<br>";
} else {
    echo "Error al agregar columna 'video': " . $conectar->error . "<br>";
}

echo "<br>Actualización de tabla productos completada.";
?>
