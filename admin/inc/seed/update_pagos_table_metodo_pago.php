<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Agregar columna metodo_pago a la tabla pagos
$sql = "ALTER TABLE pagos
ADD COLUMN metodo_pago VARCHAR(100) DEFAULT NULL COMMENT 'Método de pago utilizado (getnet, mercadopago, etc)'";

if ($conectar->query($sql) === TRUE) {
    echo "Columna 'metodo_pago' agregada exitosamente a la tabla pagos.<br>";
} else {
    echo "Error al agregar columna 'metodo_pago': " . $conectar->error . "<br>";
}

?>
