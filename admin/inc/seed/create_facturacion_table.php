<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Crear tabla facturacion
$sql = "CREATE TABLE IF NOT EXISTS facturacion (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT(11) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    provincia VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    cp VARCHAR(10) NOT NULL,
    telefono VARCHAR(50) DEFAULT NULL,
    celular VARCHAR(50) DEFAULT NULL,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conectar->query($sql) === TRUE) {
    echo "Tabla 'facturacion' creada exitosamente.<br>";
    echo "<br>Estructura de la tabla:<br>";
    echo "- id: Identificador único de la facturación<br>";
    echo "- pedido_id: ID del pedido asociado (clave foránea)<br>";
    echo "- nombre: Nombre del cliente para facturación<br>";
    echo "- apellido: Apellido del cliente para facturación<br>";
    echo "- dni: DNI del cliente para facturación<br>";
    echo "- email: Email del cliente para facturación<br>";
    echo "- direccion: Dirección del cliente para facturación<br>";
    echo "- provincia: Provincia del cliente para facturación<br>";
    echo "- ciudad: Ciudad del cliente para facturación<br>";
    echo "- cp: Código postal del cliente para facturación<br>";
    echo "- telefono: Teléfono del cliente para facturación<br>";
    echo "- celular: Celular del cliente para facturación<br>";
    echo "- creado: Fecha de creación del registro<br>";
    echo "<br><strong>NOTA:</strong> Los campos factura_a, cuit y razon_social se guardan en la tabla pedidos.<br>";
} else {
    echo "Error al crear tabla 'facturacion': " . $conectar->error . "<br>";
}

echo "<br>Creación de tabla facturacion completada.";
?>
