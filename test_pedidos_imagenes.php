<?php
include 'inc/config.inc.php';
global $conectar;

echo "<h2>🔍 Test - Tabla pedidos_imagenes</h2>";

// 1. Verificar estructura de la tabla
echo "<h3>1️⃣ Estructura de la tabla pedidos_imagenes</h3>";
$result = $conectar->query("DESCRIBE pedidos_imagenes");

if ($result) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr style='background: #007bff; color: white;'>";
    echo "<th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th>";
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><strong>{$row['Field']}</strong></td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Error al obtener estructura: " . $conectar->error . "</p>";
}

// 2. Verificar registros existentes
echo "<h3>2️⃣ Registros en pedidos_imagenes</h3>";
$count = $conectar->query("SELECT COUNT(*) as total FROM pedidos_imagenes");
$total = $count->fetch_assoc()['total'];
echo "<p><strong>Total de registros:</strong> $total</p>";

if ($total > 0) {
    echo "<h4>Últimos 10 registros:</h4>";
    $result = $conectar->query("SELECT * FROM pedidos_imagenes ORDER BY id DESC LIMIT 10");

    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #28a745; color: white;'>";
        echo "<th>ID</th><th>ID Pedido</th><th>Imagen</th><th>Formato</th><th>Acabado</th><th>Cantidad</th><th>ID Impresión</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['id_pedido']}</td>";
            echo "<td>" . substr($row['imagen'], 0, 50) . "...</td>";
            echo "<td>{$row['formato']}</td>";
            echo "<td>{$row['acabado']}</td>";
            echo "<td>{$row['cantidad']}</td>";
            echo "<td>{$row['id_impresion']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ No hay registros en la tabla</p>";
}

// 3. Test de INSERT
echo "<h3>3️⃣ Test de INSERT (prueba)</h3>";
echo "<p>Vamos a intentar insertar un registro de prueba...</p>";

$test_sql = "INSERT INTO `pedidos_imagenes`(
    `id_pedido`, `imagen`, `thumb`, `acabado`, `formato`, `cantidad`, `id_impresion`
) VALUES (
    999999,
    'img/impresiones/test.jpg',
    'img/tmp/test.jpg',
    'Brillante',
    '10x15',
    1,
    1
)";

echo "<pre style='background: #f0f0f0; padding: 10px;'>$test_sql</pre>";

if ($conectar->query($test_sql)) {
    $insert_id = $conectar->insert_id;
    echo "<p style='color: green;'>✅ INSERT exitoso. ID generado: $insert_id</p>";

    // Eliminar el registro de prueba
    $conectar->query("DELETE FROM pedidos_imagenes WHERE id=$insert_id");
    echo "<p style='color: blue;'>ℹ️ Registro de prueba eliminado</p>";
} else {
    echo "<p style='color: red;'>❌ ERROR en INSERT: " . $conectar->error . "</p>";
}

// 4. Verificar tabla impresiones
echo "<h3>4️⃣ Tabla impresiones (formatos disponibles)</h3>";
$impresiones = $conectar->query("SELECT * FROM impresiones ORDER BY formato, desde");

if ($impresiones && $impresiones->num_rows > 0) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr style='background: #ffc107;'>";
    echo "<th>ID</th><th>Formato</th><th>Desde</th><th>Hasta</th><th>Precio</th>";
    echo "</tr>";

    while ($row = $impresiones->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><strong>{$row['formato']}</strong></td>";
        echo "<td>{$row['desde']}</td>";
        echo "<td>{$row['hasta']}</td>";
        echo "<td>\${$row['precio']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ No hay registros en tabla impresiones</p>";
}

// 5. Verificar pedidos con revelado
echo "<h3>5️⃣ Pedidos con productos de revelado</h3>";
$pedidos_revelado = $conectar->query("
    SELECT p.id, p.fecha, pd.cantidad, pd.tipo
    FROM pedidos p
    INNER JOIN pedidos_detalle pd ON p.id = pd.id_pedido
    WHERE pd.tipo = 'revelado'
    ORDER BY p.id DESC
    LIMIT 10
");

if ($pedidos_revelado && $pedidos_revelado->num_rows > 0) {
    echo "<p><strong>Total de pedidos con revelado:</strong> " . $pedidos_revelado->num_rows . "</p>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr style='background: #dc3545; color: white;'>";
    echo "<th>ID Pedido</th><th>Fecha</th><th>Cantidad Fotos</th><th>Imágenes en BD</th><th>Estado</th>";
    echo "</tr>";

    while ($row = $pedidos_revelado->fetch_assoc()) {
        $id_pedido = $row['id'];

        // Verificar si tiene imágenes guardadas
        $count_img = $conectar->query("SELECT COUNT(*) as total FROM pedidos_imagenes WHERE id_pedido=$id_pedido");
        $total_img = $count_img->fetch_assoc()['total'];

        $estado = $total_img > 0 ? "✅ OK ($total_img imgs)" : "❌ SIN IMÁGENES";
        $color = $total_img > 0 ? "green" : "red";

        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['fecha']}</td>";
        echo "<td>{$row['cantidad']}</td>";
        echo "<td>$total_img</td>";
        echo "<td style='color: $color; font-weight: bold;'>$estado</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠️ No hay pedidos con revelado</p>";
}

echo "<hr>";
echo "<p><a href='debug_carrito.php'>← Ir a Debug Carrito</a> | <a href='admin/pedidos.php'>Ver Pedidos</a></p>";
?>

<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h2 { color: #dc3545; }
    h3 { color: #007bff; margin-top: 30px; }
    table { margin: 10px 0; }
</style>
