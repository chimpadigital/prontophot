<?php
/**
 * Script de migración para renombrar columna 'cuit' a 'cuil'
 * Fecha: 2026-02-09
 * Descripción: Renombra la columna 'cuit' a 'cuil' en la tabla clientes
 */

// Incluir conexión a la base de datos
include __DIR__.'/../../../conexion/conectar.inc.php';
global $conectar;

// Array para almacenar resultados
$resultados = [];
$errores = [];

echo "<h2>Migración: Renombrar columna 'cuit' a 'cuil' en tabla clientes</h2>";
echo "<hr>";

// Verificar si la columna 'cuit' existe
echo "<p><strong>1. Verificando existencia de columna 'cuit'...</strong></p>";
$verify_cuit = $conectar->query("SHOW COLUMNS FROM clientes LIKE 'cuit'");

if ($verify_cuit && $verify_cuit->num_rows > 0) {
    echo "<p style='color: green;'>✓ Columna 'cuit' encontrada</p>";

    // Obtener información de la columna actual
    $col_info = $verify_cuit->fetch_assoc();
    $tipo = $col_info['Type'];
    $nulo = $col_info['Null'];
    $default = $col_info['Default'];

    echo "<div style='background-color: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<p><strong>Información de la columna actual:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Campo:</strong> " . $col_info['Field'] . "</li>";
    echo "<li><strong>Tipo:</strong> " . $tipo . "</li>";
    echo "<li><strong>Nulo:</strong> " . $nulo . "</li>";
    echo "<li><strong>Default:</strong> " . ($default ?? 'NULL') . "</li>";
    echo "</ul>";
    echo "</div>";

    // Renombrar columna 'cuit' a 'cuil'
    echo "<p><strong>2. Renombrando columna 'cuit' a 'cuil'...</strong></p>";

    // Construir la definición de la columna
    $null_clause = ($nulo == 'YES') ? 'NULL' : 'NOT NULL';
    $default_clause = '';
    if ($default !== null) {
        $default_clause = " DEFAULT '" . $conectar->real_escape_string($default) . "'";
    } else if ($nulo == 'YES') {
        $default_clause = " DEFAULT NULL";
    }

    $query = "ALTER TABLE `clientes` CHANGE COLUMN `cuit` `cuil` {$tipo} {$null_clause}{$default_clause}";

    try {
        $result = $conectar->query($query);
        if ($result) {
            $resultados[] = "✓ Columna renombrada exitosamente de 'cuit' a 'cuil'";
            echo "<p style='color: green;'>✓ Columna renombrada exitosamente de 'cuit' a 'cuil'</p>";
        } else {
            throw new Exception($conectar->error);
        }
    } catch (Exception $e) {
        $errores[] = "✗ Error al renombrar columna: " . $e->getMessage();
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }

} else {
    // Verificar si ya existe la columna 'cuil'
    $verify_cuil = $conectar->query("SHOW COLUMNS FROM clientes LIKE 'cuil'");
    if ($verify_cuil && $verify_cuil->num_rows > 0) {
        echo "<p style='color: orange;'>⚠ La columna 'cuit' no existe, pero 'cuil' ya está presente</p>";
        $resultados[] = "⚠ La columna 'cuil' ya existe en la tabla";
    } else {
        echo "<p style='color: red;'>✗ La columna 'cuit' no existe en la tabla 'clientes'</p>";
        $errores[] = "Columna 'cuit' no encontrada";
    }
}

echo "<hr>";

// Verificar que la columna se renombró correctamente
echo "<h3>3. Verificación de columna 'cuil':</h3>";

$verify = $conectar->query("SHOW COLUMNS FROM clientes LIKE 'cuil'");
if ($verify && $verify->num_rows > 0) {
    echo "<p style='color: green;'>✓ Columna 'cuil' confirmada en tabla 'clientes'</p>";

    // Mostrar detalles de la columna
    $col_info = $verify->fetch_assoc();
    echo "<div style='background-color: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<p><strong>Detalles de la columna renombrada:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Campo:</strong> " . $col_info['Field'] . "</li>";
    echo "<li><strong>Tipo:</strong> " . $col_info['Type'] . "</li>";
    echo "<li><strong>Nulo:</strong> " . $col_info['Null'] . "</li>";
    echo "<li><strong>Default:</strong> " . ($col_info['Default'] ?? 'NULL') . "</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<p style='color: red;'>✗ Columna 'cuil' NO encontrada en tabla 'clientes'</p>";
    $errores[] = "Verificación falló: columna 'cuil' no encontrada";
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
    echo "<p>La columna ha sido renombrada de 'cuit' a 'cuil' en la tabla 'clientes' y está lista para usarse.</p>";
    echo "</div>";
}

// Cerrar conexión
$conectar->close();
?>
