<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Agregar columna factura_a
$sql1 = "ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS factura_a TINYINT(1) DEFAULT 0 COMMENT 'Indica si requiere Factura A'";
if ($conectar->query($sql1) === TRUE) {
    echo "Columna 'factura_a' agregada exitosamente a la tabla pedidos.<br>";
} else {
    echo "Error al agregar columna 'factura_a': " . $conectar->error . "<br>";
}

// Agregar columna cuit
$sql2 = "ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS cuit VARCHAR(20) DEFAULT NULL COMMENT 'CUIT para Factura A'";
if ($conectar->query($sql2) === TRUE) {
    echo "Columna 'cuit' agregada exitosamente a la tabla pedidos.<br>";
} else {
    echo "Error al agregar columna 'cuit': " . $conectar->error . "<br>";
}

// Agregar columna razon_social
$sql3 = "ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS razon_social VARCHAR(200) DEFAULT NULL COMMENT 'Razón Social para Factura A'";
if ($conectar->query($sql3) === TRUE) {
    echo "Columna 'razon_social' agregada exitosamente a la tabla pedidos.<br>";
} else {
    echo "Error al agregar columna 'razon_social': " . $conectar->error . "<br>";
}

echo "<br>Actualización de tabla pedidos completada.";
?>
