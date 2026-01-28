<?php
/**
 * Script de migración para agregar columna 'cuit'
 * Fecha: 2026-01-28
 * Descripción: Agrega la columna 'cuit' (string/varchar) a la tabla clientes
 */

// Incluir conexión a la base de datos
include __DIR__.'/../../../conexion/conectar.inc.php';
global $conectar;

// Array para almacenar resultados
$resultados = [];
$errores = [];

echo "<h2>Migración: Agregar columna 'cuit' a tabla clientes</h2>";
echo "<hr>";

// Agregar columna 'cuit' a la tabla clientes
echo "<p><strong>1. Agregando columna 'cuit' a tabla 'clientes'...</strong></p>";
$query = "ALTER TABLE `clientes` ADD COLUMN `cuit` VARCHAR(20) NULL DEFAULT NULL AFTER `dni`";
try {
    $result = $conectar->query($query);
    if ($result) {
        $resultados[] = "✓ Columna 'cuit' agregada exitosamente a tabla 'clientes'";
        echo "<p style='color: green;'>✓ Columna 'cuit' agregada exitosamente a tabla 'clientes'</p>";
    } else {
        throw new Exception($conectar->error);
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        $resultados[] = "⚠ La columna 'cuit' ya existe en tabla 'clientes'";
        echo "<p style='color: orange;'>⚠ La columna 'cuit' ya existe en tabla 'clientes'</p>";
    } else {
        $errores[] = "✗ Error en tabla 'clientes': " . $e->getMessage();
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";

// Verificar que la columna se agregó correctamente
echo "<h3>2. Verificación de columna:</h3>";

$verify = $conectar->query("SHOW COLUMNS FROM clientes LIKE 'cuit'");
if ($verify && $verify->num_rows > 0) {
    echo "<p style='color: green;'>✓ Columna 'cuit' confirmada en tabla 'clientes'</p>";

    // Mostrar detalles de la columna
    $col_info = $verify->fetch_assoc();
    echo "<div style='background-color: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<p><strong>Detalles de la columna:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Campo:</strong> " . $col_info['Field'] . "</li>";
    echo "<li><strong>Tipo:</strong> " . $col_info['Type'] . "</li>";
    echo "<li><strong>Nulo:</strong> " . $col_info['Null'] . "</li>";
    echo "<li><strong>Default:</strong> " . ($col_info['Default'] ?? 'NULL') . "</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<p style='color: red;'>✗ Columna 'cuit' NO encontrada en tabla 'clientes'</p>";
    $errores[] = "Verificación falló: columna no encontrada";
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
    echo "<p>La columna 'cuit' ha sido agregada a la tabla 'clientes' y está lista para usarse.</p>";
    echo "</div>";
}

// Cerrar conexión
$conectar->close();
?>
