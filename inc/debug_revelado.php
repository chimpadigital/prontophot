<?php
session_start();
include 'config.inc.php';
global $conectar;

echo "<h2>DEBUG - Estado del Carrito</h2>";

if (!isset($_SESSION['pronto']['cart']) || empty($_SESSION['pronto']['cart'])) {
    echo "<p style='color: red;'>No hay productos en el carrito</p>";
    exit;
}

echo "<h3>Productos en Carrito:</h3>";
echo "<pre>";
print_r($_SESSION['pronto']['cart']);
echo "</pre>";

echo "<h3>Verificación de Productos de Revelado:</h3>";

foreach ($_SESSION['pronto']['cart'] as $id => $datos) {
    echo "<hr>";
    echo "<h4>Producto ID: $id</h4>";

    if (isset($datos['tipo']) && $datos['tipo'] === 'revelado') {
        echo "<p style='color: green;'><strong>✓ Es producto de REVELADO</strong></p>";
        echo "<p><strong>Cantidad total:</strong> {$datos['cantidad']} fotos</p>";
        echo "<p><strong>Precio total:</strong> \${$datos['precio']}</p>";
        echo "<p><strong>Descripción:</strong> {$datos['descripcion']}</p>";

        if (isset($datos['datos_revelado']['imagenes'])) {
            echo "<h5>Imágenes encontradas: " . count($datos['datos_revelado']['imagenes']) . "</h5>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>#</th><th>Archivo</th><th>Tamaño</th><th>Acabado</th><th>Cantidad</th></tr>";

            $i = 1;
            foreach ($datos['datos_revelado']['imagenes'] as $img) {
                echo "<tr>";
                echo "<td>$i</td>";
                echo "<td>" . htmlspecialchars($img['archivo']) . "</td>";
                echo "<td>" . htmlspecialchars($img['tamano']) . "</td>";
                echo "<td>" . htmlspecialchars($img['acabado']) . "</td>";
                echo "<td>" . htmlspecialchars($img['cantidad']) . "</td>";
                echo "</tr>";

                // Verificar si el archivo existe
                $archivo_path = "../" . $img['archivo'];
                if (file_exists($archivo_path)) {
                    echo "<tr><td colspan='5' style='color: green;'>✓ Archivo existe en: $archivo_path</td></tr>";
                } else {
                    echo "<tr><td colspan='5' style='color: red;'>✗ Archivo NO existe: $archivo_path</td></tr>";
                }

                $i++;
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'><strong>✗ NO hay imágenes en datos_revelado</strong></p>";
        }
    } else {
        echo "<p><strong>Tipo:</strong> Producto normal</p>";
        echo "<p><strong>Cantidad:</strong> {$datos['cantidad']}</p>";
        echo "<p><strong>Categoría:</strong> " . ($datos['cat'] ?? 'N/A') . "</p>";
    }
}

echo "<hr>";
echo "<h3>Simulación de INSERT SQL para pedidos_imagenes:</h3>";

foreach ($_SESSION['pronto']['cart'] as $id => $datos) {
    if (isset($datos['tipo']) && $datos['tipo'] === 'revelado' && isset($datos['datos_revelado']['imagenes'])) {
        echo "<p><strong>Para producto revelado ID: $id</strong></p>";

        foreach ($datos['datos_revelado']['imagenes'] as $imagen) {
            $archivo = $conectar->real_escape_string($imagen['archivo']);
            $acabado = $conectar->real_escape_string($imagen['acabado']);
            $tamano = $conectar->real_escape_string($imagen['tamano']);
            $cantidad_img = intval($imagen['cantidad']);
            $thumb = str_replace('img/impresiones/', 'img/tmp/', $archivo);

            // Simular obtención de id_impresion
            $query_imp = "SELECT id FROM `impresiones` WHERE formato='$tamano' ORDER BY id DESC LIMIT 1";
            $res_imp = $conectar->query($query_imp);
            $idimpresion = 0;
            if($res_imp && $res_imp->num_rows > 0){
                $row_imp = $res_imp->fetch_assoc();
                $idimpresion = $row_imp['id'];
            }

            $sql = "INSERT INTO `pedidos_imagenes`( `id_pedido`, `imagen`, `thumb`, `acabado`, `formato`, `cantidad`, `id_impresion`) VALUES ('[ID_PEDIDO]','$archivo','$thumb','$acabado','$tamano','$cantidad_img','$idimpresion')";

            echo "<div style='background: #f0f0f0; padding: 10px; margin: 5px 0; font-family: monospace;'>";
            echo htmlspecialchars($sql);
            echo "</div>";
            echo "<p style='font-size: 12px;'>id_impresion encontrado: " . ($idimpresion > 0 ? $idimpresion : "<span style='color: red;'>NO ENCONTRADO</span>") . "</p>";
        }
    }
}

echo "<hr>";
echo "<h3>Tabla impresiones (para verificar formatos):</h3>";
$impresiones = $conectar->query("SELECT * FROM impresiones ORDER BY formato, desde");
if ($impresiones && $impresiones->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Formato</th><th>Desde</th><th>Hasta</th><th>Precio</th></tr>";
    while ($row = $impresiones->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['formato']}</td>";
        echo "<td>{$row['desde']}</td>";
        echo "<td>{$row['hasta']}</td>";
        echo "<td>\${$row['precio']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>No hay registros en tabla impresiones</p>";
}
?>
