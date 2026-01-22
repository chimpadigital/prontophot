<?php
include_once __DIR__ . '/../../conexion/conectar.inc.php';
global $conectar;




// Agregar columna factura A 

$sql = "ALTER TABLE facturacion ADD COLUMN factura_a TINYINT DEFAULT NULL COMMENT 'si contiene factura A'";
if ($conectar->query($sql) === TRUE) {
    echo "Columna 'factura_a' agregada exitosamente a la tabla pedido.<br>";
} else {
    echo "Error al agregar columna 'factura_a': " . $conectar->error . "<br>";
}
// Agregar columna cuit 
$sql = "ALTER TABLE facturacion ADD COLUMN cuit VARCHAR(100) DEFAULT NULL COMMENT 'cuit del cliente'";
if ($conectar->query($sql) === TRUE) {
    echo "Columna 'cuit' agregada exitosamente a la tabla pedido.<br>";
} else {
    echo "Error al agregar columna 'cuit': " . $conectar->error . "<br>";
}
// Agregar columna razon social 

$sql = "ALTER TABLE facturacion ADD COLUMN razon_social VARCHAR(100) DEFAULT NULL COMMENT 'razon social del cliente'";
if ($conectar->query($sql) === TRUE) {
    echo "Columna 'razon_social' agregada exitosamente a la tabla pedido.<br>";
} else {
    echo "Error al agregar columna 'razon_social': " . $conectar->error . "<br>";
}
echo "<br>Actualización de tabla imagenes completada.";
