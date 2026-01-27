# Sistema de Generación de Guías Epresis

## 📋 Descripción

Sistema completo que permite generar guías de envío con Epresis **manualmente** desde el panel de administración para pedidos que utilizan este método de envío.

## 🚀 Instalación

### 1. Ejecutar Migración de Base de Datos

Acceder desde el navegador a:
```
http://tu-dominio.com/admin/inc/seed/migrate_epresis_guias.php
```

Esto creará:
- Tabla `epresis_guias` para almacenar las guías generadas
- Campo `metodo_envio_id` en tabla `pedidos`
- Campo `valor_gratis` en tabla `metodos_envio`

### 2. Configurar Valor de Envío Gratis

En el panel de administración, configurar el campo `valor_gratis` para Epresis (ID 2):
- Si el costo calculado ≤ `valor_gratis` → Envío GRATIS
- Por defecto se establece en $1000.00

### 3. Configurar Credenciales de Epresis

Editar el archivo `inc/epresis_guia.php` (líneas 95-97):
```php
$api_token = "TU_TOKEN_EPRESIS";
$sucursal = "TU_CODIGO_SUCURSAL";
$url = "https://api-produccion.epresis.com/api/v2/guias.json"; // URL de producción
```

## 📁 Archivos Creados/Modificados

### Nuevos Archivos:
1. **`inc/epresis_guia.php`** - Funciones para generar guías
   - `generarGuiaEpresis($pedido_id)` - Genera la guía
   - `obtenerGuiaEpresis($pedido_id)` - Consulta guía existente

2. **`admin/inc/generar_guia_ajax.php`** - Endpoint AJAX para generar guías

3. **`admin/epresis_guias.php`** - Panel para ver todas las guías generadas

4. **`admin/inc/get_guia_detalles.php`** - API para ver detalles de guías

5. **`admin/inc/seed/migrate_epresis_guias.php`** - Script de migración

6. **`admin/inc/seed/create_epresis_guias_table.sql`** - SQL de la tabla

### Archivos Modificados:

1. **`checkout_1.php`**
   - Obtiene `valor_gratis` de BD
   - Valida si el envío es gratis según monto
   - Envía datos de Epresis al siguiente paso

2. **`checkout_2.php`**
   - Procesa costo de Epresis (puede ser $0)
   - Guarda datos en sesión incluyendo `metodo_envio_id`
   - Incluye costo en el total

3. **`inc/crear_pago2.php`** y **`inc/crear_pedido2.php`**
   - Incluyen costo de envío en el total del pedido

4. **`admin/pedidos_detallePedido.php`**
   - Agrega sección "Guía de Envío Epresis"
   - Botón "Generar Guía" (solo para pedidos con Epresis)
   - Muestra información de guía si ya existe

## 🔄 Flujo Completo

### 1. Checkout - Usuario Final

```
Usuario selecciona Epresis en checkout_1
  ↓
Ingresa código postal de destino
  ↓
Sistema calcula costo con API Epresis
  ↓
Verifica si costo ≤ valor_gratis de BD
  ↓
Si SÍ: Muestra "GRATIS" (costo = $0)
Si NO: Muestra el costo calculado
  ↓
Usuario continúa a checkout_2
  ↓
Se guarda: costo, metodo_envio_id=2 en sesión
  ↓
Usuario completa pago
  ↓
Pedido se crea con metodo_envio_id=2
```

### 2. Panel Admin - Generación de Guía

