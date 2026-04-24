<?php
session_start();
ini_set("error_log", "guardarrevelado.log");
require_once ('../conexion/conectar.inc.php');

function extension($ext){
    switch ($ext){
        case 'image/jpeg':
            return '.jpg';
        break;
        case 'image/png':
            return '.png';
        break;
        case 'image/webp':
            return '.webp';
        break;
    }
}

if (!isset($_POST['tamanogral'])) {
    header('Location: ../revelar-fotos');
    exit();
}

$dir = __DIR__ . '/../img/impresiones';

// Inicializar array de archivos
if (!isset($_SESSION['archivos'])) {
    $_SESSION['archivos'] = array();
}

// Procesar imágenes subidas directamente (si las hay)
if(isset($_FILES['imagen']) && count($_FILES['imagen']['name']) > 0){
    $imagenes = $_FILES['imagen'];

    if(count($imagenes) > 0){
        foreach ($imagenes['error'] as $key => $error){
            if($error == UPLOAD_ERR_OK){
                $ext = $imagenes['type'][$key];
                $tam = isset($_POST['tamano'][$key]) ? $_POST['tamano'][$key] : '-';
                if ($tam == '-') {
                    $tam = $_POST['tamanogral'];
                }
                $can = isset($_POST['cantidad'][$key]) ? $_POST['cantidad'][$key] : 1;
                $aca = $_POST['acabadogral'];
                $nombre = $tam.'__'.$aca.'__'.$can.'__'.uniqid().extension($ext);
                $temp = $imagenes['tmp_name'][$key];
                $item = array();
                if (move_uploaded_file($temp, $dir.'/'.$nombre)) {
                    $item['archivo'] = 'img/impresiones/'.$nombre;
                    $item['acabado'] = $aca;
                    $item['tamano'] = $tam;
                    $item['cantidad'] = $can;
                    $_SESSION['archivos'][] = $item;
                }
            }
        }
    }
}

// Procesar imágenes de sesión temporal (las subidas por AJAX)
if(isset($_SESSION['archivostmp']) && count($_SESSION['archivostmp']) > 0){
    $imagenes = $_SESSION['archivostmp'];
    foreach ($imagenes as $id => $file){
        $tam = isset($_POST['tam-'.$id]) ? $_POST['tam-'.$id] : '0';
        if ($tam == '0') {
            $tam = $_POST['tamanogral'];
        }
        $can = isset($_POST['cant-'.$id]) ? $_POST['cant-'.$id] : 1;
        $aca = $_POST['acabadogral'];
        $nombre = $tam.'__'.$aca.'__'.$can.'__'.uniqid().extension($file['type']);
        $temp = __DIR__ . '/../' . $file['archivo'];

        $item = array();
        if (file_exists($temp) && copy($temp, $dir.'/'.$nombre)) {
            $item['archivo'] = 'img/impresiones/'.$nombre;
            $item['acabado'] = $aca;
            $item['tamano'] = $tam;
            $item['cantidad'] = $can;
            $_SESSION['archivos'][] = $item;

            // Eliminar archivo temporal
            unlink($temp);
            unset($_SESSION['archivostmp'][$id]);
        }
    }
}

// Verificar que se guardaron archivos
if (!isset($_SESSION['archivos']) || count($_SESSION['archivos']) == 0) {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>';
    echo '<script>alert("Error: No se pudieron procesar las imágenes. Asegúrese de haber subido al menos 4 fotos."); window.location.href = "../revelar-fotos";</script>';
    echo '</body></html>';
    exit;
}

