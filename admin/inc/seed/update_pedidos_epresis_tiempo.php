<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

try {
    // Agregar campo epresis_tiempo_entrega a la tabla pedidos
    $sql = "ALTER TABLE `pedidos`
    ADD COLUMN `epresis_tiempo_entrega` varchar(100) DEFAULT NULL COMMENT 'Tiempo estimado de entrega para envíos Epresis' AFTER `metodo_envio_id`";

    try {
        $conectar->query($sql);
        echo "Campo epresis_tiempo_entrega agregado a la tabla pedidos.<br>";
    } catch (Exception $e) {
        echo "Campo epresis_tiempo_entrega ya existe en la tabla pedidos o error: " . $e->getMessage() . "<br>";
    }

    echo "Migración completada.";

} catch (Exception $e) {
    echo "Error en la migración: " . $e->getMessage();
}
?>
