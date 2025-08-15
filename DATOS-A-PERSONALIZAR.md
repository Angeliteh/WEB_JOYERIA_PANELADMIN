# 🔧 DATOS A PERSONALIZAR ANTES DE PONER EN PRODUCCIÓN

## 📊 GOOGLE ANALYTICS Y TRACKING

### Google Analytics 4
**Archivo:** `index.html`  
**Líneas:** 39-62

```javascript
// CAMBIAR ESTOS IDs:
'GTM-XXXXXXX'     → Tu ID de Google Tag Manager real
'G-XXXXXXXXXX'    → Tu ID de Google Analytics 4 real
```

**Cómo obtenerlos:**
1. Ir a [Google Analytics](https://analytics.google.com)
2. Crear cuenta y propiedad
3. Copiar el ID que empieza por "G-"
4. Ir a [Google Tag Manager](https://tagmanager.google.com)
5. Crear cuenta y contenedor
6. Copiar el ID que empieza por "GTM-"

---

## 📧 FORMULARIO DE CONTACTO

### Email de destino
**Archivo:** `contact-form.php`  
**Línea:** 8

```php
$to_email = "hola@pixelstudio.es"; // CAMBIAR POR EMAIL REAL
```

### Configuración del servidor
- Verificar que el hosting tenga **PHP habilitado**
- Verificar que la función **mail()** esté disponible
- Configurar **SPF y DKIM** para evitar spam

---

## 🌐 DOMINIO Y URLs

### URLs principales
**Archivos:** `index.html`, `sitemap.xml`, `privacy-policy.html`, `terms.html`

```html
https://pixelstudio.es → https://tudominio.com
```

**Ubicaciones específicas:**
- `index.html` líneas: 16, 17, 73-75, 88-90
- `sitemap.xml` todas las URLs
- Schema markup en `index.html` líneas 78-115

---

## 📱 INFORMACIÓN DE CONTACTO

### Teléfono
**Archivo:** `index.html`  
**Línea:** 325

```html
📞 Teléfono: +34 963 456 789 → TU TELÉFONO REAL
```

### Email
**Archivo:** `index.html`  
**Línea:** 326

```html
✉️ Email: hola@pixelstudio.es → TU EMAIL REAL
```

### Dirección
**Archivo:** `index.html`  
**Línea:** 324

```html
📍 Dirección: Calle Colón 45, 3º B - 46004 Valencia, España
→ TU DIRECCIÓN REAL
```

---

## 🏢 DATOS DE LA EMPRESA

### Nombre de la empresa
**Archivos:** `index.html`, `privacy-policy.html`, `terms.html`

```html
Pixel Studio → NOMBRE REAL DE TU EMPRESA
```

### CIF/NIF
**Archivo:** `privacy-policy.html`  
**Línea:** 18

```html
CIF: B12345678 → TU CIF/NIF REAL
```

### Datos del Schema Markup
**Archivo:** `index.html`  
**Líneas:** 78-115

```json
{
  "name": "Pixel Studio",           → NOMBRE REAL
  "telephone": "+34963456789",      → TELÉFONO REAL
  "email": "hola@pixelstudio.es",   → EMAIL REAL
  "streetAddress": "Calle Colón 45, 3º B", → DIRECCIÓN REAL
  "addressLocality": "Valencia",    → CIUDAD REAL
  "postalCode": "46004",           → CÓDIGO POSTAL REAL
  "foundingDate": "2019",          → AÑO DE FUNDACIÓN REAL
  "numberOfEmployees": "3"         → NÚMERO DE EMPLEADOS REAL
}
```

---

## 🎨 IMÁGENES A REEMPLAZAR

### Logo
**Archivo:** `images/logo.png`
- Reemplazar por logo real de la empresa
- Tamaño recomendado: 200x60px
- Formato: PNG con fondo transparente

### Favicon
**Archivo:** `favicon.svg`
- Personalizar con logo/iniciales reales
- Mantener tamaño 32x32px

### Portfolio
**Carpeta:** `images/demo/`
```
portfolio-1.jpg → Proyecto real 1
portfolio-2.jpg → Proyecto real 2
portfolio-3.jpg → Proyecto real 3
portfolio-4.jpg → Proyecto real 4
portfolio-5.jpg → Proyecto real 5
portfolio-6.jpg → Proyecto real 6
```

### Equipo
**Carpeta:** `images/demo/`
```
author-2.jpg → Foto real del director creativo
author-4.jpg → Foto real del especialista en marketing
author-6.jpg → Foto real de la fotógrafa
```

### Open Graph
**Crear:** `images/og-image.jpg`
- Tamaño: 1200x630px
- Imagen representativa de la empresa
- Actualizar referencia en `index.html` línea 17

---

## 🔗 REDES SOCIALES (Para Fase 2)

### URLs a crear y actualizar
**Archivo:** `index.html` (Schema markup)

```json
"sameAs": [
  "https://www.instagram.com/pixelstudio.es",     → TU INSTAGRAM
  "https://www.linkedin.com/company/pixelstudio-valencia" → TU LINKEDIN
]
```

### Perfiles recomendados a crear:
- Instagram: @tunombre.es
- LinkedIn: /company/tu-empresa
- Facebook: /tu-empresa
- Twitter: @tu-empresa (opcional)

---

## 📋 CHECKLIST DE PERSONALIZACIÓN

### ✅ ANTES DE SUBIR A PRODUCCIÓN:

- [ ] **Google Analytics ID** actualizado
- [ ] **Google Tag Manager ID** actualizado
- [ ] **Email del formulario** cambiado
- [ ] **Teléfono de contacto** actualizado
- [ ] **Dirección física** actualizada
- [ ] **Nombre de empresa** en todos los archivos
- [ ] **CIF/NIF** actualizado
- [ ] **URLs del dominio** cambiadas
- [ ] **Logo real** subido
- [ ] **Imágenes del portfolio** reemplazadas
- [ ] **Fotos del equipo** reemplazadas
- [ ] **Imagen Open Graph** creada
- [ ] **Sitemap.xml** URLs actualizadas
- [ ] **Schema markup** datos actualizados

### ✅ DESPUÉS DE SUBIR:

- [ ] **Verificar formulario** funciona
- [ ] **Probar Google Analytics** recibe datos
- [ ] **Verificar en Google Search Console**
- [ ] **Comprobar velocidad** en PageSpeed Insights
- [ ] **Probar en móvil** y diferentes navegadores
- [ ] **Verificar SSL** certificado activo

---

## 🚨 IMPORTANTE

### Hosting requerido:
- **PHP 7.4+** habilitado
- **Función mail()** disponible
- **SSL certificado** incluido
- **Espacio suficiente** para imágenes

### Dominios recomendados:
- `.es` para España
- `.com` para internacional
- Evitar guiones y números
- Máximo 15 caracteres

### Emails corporativos:
- `hola@tudominio.es` (contacto general)
- `info@tudominio.es` (información)
- `admin@tudominio.es` (administración)

---

**💡 Tip:** Haz una copia de seguridad antes de cambiar cualquier dato y prueba todo en un entorno de desarrollo primero.
