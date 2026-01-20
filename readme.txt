####Cambios realizados

## 2026-01-19 - Implementación de campo Video para Productos

### Archivos modificados:

1. **admin/producto.php**
   - Agregado sección "Video del Producto" en el formulario de edición
   - Implementados dos métodos de carga de video:
     * Radio button "Subir Archivo": permite subir archivo de video desde el dispositivo
     * Radio button "URL": permite ingresar URL de video externo
   - Campo file input con accept="video/*" para validación de tipo de archivo
   - Campo text input para URLs de video
   - JavaScript para alternar entre las dos opciones (archivo/URL)
   - Detección automática del tipo de video actual al cargar el formulario
   - Muestra el nombre del archivo de video actual si existe

2. **admin/index.php**
   - Agregada misma sección de video en formulario de creación de producto nuevo
   - Radio buttons para seleccionar entre archivo o URL
   - JavaScript para manejar el cambio dinámico entre opciones
   - IDs únicos (sufijo "Nuevo") para evitar conflictos con producto.php

3. **admin/inc/guardar_producto.php**
   - Implementada lógica de procesamiento de video (líneas 26-42)
   - Verificación del tipo de video seleccionado (video_tipo)
   - Si es URL: guarda directamente el valor de video_url
   - Si es archivo:
     * Crea directorio img/videos/ si no existe (con permisos 0755)
     * Genera nombre único usando timestamp + rand() + extensión original
     * Mueve archivo subido a img/videos/
     * Guarda ruta relativa en BD: img/videos/[nombre_archivo]
   - Actualizado query INSERT para incluir campo `video` en tabla productos

4. **admin/inc/update_producto.php**
   - Implementada misma lógica de procesamiento de video para actualizaciones
   - Construcción dinámica de fragmento SQL para campo video
   - Solo actualiza campo video si se proporciona nuevo valor
   - Mantiene video anterior si no se sube nuevo archivo o URL

### Estructura de datos:
- Campo en tabla productos: `video` (TEXT o VARCHAR)
- Valores posibles:
  * URL completa (ej: https://ejemplo.com/video.mp4)
  * Ruta relativa (ej: img/videos/1737312000_video_12345.mp4)
  * Vacío si no tiene video

### Directorio creado:
- /img/videos/ - almacena archivos de video subidos

### Funcionalidades:
✅ Subir archivo de video desde dispositivo
✅ Ingresar URL de video externo
✅ Alternancia dinámica entre opciones
✅ Validación de tipo de archivo (solo video/*)
✅ Generación de nombres únicos para evitar colisiones
✅ Creación automática de directorio si no existe
✅ Persistencia de video en base de datos
✅ Edición y actualización de videos existentes

---

## 2026-01-19 - Implementación de campo Color para Imágenes de Productos

### Archivos modificados:

1. **admin/producto.php**
   - Agregado campo color picker para imágenes existentes (líneas 116-119)
   - Input type="color" para cada imagen con valor hexadecimal
   - Campo opcional que muestra el color actual si existe, sino #000000 por defecto
   - Nombre del campo: `color_imagen_existente[ID_IMAGEN]` para identificar cada imagen
   - Modificada función JavaScript `agregaImg()` (línea 418)
   - Agregado color picker en imágenes nuevas dinámicamente
   - Nombre del campo: `color_imagen_nueva[]` para nuevas imágenes

2. **admin/inc/update_producto.php**
   - Implementada actualización de colores de imágenes existentes (líneas 53-60)
   - Loop foreach para actualizar campo `color` en tabla `imagenes` por ID
   - Agregado campo color al insertar nuevas imágenes (líneas 76-80, 86)
   - Query INSERT actualizado: `INSERT INTO imagenes (id_producto, imagen, color) VALUES (...)`
   - Campo color es opcional, se guarda vacío si no se proporciona

### Estructura de datos:
- Campo en tabla imagenes: `color` (VARCHAR)
- Formato: Hexadecimal (ej: #FF5733, #000000, #FFFFFF)
- Valor por defecto: Vacío o #000000

### Funcionalidades:
✅ Color picker HTML5 (input type="color") para selección visual
✅ Almacenamiento en formato hexadecimal
✅ Campo opcional (no obligatorio)
✅ Edición de color en imágenes existentes
✅ Asignación de color a nuevas imágenes
✅ Sincronización de colores con el formulario mediante arrays indexados
✅ Actualización independiente de cada imagen

### Uso del campo:
- Las imágenes existentes muestran su color actual
- Al agregar nueva imagen, se puede seleccionar un color
- El color se envía junto con la imagen en el submit del formulario
- Se almacena en la base de datos en la tabla `imagenes`

