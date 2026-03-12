<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

echo "<h3>Agregando campo tipo_producto y producto especial de revelado...</h3>";

// 1. Verificar si el campo tipo_producto ya existe
$checkColumn = $conectar->query("SHOW COLUMNS FROM productos LIKE 'tipo_producto'");

if ($checkColumn->num_rows == 0) {
    // Agregar campo tipo_producto
    $alterQuery = "ALTER TABLE `productos` ADD `tipo_producto` VARCHAR(50) NULL DEFAULT 'normal' AFTER `descripcion`";

    if ($conectar->query($alterQuery)) {
        echo "✓ Campo 'tipo_producto' agregado exitosamente a la tabla productos<br>";
    } else {
        echo "✗ Error al agregar campo 'tipo_producto': " . $conectar->error . "<br>";
    }
} else {
    echo "⚠️ El campo 'tipo_producto' ya existe en la tabla productos<br>";
}

// 2. Verificar si ya existe el producto especial de revelado
$checkProducto = $conectar->query("SELECT id FROM productos WHERE tipo_producto='revelado' LIMIT 1");

if ($checkProducto->num_rows == 0) {
    // Obtener una categoría válida (preferiblemente "Impresiones" o crear una)
    $catQuery = $conectar->query("SELECT id FROM categorias LIMIT 1");
    $catRow = $catQuery->fetch_assoc();
    $id_categoria = $catRow ? $catRow['id'] : 1;

    // Insertar producto especial de revelado
    $insertQuery = "INSERT INTO `productos`
        (`nombre`, `descripcion`, `codigo`, `ancho`,`alto`, `profundidad`, `descuento`, `tipo_producto`, `precio`, `stock`, `id_categoria`, `color_rojo`, `color_naranja`, `color_azul`, `color_celeste`, `color_violeta`, `color_verde`, `color_amarillo` , `estado`)
        VALUES
        ('Revelado de Fotos',  'Impresión profesional de fotografías digitales con calidad de laboratorio', 'REVELADO001', 0,0,0,0,  'revelado', 0, 9999, '$id_categoria', 0, 0, 0, 0, 0, 0, 0, 0)";

    if ($conectar->query($insertQuery)) {
        $id_revelado = $conectar->insert_id;
        echo "✓ Producto especial 'Revelado de Fotos' creado exitosamente<br>";
        echo "<strong>ID del producto revelado: $id_revelado</strong><br>";
        echo "<br><strong>IMPORTANTE: Anota este ID para usarlo en la configuración.</strong><br>";
    } else {
        echo "✗ Error al crear producto de revelado: " . $conectar->error . "<br>";
    }
} else {
    $prodRow = $checkProducto->fetch_assoc();
    echo "⚠️ Ya existe un producto de tipo 'revelado' con ID: " . $prodRow['id'] . "<br>";
}

// 3. Agregar campo tipo en pedidos_detalle si no existe
$checkTipoDetalle = $conectar->query("SHOW COLUMNS FROM pedidos_detalle LIKE 'tipo'");

if ($checkTipoDetalle->num_rows == 0) {
    $alterDetalleQuery = "ALTER TABLE `pedidos_detalle` ADD `tipo` VARCHAR(50) NULL DEFAULT 'producto' AFTER `precio`";

    if ($conectar->query($alterDetalleQuery)) {
        echo "✓ Campo 'tipo' agregado exitosamente a la tabla pedidos_detalle<br>";
    } else {
        echo "✗ Error al agregar campo 'tipo': " . $conectar->error . "<br>";
    }
} else {
    echo "⚠️ El campo 'tipo' ya existe en la tabla pedidos_detalle<br>";
}

echo "<br><strong>Proceso completado.</strong>";
