<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

try {
    // Agregar campo color a la tabla pedidos_detalle
    $sql = "ALTER TABLE `pedidos_detalle`
    ADD COLUMN `color` varchar(20) DEFAULT NULL COMMENT 'Color en hexadecimal del producto elegido' AFTER `cantidad`";

    try {
        $conectar->query($sql);
        echo "Campo color agregado a la tabla pedidos_detalle.<br>";
    } catch (Exception $e) {
        echo "Campo color ya existe en la tabla pedidos_detalle o error: " . $e->getMessage() . "<br>";
    }

    echo "Migración completada.";

} catch (Exception $e) {
    echo "Error en la migración: " . $e->getMessage();
}
?>