```
Admin abre pedido en admin/pedidos_detallePedido.php?id=123
  ↓
Sistema detecta: metodo_envio_id = 2 (Epresis)
  ↓
Muestra sección "Guía de Envío Epresis"
  ↓
Si NO hay guía: Muestra botón "Generar Guía"
Si hay guía: Muestra datos de la guía
  ↓
Admin hace clic en "Generar Guía"
  ↓
AJAX POST a admin/inc/generar_guia_ajax.php
  ↓
Llama a generarGuiaEpresis($pedido_id)
  ↓
Obtiene datos del pedido y productos desde BD
  ↓
Construye payload para API Epresis
  ↓
POST a /api/v2/guias.json
  ↓
Recibe: {guia: 758813, remito: "PED-123", importe: 3}
  ↓
Guarda en tabla epresis_guias
  ↓
Retorna datos al frontend
  ↓
Muestra código de guía en pantalla
```

## 📊 Estructura de la Tabla epresis_guias

```sql
CREATE TABLE epresis_guias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL UNIQUE,
  codigo_guia VARCHAR(50) NOT NULL,
  importe DECIMAL(10,2) DEFAULT 0.00,
  remito VARCHAR(100),
  sub_zona_destino VARCHAR(100),
  zona VARCHAR(100),
  respuesta_json TEXT,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🔑 Funciones Principales

### `generarGuiaEpresis($pedido_id)`
Genera una guía de envío en Epresis para un pedido específico.

**Parámetros:**
- `$pedido_id` (int): ID del pedido

**Proceso:**
1. Verifica que el pedido use Epresis (metodo_envio_id = 2)
2. Verifica que no exista guía duplicada
3. Obtiene productos del pedido con dimensiones
4. Parsea dirección del cliente
5. Construye payload para API
6. Envía POST a `/api/v2/guias.json`
7. Guarda respuesta en BD

**Retorna:**
```php
{
  "success": true,
  "guia": "758813",
  "importe": 3,
  "remito": "PED-425140",
  "sub_zona_destino": "CABA",
  "zona": ""
}
```

### `obtenerGuiaEpresis($pedido_id)`
Obtiene información de una guía existente.

**Parámetros:**
- `$pedido_id` (int): ID del pedido

**Retorna:** Array con datos de la guía o null si no existe.

## 📦 Datos Enviados a API Epresis

```php
[
  'api_token' => 'TOKEN',
  'codigo_sucursal' => 'CODIGO',
  'codigo_servicio' => 'ESTANDAR',
  'internacional' => 0,
  'isInversa' => 0,
  'pago_en' => 'ORIGEN',
  'tipo_operacion' => 'ENTREGA',
  'is_urgente' => 0,
  'remito' => 'PED-123',
  'valor_declarado' => 5000.00,
  'comprador[destinatario]' => 'Juan Pérez',
  'comprador[calle]' => 'Av. Corrientes',
  'comprador[altura]' => '1234',
  'comprador[localidad]' => 'CABA',
  'comprador[provincia]' => 'Buenos Aires',
  'comprador[cp]' => '1001',
  'comprador[email]' => 'cliente@mail.com',
  'comprador[celular]' => '1234567890',
  'productos[0][bultos]' => 2,
  'productos[0][peso]' => 0.5,
  'productos[0][descripcion]' => 'Producto ABC',
  'productos[0][dimensiones][alto]' => 0.25,
  'productos[0][dimensiones][largo]' => 0.25,
  'productos[0][dimensiones][profundidad]' => 0.25
]
```

## 🎨 Vista en Panel Admin

### Detalle de Pedido con Epresis

**Si NO hay guía generada:**
```
┌──────────────────────────────────────────┐
│ Guía de Envío Epresis  [Generar Guía] │
├──────────────────────────────────────────┤
│ ℹ️ No se ha generado guía de envío      │
│   para este pedido. Haz clic en         │
│   "Generar Guía" para crear una.        │
└──────────────────────────────────────────┘
```

**Si hay guía generada:**
```
┌──────────────────────────────────────────┐
│ Guía de Envío Epresis                    │
├──────────────────────────────────────────┤
│ ✓ Guía Generada                          │
│                                          │
│ Código de Guía: 758813                   │
│ Remito: PED-425140                       │
│ Importe: $3.00                           │
│ Zona: CABA                               │
│                                          │
│ Generada el 27/01/2025 14:30            │
└──────────────────────────────────────────┘
```

### Panel de Guías (`admin/epresis_guias.php`)

Muestra listado completo de todas las guías con:
- ID, Pedido, Código de Guía, Remito
- Cliente, Importe, Zona, Fecha
- Botón para ver detalles en modal
- Estadísticas totales

## ⚠️ Consideraciones

### 1. API de Desarrollo vs Producción
- **Desarrollo**: `https://epresis-desa.epsared.com.ar/api/v2/guias.json`
- **Producción**: Cambiar a URL real de producción

