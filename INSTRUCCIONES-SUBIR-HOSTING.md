# 📝 INSTRUCCIONES PARA SUBIR SITIO A HOSTING

## 🎯 **OPCIÓN ELEGIDA: Mantener JSON (Simple y funcional)**

---

## 📦 **ARCHIVOS A SUBIR**

### **1. Estructura completa a subir a `/htdocs`:**

```
📁 htdocs/
├── index.html ✅
├── contact-form.php ✅
├── LOGO_CON_IMAGEN.jpeg ✅
├── 📁 css/
│   ├── bootstrap.min.css
│   └── style.css
├── 📁 js/
│   ├── bootstrap.min.js
│   ├── jquery-2.1.1.js
│   └── scripts.js
├── 📁 images/
│   └── (todas las imágenes del sitio)
├── 📁 data/
│   ├── productos.json ✅
│   └── 📁 uploads/
│       ├── anillo1.jpg
│       ├── anillo2.jpg
│       ├── anillo3.jpg
│       └── anillo4.jpg
└── ❌ NO subir carpeta admin/ (no funcionará en InfinityFree)
```

---

## 🚀 **PASOS PARA SUBIR A INFINITYFREE**

### **Paso 1: Crear cuenta**
1. Ir a: https://infinityfree.net
2. Click en "Create Account"
3. Elegir subdominio: `joyeriamatt.epizy.com` (o el que prefieras)
4. Completar registro

### **Paso 2: Acceder al cPanel**
1. Login en InfinityFree
2. Click en "Manage"
3. Click en "Control Panel"

### **Paso 3: Subir archivos**
1. En cPanel → "File Manager"
2. Navegar a carpeta `/htdocs`
3. **BORRAR** todo lo que esté ahí por defecto
4. Subir todos los archivos del proyecto (excepto carpeta `admin/`)

**Métodos de subida:**
- **Opción A:** Arrastrar archivos en File Manager
- **Opción B:** Usar FTP con FileZilla (mejor para muchos archivos)

### **Paso 4: Configurar permisos**
1. Click derecho en carpeta `data/`
2. "Change Permissions"
3. Establecer: `755`

### **Paso 5: Probar el sitio**
Abrir en navegador:
```
http://joyeriamatt.epizy.com
```

---

## ✅ **QUÉ FUNCIONARÁ EN INFINITYFREE**

- ✅ Sitio público completo
- ✅ Galería con 4 productos
- ✅ Formulario de contacto (emails a: danielillocontreras@gmail.com)
- ✅ Modo oscuro/claro
- ✅ Responsive (móvil, tablet, desktop)
- ✅ Filtros de categorías
- ✅ Modales de productos
- ✅ Testimonios
- ✅ FAQ

---

## ❌ **QUÉ NO FUNCIONARÁ EN INFINITYFREE**

- ❌ Panel de administración
- ❌ Agregar productos nuevos
- ❌ Editar productos existentes
- ❌ Subir imágenes nuevas

**Solución:** Mostrar el admin funcionando en localhost (tu computadora) durante videollamada con cliente.

---

## 🎬 **GUION PARA PRESENTAR AL CLIENTE**

### **Mensaje inicial:**
```
Hola Daniel,

Te comparto el link de tu sitio web funcionando:
👉 http://joyeriamatt.epizy.com

Este es el diseño final con 4 productos de ejemplo para que veas 
cómo se verá tu catálogo completo.

Características:
✅ Diseño moderno y profesional
✅ Modo oscuro/claro
✅ Responsive (se adapta a celular)
✅ Formulario de contacto funcional
✅ Integración con WhatsApp

Los productos son de ejemplo. Cuando apruebes el diseño y 
contratemos el hosting definitivo ($3-5 USD/mes), tendrás 
acceso al panel de administración donde podrás:

📸 Subir tus propias fotos
✏️ Agregar/editar productos
💰 Cambiar precios
📝 Modificar descripciones

¿Te parece bien si agendamos una videollamada para mostrarte 
el panel de administración funcionando?
```

### **Durante la videollamada:**
1. Mostrar sitio público en InfinityFree
2. Compartir pantalla con localhost
3. Mostrar panel admin funcionando:
   - Agregar un producto nuevo
   - Subir una imagen
   - Editar un producto
   - Mostrar cómo aparece en el sitio
4. Explicar que en hosting de pago funcionará igual

---

## 🌐 **MIGRACIÓN A HOSTINGER (PRODUCCIÓN)**

