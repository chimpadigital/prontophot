<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

try {
    // Tabla para almacenar guías de envío de Epresis
    $sql1 = "CREATE TABLE IF NOT EXISTS `epresis_guias` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `pedido_id` int(11) NOT NULL,
      `codigo_guia` varchar(50) NOT NULL,
      `importe` decimal(10,2) DEFAULT 0.00,
      `remito` varchar(100) DEFAULT NULL,
      `sub_zona_destino` varchar(100) DEFAULT NULL,
      `zona` varchar(100) DEFAULT NULL,
      `respuesta_json` text DEFAULT NULL,
      `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
      `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `pedido_id` (`pedido_id`),
      KEY `codigo_guia` (`codigo_guia`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conectar->query($sql1);
    echo "Tabla epresis_guias creada correctamente.<br>";

} catch (Exception $e) {
    echo "Error en la migración: " . $e->getMessage();
}
