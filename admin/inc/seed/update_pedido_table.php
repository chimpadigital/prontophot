<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;


// Eliminar columna factura_a
$sql = "ALTER TABLE pedidos DROP COLUMN factura_a";
if ($conectar->query($sql) === TRUE) {
    echo "Columna 'factura_a' eliminada correctamente.<br>";
} else {
    echo "Error al eliminar 'factura_a': " . $conectar->error . "<br>";
}

// Eliminar columna cuit
$sql = "ALTER TABLE pedidos DROP COLUMN cuit";
if ($conectar->query($sql) === TRUE) {
    echo "Columna 'cuit' eliminada correctamente.<br>";
} else {
    echo "Error al eliminar 'cuit': " . $conectar->error . "<br>";
}

// Eliminar columna razon_social
$sql = "ALTER TABLE pedidos DROP COLUMN razon_social";
if ($conectar->query($sql) === TRUE) {
    echo "Columna 'razon_social' eliminada correctamente.<br>";
} else {
    echo "Error al eliminar 'razon_social': " . $conectar->error . "<br>";
}

echo "<br>Actualización de tabla pedidos completada.";
?>