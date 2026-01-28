<?php
/**
 * Script de migración para agregar columna 'altura'
 * Fecha: 2026-01-28
 * Descripción: Agrega la columna 'altura' (número entero) a las tablas clientes, facturacion y pedidos
 */

// Incluir conexión a la base de datos
include __DIR__.'/../../../conexion/conectar.inc.php';
global $conectar;

// Array para almacenar resultados
$resultados = [];
$errores = [];

echo "<h2>Migración: Agregar columna 'altura'</h2>";
echo "<hr>";

// 1. Agregar columna 'altura' a la tabla clientes
echo "<p><strong>1. Agregando columna 'altura' a tabla 'clientes'...</strong></p>";
$query1 = "ALTER TABLE `clientes` ADD COLUMN `altura` INT(11) NULL DEFAULT NULL AFTER `cp`";
try {
    $result1 = $conectar->query($query1);
    if ($result1) {
        $resultados[] = "✓ Columna 'altura' agregada exitosamente a tabla 'clientes'";
        echo "<p style='color: green;'>✓ Columna 'altura' agregada exitosamente a tabla 'clientes'</p>";
    } else {
        throw new Exception($conectar->error);
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        $resultados[] = "⚠ La columna 'altura' ya existe en tabla 'clientes'";
        echo "<p style='color: orange;'>⚠ La columna 'altura' ya existe en tabla 'clientes'</p>";
    } else {
        $errores[] = "✗ Error en tabla 'clientes': " . $e->getMessage();
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

// 2. Agregar columna 'altura' a la tabla facturacion
echo "<p><strong>2. Agregando columna 'altura' a tabla 'facturacion'...</strong></p>";
$query2 = "ALTER TABLE `facturacion` ADD COLUMN `altura` INT(11) NULL DEFAULT NULL AFTER `cp`";
try {
    $result2 = $conectar->query($query2);
    if ($result2) {
        $resultados[] = "✓ Columna 'altura' agregada exitosamente a tabla 'facturacion'";
        echo "<p style='color: green;'>✓ Columna 'altura' agregada exitosamente a tabla 'facturacion'</p>";
    } else {
        throw new Exception($conectar->error);
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        $resultados[] = "⚠ La columna 'altura' ya existe en tabla 'facturacion'";
        echo "<p style='color: orange;'>⚠ La columna 'altura' ya existe en tabla 'facturacion'</p>";
    } else {
        $errores[] = "✗ Error en tabla 'facturacion': " . $e->getMessage();
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

// 3. Agregar columna 'altura' a la tabla pedidos
echo "<p><strong>3. Agregando columna 'altura' a tabla 'pedidos'...</strong></p>";
$query3 = "ALTER TABLE `pedidos` ADD COLUMN `altura` INT(11) NULL DEFAULT NULL AFTER `cp`";
try {
    $result3 = $conectar->query($query3);
    if ($result3) {
        $resultados[] = "✓ Columna 'altura' agregada exitosamente a tabla 'pedidos'";
        echo "<p style='color: green;'>✓ Columna 'altura' agregada exitosamente a tabla 'pedidos'</p>";
    } else {
        throw new Exception($conectar->error);
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        $resultados[] = "⚠ La columna 'altura' ya existe en tabla 'pedidos'";
        echo "<p style='color: orange;'>⚠ La columna 'altura' ya existe en tabla 'pedidos'</p>";
    } else {
        $errores[] = "✗ Error en tabla 'pedidos': " . $e->getMessage();
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";

// Verificar que las columnas se agregaron correctamente
echo "<h3>4. Verificación de columnas:</h3>";

// Verificar clientes
$verify1 = $conectar->query("SHOW COLUMNS FROM clientes LIKE 'altura'");
if ($verify1 && $verify1->num_rows > 0) {
    echo "<p style='color: green;'>✓ Columna 'altura' confirmada en tabla 'clientes'</p>";
} else {
    echo "<p style='color: red;'>✗ Columna 'altura' NO encontrada en tabla 'clientes'</p>";
}

// Verificar facturacion
$verify2 = $conectar->query("SHOW COLUMNS FROM facturacion LIKE 'altura'");
if ($verify2 && $verify2->num_rows > 0) {
    echo "<p style='color: green;'>✓ Columna 'altura' confirmada en tabla 'facturacion'</p>";
} else {
    echo "<p style='color: red;'>✗ Columna 'altura' NO encontrada en tabla 'facturacion'</p>";
}

// Verificar pedidos
$verify3 = $conectar->query("SHOW COLUMNS FROM pedidos LIKE 'altura'");
if ($verify3 && $verify3->num_rows > 0) {
    echo "<p style='color: green;'>✓ Columna 'altura' confirmada en tabla 'pedidos'</p>";
} else {
    echo "<p style='color: red;'>✗ Columna 'altura' NO encontrada en tabla 'pedidos'</p>";
}

echo "<hr>";

// Resumen final
echo "<h3>Resumen de Migración:</h3>";
echo "<p><strong>Operaciones exitosas:</strong> " . count($resultados) . "</p>";
echo "<p><strong>Errores:</strong> " . count($errores) . "</p>";

if (count($errores) > 0) {
    echo "<div style='background-color: #ffebee; padding: 10px; border-radius: 5px;'>";
    echo "<h4>Detalles de errores:</h4>";
    foreach ($errores as $error) {
        echo "<p>" . $error . "</p>";
    }
    echo "</div>";
} else {
    echo "<div style='background-color: #e8f5e9; padding: 10px; border-radius: 5px;'>";
    echo "<p style='color: green;'><strong>✓ Migración completada exitosamente</strong></p>";
    echo "</div>";
}

// Cerrar conexión
$conectar->close();
?>