### 2. Validación de Duplicados
El sistema previene crear guías duplicadas:
- Verifica si ya existe guía para el pedido
- Si existe, retorna la guía existente

### 3. Manejo de Errores
- Si falla la generación, muestra mensaje de error al admin
- No bloquea el pedido, se puede reintentar
- Errores se loguean en respuesta_json

### 4. Datos Requeridos

**Del Pedido:**
- Dirección completa con calle y altura
- CP válido
- Provincia y ciudad
- Teléfono/celular del cliente

**De los Productos:**
- Dimensiones: ancho, alto, profundidad (en metros)
- Peso (en kg)
- Valores por defecto si no están configurados:
  - Dimensiones: 0.25m x 0.25m x 0.25m
  - Peso: 0.5kg

### 5. Parseo de Dirección
El sistema intenta extraer automáticamente:
- Calle: "Av. Corrientes"
- Altura: "1234"

De direcciones como: "Av. Corrientes 1234" o "Av. Corrientes, 1234"

## 🔧 Configuración Paso a Paso

### 1. Ejecutar Migración
```
http://tu-sitio.com/admin/inc/seed/migrate_epresis_guias.php
```

### 2. Editar Credenciales
En `inc/epresis_guia.php`:
```php
$api_token = "TU_TOKEN_REAL";
$sucursal = "TU_CODIGO_SUCURSAL";
$url = "https://URL_PRODUCCION/api/v2/guias.json";
```

### 3. Configurar Productos
Asegurarse que cada producto tenga:
- `ancho` (decimal)
- `alto` (decimal)
- `profundidad` (decimal)
- `peso` (decimal)

### 4. Configurar Valor Gratis
En BD, tabla `metodos_envio`:
```sql
UPDATE metodos_envio
SET valor_gratis = 1500.00
WHERE id = 2;
```

### 5. Probar Flujo
1. Crear pedido de prueba con Epresis
2. Ir a admin/pedidos_detallePedido.php
3. Hacer clic en "Generar Guía"
4. Verificar que se genere correctamente

## 📞 Soporte

Para consultas sobre la API de Epresis, contactar con el soporte técnico de Epresis.

## ✅ Checklist de Implementación

- [ ] Ejecutar migración de BD
- [ ] Configurar valor_gratis para Epresis
- [ ] Actualizar credenciales API (token y sucursal)
- [ ] Cambiar URL de API a producción
- [ ] Configurar dimensiones y peso de productos
- [ ] Validar direcciones de clientes completas
- [ ] Probar generación de guía con pedido real
- [ ] Verificar que los datos se guarden en BD
- [ ] Revisar panel de guías generadas

## 🆕 Diferencias con Versión Anterior

**Antes:** Las guías se generaban automáticamente al confirmar el pago de Mercado Pago.

**Ahora:** Las guías se generan manualmente desde el panel de administración.

**Ventajas:**
- ✅ Mayor control sobre cuándo generar la guía
- ✅ Permite revisar el pedido antes de crear guía
- ✅ Evita generar guías de pedidos cancelados
- ✅ Admin puede revisar datos antes de enviar
- ✅ Flexibilidad para regenerar si hay errores

---

**Versión:** 2.0
**Fecha:** 2025-01-27
**Modalidad:** Generación Manual desde Admin
