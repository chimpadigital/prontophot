# 📋 ESTRATEGIA DE INTEGRACIÓN: Revelado + Carrito

## ✅ IMPLEMENTADO

### 1. Migración de Base de Datos
**Archivo**: `admin/inc/seed/add_tipo_producto_revelado.php`

**Ejecutar**: Acceder a `http://tu-dominio.com/admin/inc/seed/add_tipo_producto_revelado.php`

**Qué hace**:
- ✅ Agrega campo `tipo_producto` VARCHAR(50) en tabla `productos`
- ✅ Crea producto especial "Revelado de Fotos" con `tipo_producto='revelado'`
- ✅ Agrega campo `tipo` VARCHAR(50) en tabla `pedidos_detalle`
- ✅ Guarda el ID del producto revelado (anótalo, lo necesitarás)

### 2. Endpoint para Agregar Revelado al Carrito
**Archivo**: `inc/agregar_revelado_carrito.php`

**Qué hace**:
- ✅ Obtiene el ID del producto revelado de la BD
- ✅ Calcula el total de fotos y costo
- ✅ Agrega al carrito `$_SESSION['pronto']['cart']` con estructura especial:
  ```php
  [
      'tipo' => 'revelado',
      'cantidad' => total_fotos,
      'precio' => costo_calculado,
      'datos_revelado' => [
          'imagenes' => $_SESSION['archivos'],
          'metodo_envio_id' => ...,
          'epresis_fecha' => ...,
          'costo_envio' => ...
      ]
  ]
  ```

---

## 🔨 PENDIENTE DE IMPLEMENTACIÓN

### 3. Modificar `revelado_3.php`

**Ubicación**: Líneas 595-599

**Cambio**: Agregar botón "Agregar al Carrito" antes del botón "Pagar"

```php
<div class="row p-0">
    <div class="col-md-12 p-0 m-0 mb-2">
        <button type="button" class="btn btn-outline-danger btn-lg btn-block rounded btn-agregar-carrito">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus mb-1 mr-1" viewBox="0 0 16 16">
                <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9V5.5z"/>
                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg>
            Agregar al Carrito
        </button>
    </div>
    <div class="col-md-12 p-0 m-0 mb-5">
        <button type="button" class="btn btn-success btn-lg btn-block rounded-bottom btn-pagar">Pagar Ahora</button>
    </div>
</div>
```

**JavaScript**: Agregar antes de la línea 837 (antes del cierre de `$(function()`):

```javascript
// Botón agregar al carrito
$('.btn-agregar-carrito').click(function(e){
    e.preventDefault();
    var btn = $(this);
    btn.html('<i class="fa fa-spin fa-spinner"></i> Agregando...');
    btn.prop('disabled', true);

    $.post('inc/agregar_revelado_carrito.php', {}, function(data){
        if(data.success){
            alert('¡Revelado agregado al carrito! Puedes seguir comprando o ir a pagar.');
            window.location.href = 'miCarro';
        } else {
            alert('Error: ' + data.msg);
            btn.html('Agregar al Carrito');
            btn.prop('disabled', false);
        }
    }, 'json').fail(function(){
        alert('Error al agregar al carrito');
        btn.html('Agregar al Carrito');
        btn.prop('disabled', false);
    });
});
```

---

### 4. Modificar `miCarro.php`

**Ubicación**: Líneas 46-89 (dentro del foreach del carrito)

**Cambio**: Detectar si el producto es de tipo revelado y mostrar diseño especial

