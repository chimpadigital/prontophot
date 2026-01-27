<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Agregar columna descuento_final
$sql1 = "ALTER TABLE metodos_envio 
ADD COLUMN valor_gratis DECIMAL(10,2) DEFAULT 0 COMMENT 'Maximo valor de envio gratis'";
if ($conectar->query($sql1) === TRUE) {
    echo "Columna 'valor_gratis' agregada exitosamente a la tabla metodos de envio.<br>";
} else {
    echo "Error al agregar columna 'valor_gratis': " . $conectar->error . "<br>";
}

?>