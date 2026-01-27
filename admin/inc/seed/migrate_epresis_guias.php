<?php
/**
 * Script de migración para crear tabla de guías Epresis
 * Ejecutar una sola vez desde el navegador: admin/inc/seed/migrate_epresis_guias.php
 */

include '../../../conexion/conectar.inc.php';
global $conectar;

echo "<h2>Migración de Base de Datos - Epresis Guías</h2>";
echo "<hr>";

// 1. Crear tabla epresis_guias
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

echo "<h3>1. Creando tabla epresis_guias...</h3>";
if ($conectar->query($sql1)) {
    echo "<p style='color:green;'>✓ Tabla epresis_guias creada correctamente</p>";
} else {
    echo "<p style='color:red;'>✗ Error: " . $conectar->error . "</p>";
}

// 2. Verificar si columna metodo_envio_id existe en pedidos
$check_column = $conectar->query("SHOW COLUMNS FROM pedidos LIKE 'metodo_envio_id'");
if ($check_column->num_rows == 0) {
    echo "<h3>2. Agregando columna metodo_envio_id a tabla pedidos...</h3>";
    $sql2 = "ALTER TABLE `pedidos` ADD COLUMN `metodo_envio_id` int(11) DEFAULT NULL AFTER `entrega`";
    if ($conectar->query($sql2)) {
        echo "<p style='color:green;'>✓ Columna metodo_envio_id agregada correctamente</p>";
    } else {
        echo "<p style='color:red;'>✗ Error: " . $conectar->error . "</p>";
    }
} else {
    echo "<h3>2. Columna metodo_envio_id</h3>";
    echo "<p style='color:blue;'>ℹ Ya existe en la tabla pedidos</p>";
}

// 3. Verificar si columna valor_gratis existe en metodos_envio
$check_column2 = $conectar->query("SHOW COLUMNS FROM metodos_envio LIKE 'valor_gratis'");
if ($check_column2->num_rows == 0) {
    echo "<h3>3. Agregando columna valor_gratis a tabla metodos_envio...</h3>";
    $sql3 = "ALTER TABLE `metodos_envio` ADD COLUMN `valor_gratis` decimal(10,2) DEFAULT 0.00 COMMENT 'Monto máximo para envío gratis' AFTER `valor`";
    if ($conectar->query($sql3)) {
        echo "<p style='color:green;'>✓ Columna valor_gratis agregada correctamente</p>";

        // Actualizar valor_gratis para Epresis (ID 2) con valor por defecto
        $sql4 = "UPDATE metodos_envio SET valor_gratis = 1000.00 WHERE id = 2";
        if ($conectar->query($sql4)) {
            echo "<p style='color:green;'>✓ Valor por defecto para Epresis establecido en $1000.00</p>";
        }
    } else {
        echo "<p style='color:red;'>✗ Error: " . $conectar->error . "</p>";
    }
} else {
    echo "<h3>3. Columna valor_gratis</h3>";
    echo "<p style='color:blue;'>ℹ Ya existe en la tabla metodos_envio</p>";
}

echo "<hr>";
echo "<h3>✅ Migración completada</h3>";
echo "<p>Puedes volver al <a href='../../index.php'>panel de administración</a></p>";

$conectar->close();
?>
