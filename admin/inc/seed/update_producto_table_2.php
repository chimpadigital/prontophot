<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Agregar columna descuento_final
$sql1 = "ALTER TABLE productos 
ADD COLUMN peso DECIMAL(10,2) DEFAULT 0 COMMENT 'Peso del producto'";
if ($conectar->query($sql1) === TRUE) {
    echo "Columna 'peso' agregada exitosamente a la tabla productos.<br>";
} else {
    echo "Error al agregar columna 'peso': " . $conectar->error . "<br>";
}

?>