```php
foreach($carro as $id=>$datos){
    // VERIFICAR SI ES REVELADO
    if(isset($datos['tipo']) && $datos['tipo'] == 'revelado'){
        // DISEÑO ESPECIAL PARA REVELADO
        $imagenes = $datos['datos_revelado']['imagenes'];
        $total_fotos = 0;
        $resumen = [];

        foreach($imagenes as $img){
            $total_fotos += intval($img['cantidad']);
            $key = $img['tamano'] . ' ' . $img['acabado'];
            if(!isset($resumen[$key])){
                $resumen[$key] = 0;
            }
            $resumen[$key] += intval($img['cantidad']);
        }
        ?>
        <div class="row mb-4 p-3 bg-light">
            <div class="col-md-12">
                <h5 class="text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-images mb-1" viewBox="0 0 16 16">
                        <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                        <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                    </svg>
                    Revelado de Fotos
                </h5>
                <p class="mb-2"><strong>Total: <?php echo $total_fotos; ?> fotografías</strong></p>

                <div class="mb-2">
                    <small class="text-muted">Detalle:</small>
                    <?php foreach($resumen as $tipo => $cantidad): ?>
                        <div><small>• <?php echo $cantidad . ' fotos ' . $tipo; ?></small></div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <a href="revelado_1.php" class="btn btn-sm btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil mb-1" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                            Editar Revelado
                        </a>
                        <button data-id="<?php echo $id; ?>" type="button" class="btn btn-sm btn-link text-danger btn-remove">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash-fill mb-1" viewBox="0 0 16 16">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                    <p class="mb-0"><strong>$<?php echo number_format($datos['precio'], 2); ?></strong></p>
                </div>
            </div>
        </div>
        <?php
        continue; // Saltar al siguiente item
    }

    // DISEÑO NORMAL PARA PRODUCTOS (código actual sin cambios)
    $res=$conectar->query("SELECT p.nombre,p.descripcion, p.precio, p.descuento_final,(SELECT imagen FROM `imagenes` WHERE id_producto='$id' ORDER BY id ASC LIMIT 1) as imagen FROM productos p  WHERE p.id='$id' ");
    // ... resto del código actual ...
}
```

---

### 5. Modificar `inc/crear_pedido2.php`

**Ubicación**: Líneas 85-120 (procesamiento de items del carrito)

**Cambio**: Agregar lógica para procesar revelado

```php
foreach($carro as $id=>$datos){
    // VERIFICAR SI ES REVELADO
    if(isset($datos['tipo']) && $datos['tipo'] == 'revelado'){
        // Obtener ID original del producto
        $id_real = isset($datos['id_original']) ? $datos['id_original'] : $id;

        // Insertar en pedidos_detalle
        $conectar->query("INSERT INTO `pedidos_detalle`
            (`id_pedido`, `id_producto`, `cantidad`, `precio`, `tipo`)
            VALUES ('$idpedido', '$id_real', '".$datos['cantidad']."', '".$datos['precio']."', 'revelado')");

        // Insertar cada imagen en pedidos_imagenes
        foreach($datos['datos_revelado']['imagenes'] as $imagen){
            $archivo = $conectar->real_escape_string($imagen['archivo']);
            $acabado = $conectar->real_escape_string($imagen['acabado']);
            $tamano = $conectar->real_escape_string($imagen['tamano']);
            $cantidad = intval($imagen['cantidad']);
            $idimpresion = intval($imagen['idimpresion']);

            $conectar->query("INSERT INTO `pedidos_imagenes`
                (`id_pedido`, `archivo`, `acabado`, `tamano`, `cantidad`, `idimpresion`)
                VALUES ('$idpedido', '$archivo', '$acabado', '$tamano', '$cantidad', '$idimpresion')");
        }

        // Agregar item para notificación
        $item = [];
        $item['producto'] = 'Revelado de Fotos (' . $datos['cantidad'] . ' fotos)';
        $item['cantidad'] = $datos['cantidad'];
        $item['precio'] = $datos['precio'];
        $items[] = $item;

        continue; // Saltar al siguiente
    }

    // PROCESAR PRODUCTO NORMAL (código actual sin cambios)
    $res=$conectar->query("SELECT p.nombre,p.descripcion, p.precio, p.descuento_final,(SELECT imagen FROM `imagenes` WHERE id_producto='$id' ORDER BY id ASC LIMIT 1) as imagen FROM productos p  WHERE p.id='$id' ");
    // ... resto del código actual ...
}
```