// AGREGAR DIRECTAMENTE AL CARRITO (sin AJAX)
try {
    global $conectar;

    // Obtener ID del producto especial de revelado
    $queryProducto = $conectar->query("SELECT id FROM productos WHERE tipo_producto='revelado' LIMIT 1");
    if (!$queryProducto || $queryProducto->num_rows == 0) {
        echo '<script>alert("Error: Producto de revelado no encontrado. Ejecutar migración primero."); window.location.href = "../revelar-fotos";</script>';
        exit;
    }

    $productoRevelado = $queryProducto->fetch_assoc();
    $id_producto_revelado = $productoRevelado['id'];

    // Calcular el costo total del revelado basado en las impresiones
    $costototal = 0;
    $tama = array();
    foreach ($_SESSION['archivos'] as $key => $impre) {
        $tam = $impre['tamano'];
        $c = (int)$impre['cantidad'];
        if (isset($tama[$tam])) {
            $tama[$tam] = $tama[$tam] + $c;
        } else {
            $tama[$tam] = $c;
        }
    }

    foreach ($tama as $tam => $cant) {
        $query = "SELECT * FROM `impresiones` WHERE formato='$tam' AND desde<='$cant' AND hasta>='$cant' ORDER BY id DESC LIMIT 1";
        $calc = $conectar->query($query);
        if ($calc && $calc->num_rows > 0) {
            $row = $calc->fetch_assoc();
            $p = $row['precio'];
            $precio = ($p * $cant);
            $costototal = $costototal + $precio;
        }
    }

    // Preparar datos del carrito
    $hoy = time();
    $_SESSION['pronto']['cart_creado'] = $hoy;
    $_SESSION['pronto']['cart_expira'] = $hoy + 1800;

    // Contar total de fotos
    $total_fotos = 0;
    foreach ($_SESSION['archivos'] as $img) {
        $total_fotos += intval($img['cantidad']);
    }

    // Preparar resumen de impresiones para descripción
    $resumen_impresiones = [];
    foreach ($_SESSION['archivos'] as $img) {
        $key = $img['tamano'] . '_' . $img['acabado'];
        if (!isset($resumen_impresiones[$key])) {
            $resumen_impresiones[$key] = [
                'tamano' => $img['tamano'],
                'acabado' => $img['acabado'],
                'cantidad' => 0
            ];
        }
        $resumen_impresiones[$key]['cantidad'] += intval($img['cantidad']);
    }

    // Crear descripción
    $descripcion = '';
    foreach ($resumen_impresiones as $item) {
        $descripcion .= $item['cantidad'] . ' fotos ' . $item['tamano'] . ' ' . $item['acabado'] . ' | ';
    }
    $descripcion = rtrim($descripcion, ' | ');

    // Inicializar carrito si no existe
    if (!isset($_SESSION['pronto'])) {
        $_SESSION['pronto'] = array();
        error_log("=== INICIANDO SESSION['pronto'] ===");
    }

    if (!isset($_SESSION['pronto']['cart'])) {
        $_SESSION['pronto']['cart'] = array();
        error_log("=== INICIANDO SESSION['pronto']['cart'] ===");
    }

    error_log("=== AGREGANDO REVELADO AL CARRITO ===");
    error_log("ID Producto: $id_producto_revelado");
    error_log("Total fotos: $total_fotos");
    error_log("Precio total: $costototal");
    error_log("Imágenes: " . count($_SESSION['archivos']));

    // Agregar al carrito
    $_SESSION['pronto']['cart'][$id_producto_revelado] = [
        'color' => 'N/A',
        'cantidad' => $total_fotos,
        'cat' => 'revelado',
        'precio' => $costototal,
        'tipo' => 'revelado',
        'id_original' => $id_producto_revelado,
        'descripcion' => $descripcion,
        'datos_revelado' => [
            'imagenes' => $_SESSION['archivos']
        ]
    ];

    error_log("=== REVELADO AGREGADO EXITOSAMENTE ===");

    // Redirigir al carrito
    header('Location: ../index.php');
    exit;

} catch (Exception $e) {
    echo '<script>alert("Error: ' . $e->getMessage() . '"); window.location.href = "../revelar-fotos";</script>';
    exit;
}
