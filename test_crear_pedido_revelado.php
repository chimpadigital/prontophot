<?php
session_start();
include 'inc/config.inc.php';
global $conectar;

echo "<h2>🧪 Test - Crear Pedido con Revelado</h2>";

// Verificar que hay un carrito con revelado
if (!isset($_SESSION['pronto']['cart']) || empty($_SESSION['pronto']['cart'])) {
    echo "<p style='color: red;'>❌ No hay productos en el carrito</p>";
    echo "<p><a href='debug_carrito.php'>Ir a Debug Carrito</a> | <a href='revelado_1.php'>Agregar Revelado</a></p>";
    exit;
}

$tiene_revelado = false;
$producto_revelado_id = null;
$datos_revelado = null;

foreach ($_SESSION['pronto']['cart'] as $id => $datos) {
    if (isset($datos['tipo']) && $datos['tipo'] === 'revelado') {
        $tiene_revelado = true;
        $producto_revelado_id = $id;
        $datos_revelado = $datos;
        break;
    }
}

if (!$tiene_revelado) {
    echo "<p style='color: orange;'>⚠️ No hay productos de revelado en el carrito</p>";
    echo "<p><a href='debug_carrito.php'>Ver Carrito</a> | <a href='revelado_1.php'>Agregar Revelado</a></p>";
    exit;
}

echo "<h3>✅ Producto de Revelado Encontrado</h3>";
echo "<p><strong>ID del producto:</strong> $producto_revelado_id</p>";
echo "<p><strong>Cantidad total:</strong> {$datos_revelado['cantidad']} fotos</p>";
echo "<p><strong>Precio total:</strong> \${$datos_revelado['precio']}</p>";

// Verificar imágenes
if (!isset($datos_revelado['datos_revelado']['imagenes'])) {
    echo "<p style='color: red;'>❌ ERROR: No hay campo 'datos_revelado[imagenes]'</p>";
    exit;
}

$imagenes = $datos_revelado['datos_revelado']['imagenes'];
echo "<p><strong>Imágenes en carrito:</strong> " . count($imagenes) . "</p>";

// Mostrar las imágenes
echo "<h3>📸 Imágenes a Guardar</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #007bff; color: white;'>";
echo "<th>#</th><th>Archivo</th><th>Tamaño</th><th>Acabado</th><th>Cantidad</th><th>¿Archivo Existe?</th>";
echo "</tr>";

$total_fotos = 0;
foreach ($imagenes as $key => $img) {
    $existe = file_exists($img['archivo']) ? "✅ SÍ" : "❌ NO";
    $color = file_exists($img['archivo']) ? "green" : "red";

    echo "<tr>";
    echo "<td>" . ($key + 1) . "</td>";
    echo "<td>" . htmlspecialchars($img['archivo']) . "</td>";
    echo "<td>{$img['tamano']}</td>";
    echo "<td>{$img['acabado']}</td>";
    echo "<td>{$img['cantidad']}</td>";
    echo "<td style='color: $color; font-weight: bold;'>$existe</td>";
    echo "</tr>";

    $total_fotos += intval($img['cantidad']);
}
echo "</table>";
echo "<p><strong>Total de copias:</strong> $total_fotos</p>";

// SIMULACIÓN DE GUARDADO
echo "<h3>🔧 Simulación de Guardado en pedidos_imagenes</h3>";

