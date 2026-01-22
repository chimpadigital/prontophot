-- Script de migración para datos de facturación
-- Fecha: 2026-01-22

-- Crear tabla facturacion
CREATE TABLE IF NOT EXISTS `facturacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `cp` varchar(20) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `cuit` varchar(20) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `factura_a` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- NOTA: Los siguientes comandos son opcionales y solo deben ejecutarse
-- después de verificar que la tabla facturacion funciona correctamente
-- y que todos los datos se están guardando en ella.

-- Migrar datos existentes de pedidos a facturacion (OPCIONAL - ejecutar manualmente si es necesario)
-- INSERT INTO facturacion (pedido_id, nombre, apellido, dni, email, direccion, provincia, ciudad, cp, telefono, cuit, razon_social, factura_a)
-- SELECT id, nombre, '', dni, '', direccion, provincia, ciudad, cp, telefono, cuit, razon_social, factura_a
-- FROM pedidos
-- WHERE nombre IS NOT NULL;

-- Una vez que se haya verificado que todo funciona correctamente,
-- se pueden eliminar las columnas de la tabla pedidos (OPCIONAL - NO EJECUTAR AHORA):
-- ALTER TABLE pedidos DROP COLUMN cuit;
-- ALTER TABLE pedidos DROP COLUMN razon_social;
-- ALTER TABLE pedidos DROP COLUMN factura_a;
