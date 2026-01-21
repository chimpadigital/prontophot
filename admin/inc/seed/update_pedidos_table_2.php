<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Agregar columna factura A
$sql = "ALTER TABLE pedidos 
ADD COLUMN metodo_envio_id TINYINT DEFAULT NULL COMMENT 'si contiene metodo_envio_id'";
if ($conectar->query($sql) === TRUE) {
    echo "Columna 'metodo_envio_id' agregada exitosamente a la tabla pedido.<br>";
} else {
    echo "Error al agregar columna 'metodo_envio_id': " . $conectar->error . "<br>";
}

?>