### **Cuando el cliente apruebe:**

**Paso 1: Contratar Hostinger**
- Plan recomendado: Premium Hosting ($2.99/mes)
- Incluye: SSL gratis, email profesional, soporte 24/7

**Paso 2: Subir archivos**
1. Acceder a cPanel de Hostinger
2. File Manager → `/public_html`
3. Subir **TODOS** los archivos (incluyendo carpeta `admin/`)

**Paso 3: Configurar permisos**
```
Carpeta data/ → 755
Carpeta data/uploads/ → 755
Archivo data/productos.json → 644
```

**Paso 4: Configurar email**
1. En contact-form.php cambiar:
   ```php
   $to_email = "danielillocontreras@gmail.com";
   ```

**Paso 5: Probar todo**
- ✅ Sitio público
- ✅ Formulario de contacto
- ✅ Panel admin (login: admin / admin)
- ✅ Agregar producto
- ✅ Subir imagen

**Paso 6: Cambiar credenciales admin**
En `admin/config.php`:
```php
$valid_username = "admin"; // Cambiar
$valid_password = "admin"; // Cambiar
```

---

## 📋 **CHECKLIST ANTES DE ENTREGAR**

### **Información actualizada:**
- [x] Teléfono: 6565563197
- [x] Email: danielillocontreras@gmail.com
- [x] Horario: 8 AM - 8 PM
- [ ] Redes sociales (URLs completas de FB e IG)
- [ ] Productos reales con fotos del cliente
- [ ] Precios reales (pendiente)

### **Configuración técnica:**
- [ ] SSL activado (https)
- [ ] Email de contacto funcionando
- [ ] Google Analytics configurado (opcional)
- [ ] Backup de archivos
- [ ] Credenciales admin cambiadas

---

## 🔐 **CREDENCIALES IMPORTANTES**

### **InfinityFree (Demo):**
- URL: http://joyeriamatt.epizy.com
- cPanel: (guardar usuario y contraseña)
- FTP: (guardar datos si usas FileZilla)

### **Hostinger (Producción):**
- URL: (dominio definitivo)
- cPanel: (guardar usuario y contraseña)
- Admin sitio: admin / admin (CAMBIAR después)

---

## 💡 **NOTAS IMPORTANTES**

1. **InfinityFree es SOLO para demo**
   - No usar para producción
   - Tiene limitaciones de escritura
   - Puede tener downtime

2. **Hostinger es para producción**
   - Más estable y rápido
   - Permite admin completo
   - Soporte técnico 24/7

3. **Backup regular**
   - Descargar `data/productos.json` cada semana
   - Descargar carpeta `data/uploads/` con imágenes
   - Guardar en Google Drive o similar

4. **Capacitación al cliente**
   - Mostrar cómo agregar productos
   - Explicar cómo subir imágenes (máx 5MB)
   - Enseñar a editar/eliminar productos
   - Recordar hacer backup de imágenes

---

## 🆘 **SOLUCIÓN DE PROBLEMAS**

### **Error: "Page not found"**
- Verificar que archivos estén en `/htdocs` (InfinityFree)
- Verificar que archivos estén en `/public_html` (Hostinger)

### **Error: "Permission denied"**
- Cambiar permisos de carpeta `data/` a 755

### **Formulario no envía emails**
- Verificar que email en `contact-form.php` sea correcto
- Verificar que servidor permita función `mail()` de PHP

### **Admin no funciona en InfinityFree**
- Normal, es limitación del hosting gratuito
- Funcionará en Hostinger

### **Imágenes no se ven**
- Verificar rutas en `productos.json`
- Verificar que imágenes estén en `data/uploads/`
- Verificar permisos de carpeta uploads

---

## 📞 **CONTACTO DE SOPORTE**

**InfinityFree:**
- Forum: https://forum.infinityfree.net

**Hostinger:**
- Chat 24/7 en panel de control
- Email: support@hostinger.com

---

## ✅ **RESUMEN RÁPIDO**

1. **Demo (InfinityFree):**
   - Subir archivos sin carpeta `admin/`
   - Mostrar sitio funcionando
   - Mostrar admin en localhost

2. **Producción (Hostinger):**
   - Subir archivos completos con `admin/`
   - Configurar permisos
   - Cambiar credenciales
   - Entregar al cliente

3. **Mantenimiento:**
   - Backup semanal
   - Actualizar productos según cliente
   - Monitorear emails de contacto

---

**Última actualización:** Octubre 28, 2025
