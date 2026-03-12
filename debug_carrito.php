<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Debug Carrito - Revelado</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #dc3545; }
        h3 { color: #007bff; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { background: #f0f0f0; padding: 10px; margin: 10px 0; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        table td, table th { border: 1px solid #ddd; padding: 8px; }
        table th { background-color: #007bff; color: white; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>

<h2>🔍 DEBUG - Carrito de Revelado</h2>

<?php if (!isset($_SESSION['pronto']['cart']) || empty($_SESSION['pronto']['cart'])): ?>
    <p class="error">❌ No hay productos en el carrito</p>
    <p><a href="revelado_1.php">Ir a Revelado</a></p>
<?php else: ?>

    <h3>📦 Productos en Carrito (<?php echo count($_SESSION['pronto']['cart']); ?>)</h3>

    <?php
    $hay_revelado = false;
    foreach ($_SESSION['pronto']['cart'] as $id => $datos):
        $esRevelado = isset($datos['tipo']) && $datos['tipo'] === 'revelado';
        if ($esRevelado) $hay_revelado = true;
    ?>

        <div class="info">
            <h4>Producto ID: <?php echo $id; ?></h4>

            <?php if ($esRevelado): ?>
                <p class="success">✅ ES PRODUCTO DE REVELADO</p>

                <table>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td>Tipo</td>
                        <td><?php echo $datos['tipo']; ?></td>
                    </tr>
                    <tr>
                        <td>Cantidad Total</td>
                        <td><?php echo $datos['cantidad']; ?> fotos</td>
                    </tr>
                    <tr>
                        <td>Precio Total</td>
                        <td>$<?php echo $datos['precio']; ?></td>
                    </tr>
                    <tr>
                        <td>Descripción</td>
                        <td><?php echo $datos['descripcion']; ?></td>
                    </tr>
                    <tr>
                        <td>Categoría</td>
                        <td><?php echo $datos['cat']; ?></td>
                    </tr>
                    <tr>
                        <td>ID Original</td>
                        <td><?php echo $datos['id_original']; ?></td>
                    </tr>
                </table>

                <h4>🖼️ Verificación de Imágenes</h4>

                <?php if (isset($datos['datos_revelado'])): ?>
                    <p class="success">✅ Campo 'datos_revelado' existe</p>

                    <?php if (isset($datos['datos_revelado']['imagenes'])): ?>
                        <p class="success">✅ Campo 'datos_revelado[imagenes]' existe</p>
                        <p><strong>Total de imágenes:</strong> <?php echo count($datos['datos_revelado']['imagenes']); ?></p>

                        <?php if (count($datos['datos_revelado']['imagenes']) > 0): ?>
                            <table>
                                <tr>
                                    <th>#</th>
                                    <th>Archivo</th>
                                    <th>Tamaño</th>
                                    <th>Acabado</th>
                                    <th>Cantidad</th>
                                    <th>¿Existe?</th>
                                    <th>Preview</th>
                                </tr>
                                <?php
                                $i = 1;
                                foreach ($datos['datos_revelado']['imagenes'] as $img):
                                    $archivo_existe = file_exists($img['archivo']);
                                ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo htmlspecialchars($img['archivo']); ?></td>
                                        <td><?php echo htmlspecialchars($img['tamano']); ?></td>
                                        <td><?php echo htmlspecialchars($img['acabado']); ?></td>
                                        <td><?php echo htmlspecialchars($img['cantidad']); ?></td>
                                        <td><?php echo $archivo_existe ? '<span class="success">✅ SÍ</span>' : '<span class="error">❌ NO</span>'; ?></td>
                                        <td>
                                            <?php if ($archivo_existe): ?>
                                                <img src="<?php echo htmlspecialchars($img['archivo']); ?>" style="max-width: 100px; max-height: 100px;">
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php
                                    $i++;
                                endforeach;
                                ?>
                            </table>

                            <h4>📄 JSON de datos_revelado (para copiar al modal)</h4>
                            <pre><?php echo json_encode($datos['datos_revelado'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?></pre>

                        <?php else: ?>
                            <p class="error">❌ El array de imágenes está VACÍO</p>
                        <?php endif; ?>

                    <?php else: ?>
                        <p class="error">❌ NO existe el campo 'datos_revelado[imagenes]'</p>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="error">❌ NO existe el campo 'datos_revelado'</p>
                <?php endif; ?>

                <h4>🧪 Probar Modal (JavaScript)</h4>
                <button onclick="probarModal('<?php echo $id; ?>')">🔍 Ver Fotos (Modal Simulado)</button>
                <div id="resultado-<?php echo $id; ?>" style="margin-top: 10px;"></div>

            <?php else: ?>
                <p><strong>Tipo:</strong> Producto Normal</p>
                <p><strong>Cantidad:</strong> <?php echo $datos['cantidad']; ?></p>
                <p><strong>Categoría:</strong> <?php echo $datos['cat'] ?? 'N/A'; ?></p>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

    <?php if (!$hay_revelado): ?>
        <div class="info">
            <p class="error">⚠️ No hay productos de revelado en el carrito</p>
            <p>Solo hay productos normales.</p>
        </div>
    <?php endif; ?>

<?php endif; ?>

<hr>
<h3>🔧 Acciones</h3>
<p><a href="miCarro.php">← Volver al Carrito</a></p>
<p><a href="revelado_1.php">Agregar Revelado</a></p>
<p><a href="?limpiar=1" onclick="return confirm('¿Seguro que deseas limpiar el carrito?')">🗑️ Limpiar Carrito</a></p>

<?php
if (isset($_GET['limpiar'])) {
    unset($_SESSION['pronto']['cart']);
    unset($_SESSION['archivos']);
    echo '<script>alert("Carrito limpiado"); window.location.href="debug_carrito.php";</script>';
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
function probarModal(productoId) {
    console.log('Probando modal para producto:', productoId);

    $('#resultado-' + productoId).html('<p>⏳ Cargando...</p>');

    $.ajax({
        type: 'POST',
        url: 'inc/obtener_fotos_revelado.php',
        data: { producto_id: productoId },
        dataType: 'json',
        success: function(data){
            console.log('Respuesta del servidor:', data);

            if(data.success && data.fotos && data.fotos.length > 0){
                var html = '<div style="background: #d4edda; padding: 10px; margin: 10px 0;">';
                html += '<p class="success">✅ AJAX EXITOSO</p>';
                html += '<p><strong>Total fotos:</strong> ' + data.total_fotos + '</p>';
                html += '<p><strong>Fotos encontradas:</strong> ' + data.fotos.length + '</p>';
                html += '<h5>Resumen:</h5><ul>';
                for(var tipo in data.resumen){
                    html += '<li>' + data.resumen[tipo] + ' fotos ' + tipo + '</li>';
                }
                html += '</ul>';
                html += '<h5>Imágenes:</h5>';
                data.fotos.forEach(function(foto, index){
                    html += '<div style="border: 1px solid #ddd; padding: 5px; margin: 5px 0;">';
                    html += '<p><strong>Foto ' + (index + 1) + ':</strong></p>';
                    html += '<img src="' + foto.archivo + '" style="max-width: 150px; max-height: 150px;"><br>';
                    html += 'Tamaño: ' + foto.tamano + ' | Acabado: ' + foto.acabado + ' | Cantidad: ' + foto.cantidad;
                    html += '</div>';
                });
                html += '</div>';

                $('#resultado-' + productoId).html(html);
            } else {
                $('#resultado-' + productoId).html('<div style="background: #f8d7da; padding: 10px;"><p class="error">❌ Error: ' + (data.error || 'No se encontraron fotos') + '</p></div>');
            }
        },
        error: function(xhr, status, error){
            console.error('Error AJAX:', error);
            console.error('Response:', xhr.responseText);
            $('#resultado-' + productoId).html('<div style="background: #f8d7da; padding: 10px;"><p class="error">❌ Error de conexión: ' + error + '</p><pre>' + xhr.responseText + '</pre></div>');
        }
    });
}
</script>

</body>
</html>