if (isset($_POST['simular'])) {
    $id_pedido_simulado = 999999; // ID de prueba

    echo "<div style='background: #f0f0f0; padding: 15px; margin: 10px 0;'>";
    echo "<h4>Simulando guardado para pedido ID: $id_pedido_simulado</h4>";

    $contador_exitoso = 0;
    $contador_error = 0;

    foreach ($imagenes as $key => $imagen) {
        echo "<hr>";
        echo "<p><strong>Procesando imagen " . ($key + 1) . ":</strong></p>";

        $archivo = $conectar->real_escape_string($imagen['archivo']);
        $acabado = $conectar->real_escape_string($imagen['acabado']);
        $tamano = $conectar->real_escape_string($imagen['tamano']);
        $cantidad_img = intval($imagen['cantidad']);

        // Obtener ID de impresión
        $query_imp = "SELECT id FROM `impresiones` WHERE formato='$tamano' ORDER BY id DESC LIMIT 1";
        echo "<p style='font-size: 12px;'>Query impresión: <code>$query_imp</code></p>";

        $res_imp = $conectar->query($query_imp);
        $idimpresion = 0;

        if ($res_imp && $res_imp->num_rows > 0) {
            $row_imp = $res_imp->fetch_assoc();
            $idimpresion = $row_imp['id'];
            echo "<p style='color: green;'>✓ ID impresión encontrado: $idimpresion</p>";
        } else {
            echo "<p style='color: red;'>✗ No se encontró ID de impresión para formato '$tamano'</p>";
        }

        // Crear thumbnail
        $thumb = str_replace('img/impresiones/', 'img/tmp/', $archivo);

        // SQL de inserción
        $sql_imagen = "INSERT INTO `pedidos_imagenes`( `id_pedido`, `imagen`, `thumb`, `acabado`, `formato`, `cantidad`, `id_impresion`) VALUES ('$id_pedido_simulado','$archivo','$thumb','$acabado','$tamano','$cantidad_img','$idimpresion')";

        echo "<pre style='background: white; padding: 5px; font-size: 11px;'>$sql_imagen</pre>";

        // Ejecutar INSERT
        $resultado = $conectar->query($sql_imagen);

        if ($resultado) {
            $insert_id = $conectar->insert_id;
            echo "<p style='color: green; font-weight: bold;'>✅ INSERT EXITOSO - ID: $insert_id</p>";
            $contador_exitoso++;
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ ERROR: " . $conectar->error . "</p>";
            $contador_error++;
        }
    }

    echo "<hr>";
    echo "<h4>Resumen:</h4>";
    echo "<p style='color: green;'><strong>✅ Exitosos:</strong> $contador_exitoso</p>";
    echo "<p style='color: red;'><strong>❌ Errores:</strong> $contador_error</p>";

    // Verificar registros insertados
    $verificacion = $conectar->query("SELECT * FROM pedidos_imagenes WHERE id_pedido=$id_pedido_simulado");
    $total_insertados = $verificacion->num_rows;

    echo "<p><strong>Registros insertados en BD:</strong> $total_insertados</p>";

    if ($total_insertados > 0) {
        echo "<h4>Registros en BD:</h4>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #28a745; color: white;'>";
        echo "<th>ID</th><th>Formato</th><th>Acabado</th><th>Cantidad</th><th>ID Impresión</th>";
        echo "</tr>";

        while ($row = $verificacion->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['formato']}</td>";
            echo "<td>{$row['acabado']}</td>";
            echo "<td>{$row['cantidad']}</td>";
            echo "<td>{$row['id_impresion']}</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Limpiar registros de prueba
        if (isset($_POST['limpiar'])) {
            $conectar->query("DELETE FROM pedidos_imagenes WHERE id_pedido=$id_pedido_simulado");
            echo "<p style='color: blue;'>🗑️ Registros de prueba eliminados</p>";
            echo "<script>window.location.href='test_crear_pedido_revelado.php';</script>";
        } else {
            echo "<form method='post'>";
            echo "<input type='hidden' name='simular' value='1'>";
            echo "<input type='hidden' name='limpiar' value='1'>";
            echo "<button type='submit' style='background: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer;'>🗑️ Limpiar Registros de Prueba</button>";
            echo "</form>";
        }
    }

    echo "</div>";
} else {
    echo "<form method='post'>";
    echo "<button type='submit' name='simular' value='1' style='background: #007bff; color: white; padding: 15px 30px; border: none; cursor: pointer; font-size: 16px;'>▶️ SIMULAR GUARDADO</button>";
    echo "</form>";
    echo "<p style='color: orange;'><strong>Nota:</strong> Esto insertará registros de prueba con id_pedido=999999. Podrás eliminarlos después.</p>";
}

echo "<hr>";
echo "<p><a href='debug_carrito.php'>← Debug Carrito</a> | <a href='test_pedidos_imagenes.php'>Ver Tabla pedidos_imagenes</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h2 { color: #dc3545; }
    h3 { color: #007bff; }
    code { background: #f0f0f0; padding: 2px 5px; }
</style>