---

### 6. Crear función de cálculo de peso para Epresis

**Archivo NUEVO**: `inc/calcular_peso_epresis.php`

```php
<?php
function calcularPesoTotalEpresis($carro) {
    $peso_total = 0;

    foreach($carro as $id => $datos){
        if(isset($datos['tipo']) && $datos['tipo'] == 'revelado'){
            // Contar total de fotos
            $cantidad_fotos = intval($datos['cantidad']);

            // REGLAS DE PESO POR CANTIDAD (ajustar según necesidad)
            if($cantidad_fotos <= 10){
                $peso_total += $cantidad_fotos * 10; // 10g por foto
            } elseif($cantidad_fotos <= 50){
                $peso_total += 450; // Pack de 50
            } elseif($cantidad_fotos <= 100){
                $peso_total += 850; // Pack de 100
            } else {
                $peso_total += 850 + (($cantidad_fotos - 100) * 8); // Base + extra
            }
        } else {
            // Peso de productos normales
            // AGREGAR LÓGICA SI LOS PRODUCTOS TIENEN CAMPO PESO
            // Por ahora asumir 200g por producto
            $peso_total += 200 * intval($datos['cantidad']);
        }
    }

    return $peso_total;
}
```

---

### 7. Modificar `checkout_2.php` (si hay productos en carrito)

**Cambio**: Al calcular costo de Epresis, incluir peso del revelado

**Ubicación**: Donde se llama a la API de Epresis

```php
// Incluir función de cálculo de peso
include_once 'inc/calcular_peso_epresis.php';

// Calcular peso total del carrito
$peso_total = calcularPesoTotalEpresis($_SESSION['pronto']['cart']);

// Usar $peso_total en la petición a Epresis
```

---

## 🎯 FLUJO DE USUARIO FINAL

1. Usuario sube fotos → revelado_1.php
2. Selecciona tamaños y acabados → revelado_2.php
3. Selecciona método de envío → revelado_3.php
4. **NUEVO**: Usuario tiene 2 opciones:
   - **"Agregar al Carrito"**: Agrega revelado y va a `miCarro.php` (puede seguir comprando)
   - **"Pagar Ahora"**: Flujo actual (directo a pago)
5. En carrito: Puede ver revelado + productos juntos
6. Checkout unificado para todo
7. Pedido se crea con ambos tipos de items

---

## 📝 NOTAS IMPORTANTES

- ✅ El flujo de 4 pasos del revelado se mantiene intacto
- ✅ Los datos se guardan en `$_SESSION['archivos']` hasta completar pedido
- ✅ El carrito permite mezclar revelado + productos
- ✅ Epresis calcula envío considerando peso total
- ⚠️ Al eliminar revelado del carrito, NO borrar `$_SESSION['archivos']` (permitir reeditar)
- ⚠️ Limpiar ambas sesiones (`archivos` y `cart`) solo al completar pedido exitosamente

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

- [x] Ejecutar migración `add_tipo_producto_revelado.php`
- [x] Verificar ID del producto revelado creado
- [x] Endpoint `agregar_revelado_carrito.php` creado
- [ ] Modificar revelado_3.php (botón + JavaScript)
- [ ] Modificar miCarro.php (diseño especial revelado)
- [ ] Modificar inc/crear_pedido2.php (procesar revelado)
- [ ] Crear inc/calcular_peso_epresis.php
- [ ] Modificar checkout para usar cálculo de peso
- [ ] Probar flujo completo: revelado → carrito → productos → checkout → pago
- [ ] Verificar que pedidos antiguos sigan funcionando

---

**IMPORTANTE**: Antes de implementar en producción, probar en ambiente de desarrollo/local.
