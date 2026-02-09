<?php
/**
 * Script de migración para agregar columna 'cuil' a tabla facturacion
 * Fecha: 2026-02-09
 * Descripción: Agrega la columna 'cuil' a la tabla facturacion (coexiste con 'cuit')
 */

// Incluir conexión a la base de datos
include __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Array para almacenar resultados
$resultados = [];
$errores = [];

echo "<h2>Migración: Agregar columna 'cuil' a tabla facturacion</h2>";
echo "<hr>";

// Verificar si las columnas existen
echo "<p><strong>1. Verificando existencia de columnas en tabla facturacion...</strong></p>";
$verify_cuil = $conectar->query("SHOW COLUMNS FROM facturacion LIKE 'cuil'");
$verify_cuit = $conectar->query("SHOW COLUMNS FROM facturacion LIKE 'cuit'");

if ($verify_cuit && $verify_cuit->num_rows > 0) {
    echo "<p style='color: green;'>✓ Columna 'cuit' encontrada (se mantendrá)</p>";

    // Mostrar información de la columna cuit
    $cuit_info = $verify_cuit->fetch_assoc();
    echo "<div style='background-color: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<p><strong>Información de la columna 'cuit' existente:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Tipo:</strong> " . $cuit_info['Type'] . "</li>";
    echo "<li><strong>Nulo:</strong> " . $cuit_info['Null'] . "</li>";
    echo "<li><strong>Default:</strong> " . ($cuit_info['Default'] ?? 'NULL') . "</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<p style='color: orange;'>⚠ Columna 'cuit' no existe en la tabla</p>";
}

if ($verify_cuil && $verify_cuil->num_rows > 0) {
    echo "<p style='color: orange;'>⚠ La columna 'cuil' ya existe en la tabla 'facturacion'</p>";
    $resultados[] = "⚠ La columna 'cuil' ya existe en la tabla";

    // Mostrar información de la columna cuil existente
    $cuil_info = $verify_cuil->fetch_assoc();
    echo "<div style='background-color: #fff3cd; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<p><strong>Información de la columna 'cuil' existente:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Tipo:</strong> " . $cuil_info['Type'] . "</li>";
    echo "<li><strong>Nulo:</strong> " . $cuil_info['Null'] . "</li>";
    echo "<li><strong>Default:</strong> " . ($cuil_info['Default'] ?? 'NULL') . "</li>";
    echo "</ul>";
    echo "</div>";
} else {
    // Agregar columna 'cuil'
    echo "<p><strong>2. Agregando columna 'cuil' a tabla facturacion...</strong></p>";

    $query = "ALTER TABLE `facturacion` ADD COLUMN `cuil` VARCHAR(100) NULL DEFAULT NULL COMMENT 'cuil del cliente' AFTER `cuit`";

    try {
        $result = $conectar->query($query);
        if ($result) {
            $resultados[] = "✓ Columna 'cuil' agregada exitosamente";
            echo "<p style='color: green;'>✓ Columna 'cuil' agregada exitosamente a la tabla 'facturacion'</p>";
            echo "<p style='color: blue;'><em>Nota: La columna 'cuit' se mantiene en la tabla para compatibilidad.</em></p>";
        } else {
            throw new Exception($conectar->error);
        }
    } catch (Exception $e) {
        $errores[] = "✗ Error al agregar columna: " . $e->getMessage();
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";

// Verificar que la columna se agregó correctamente
echo "<h3>3. Verificación de columnas en tabla facturacion:</h3>";

// Mostrar ambas columnas si existen
$verify_cuit_final = $conectar->query("SHOW COLUMNS FROM facturacion LIKE 'cuit'");
$verify_cuil_final = $conectar->query("SHOW COLUMNS FROM facturacion LIKE 'cuil'");

echo "<div style='display: flex; gap: 20px;'>";

// Verificar CUIT
if ($verify_cuit_final && $verify_cuit_final->num_rows > 0) {
    $col_info = $verify_cuit_final->fetch_assoc();
    echo "<div style='flex: 1; background-color: #e3f2fd; padding: 10px; border-radius: 5px;'>";
    echo "<p style='color: green;'><strong>✓ Columna 'cuit':</strong></p>";
    echo "<ul>";
    echo "<li><strong>Tipo:</strong> " . $col_info['Type'] . "</li>";
    echo "<li><strong>Nulo:</strong> " . $col_info['Null'] . "</li>";
    echo "<li><strong>Default:</strong> " . ($col_info['Default'] ?? 'NULL') . "</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div style='flex: 1; background-color: #ffebee; padding: 10px; border-radius: 5px;'>";
    echo "<p style='color: red;'>✗ Columna 'cuit' NO encontrada</p>";
    echo "</div>";
}

// Verificar CUIL
if ($verify_cuil_final && $verify_cuil_final->num_rows > 0) {
    $col_info = $verify_cuil_final->fetch_assoc();
    echo "<div style='flex: 1; background-color: #e8f5e9; padding: 10px; border-radius: 5px;'>";
    echo "<p style='color: green;'><strong>✓ Columna 'cuil':</strong></p>";
    echo "<ul>";
    echo "<li><strong>Tipo:</strong> " . $col_info['Type'] . "</li>";
    echo "<li><strong>Nulo:</strong> " . $col_info['Null'] . "</li>";
    echo "<li><strong>Default:</strong> " . ($col_info['Default'] ?? 'NULL') . "</li>";
    echo "<li><strong>Comentario:</strong> " . ($col_info['Comment'] ?? 'Sin comentario') . "</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div style='flex: 1; background-color: #ffebee; padding: 10px; border-radius: 5px;'>";
    echo "<p style='color: red;'>✗ Columna 'cuil' NO encontrada</p>";
    echo "</div>";
    $errores[] = "Verificación falló: columna 'cuil' no encontrada";
}

echo "</div>";

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
    echo "<p>La columna 'cuil' ha sido agregada a la tabla 'facturacion'.</p>";
    echo "<p><strong>Estado actual:</strong> Ambas columnas 'cuit' y 'cuil' coexisten en la tabla para compatibilidad.</p>";
    echo "</div>";
}

// Cerrar conexión
$conectar->close();
?>
