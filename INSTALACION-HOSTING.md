# 🚀 Guía de Instalación en Hosting

## 📋 Checklist Pre-Instalación

### **Requisitos del Hosting:**
- [ ] **PHP 7.4 o superior** ✅
- [ ] **Extensión GD** (para procesamiento de imágenes) ✅
- [ ] **Extensión JSON** (incluida por defecto) ✅
- [ ] **Permisos de escritura** en directorios ✅
- [ ] **Espacio en disco** mínimo 100MB ✅

### **NO se requiere:**
- ❌ MySQL o base de datos
- ❌ Composer o dependencias especiales
- ❌ Node.js o npm
- ❌ Configuraciones complejas

---

## 📁 Estructura de Archivos a Subir

```
📦 Archivos a subir al hosting:
├── 📄 index.html (OBLIGATORIO)
├── 📁 admin/ (OBLIGATORIO)
│   ├── 📄 index.php
│   ├── 📄 dashboard.php
│   ├── 📄 productos.php
│   ├── 📄 auth.php
│   └── 📄 config.php
├── 📁 api/ (OBLIGATORIO)
│   ├── 📄 productos.php
│   └── 📄 upload.php
├── 📁 data/ (OBLIGATORIO)
│   ├── 📄 productos.json
│   └── 📁 uploads/ (vacía inicialmente)
├── 📁 css/ (del sitio original)
├── 📁 js/ (del sitio original)
├── 📁 images/ (del sitio original)
├── 📁 fonts/ (del sitio original)
├── 📄 contact-form.php (del sitio original)
├── 📄 privacy-policy.html (del sitio original)
├── 📄 terms.html (del sitio original)
├── 📄 robots.txt (del sitio original)
├── 📄 sitemap.xml (del sitio original)
└── 📄 favicon.svg (del sitio original)
```

---

## 🔧 Pasos de Instalación

### **PASO 1: Subir Archivos**

#### **Opción A: Panel de Control (cPanel/Plesk)**
1. **Acceder** al panel de control del hosting
2. **Ir** a "Administrador de Archivos" o "File Manager"
3. **Navegar** a la carpeta `public_html/` (o `www/`, `htdocs/`)
4. **Subir** todos los archivos del proyecto
5. **Extraer** si subiste un ZIP

#### **Opción B: FTP/SFTP**
```bash
# Conectar por FTP y subir a:
/public_html/
# o
/www/
# o 
/htdocs/
```

### **PASO 2: Configurar Permisos**

#### **Permisos Necesarios:**
```bash
# Carpetas (755):
chmod 755 data/
chmod 755 data/uploads/
chmod 755 admin/
chmod 755 api/

# Archivos (644):
chmod 644 data/productos.json
chmod 644 admin/*.php
chmod 644 api/*.php
chmod 644 index.html
```

#### **En cPanel:**
1. **Seleccionar** carpeta `data/`
2. **Clic derecho** → "Permisos" o "Permissions"
3. **Marcar:** Propietario: Leer, Escribir, Ejecutar (755)
4. **Aplicar** a subcarpetas
5. **Repetir** para `data/uploads/`

### **PASO 3: Verificar Instalación**

#### **URLs a Probar:**
1. **Sitio web:** `https://tudominio.com/`
   - ✅ Debe cargar la web con productos
   - ✅ Debe mostrar productos dinámicamente

2. **Panel admin:** `https://tudominio.com/admin/`
   - ✅ Debe mostrar formulario de login
   - ✅ Credenciales: admin/admin

3. **API:** `https://tudominio.com/api/productos.php`
   - ✅ Debe mostrar JSON con productos
   - ✅ Formato: `{"success":true,"total":X,"productos":[...]}`

### **PASO 4: Configuración de Seguridad**

#### **Cambiar Credenciales (RECOMENDADO):**
```php
// Editar: admin/config.php
// Líneas 6-7:
define('ADMIN_USER', 'elena');  // Cambiar 'admin'
define('ADMIN_PASS', 'mi_contraseña_segura_2024');  // Cambiar 'admin'
```

#### **Proteger Carpeta data/ (OPCIONAL):**
```apache
# Crear archivo: data/.htaccess
# Contenido:
Order Deny,Allow
Deny from all
<Files "*.jpg">
    Allow from all
</Files>
<Files "*.png">
    Allow from all
</Files>
<Files "*.gif">
    Allow from all
</Files>
<Files "*.webp">
    Allow from all
</Files>
```

---

## 🔍 Solución de Problemas

### **❌ Error: "No se pueden subir imágenes"**
**Causa:** Permisos incorrectos
**Solución:**
```bash
chmod 755 data/uploads/
# O desde cPanel: Permisos 755 en data/uploads/
```

### **❌ Error: "Los productos no se guardan"**
**Causa:** Sin permisos de escritura en productos.json
**Solución:**
```bash
chmod 644 data/productos.json
chmod 755 data/
```

### **❌ Error: "Página en blanco en admin"**
**Causa:** Error de PHP
**Solución:**
1. **Verificar** versión PHP (mínimo 7.4)
2. **Activar** extensión GD en panel de hosting
3. **Revisar** logs de error del hosting

### **❌ Error: "No carga la web principal"**
**Causa:** Archivo index.html no encontrado
**Solución:**
1. **Verificar** que index.html esté en la raíz
2. **Verificar** permisos 644 en index.html

### **❌ Error: "API devuelve error 500"**
**Causa:** Error en PHP o permisos
**Solución:**
1. **Verificar** que api/productos.php tenga permisos 644
2. **Verificar** que data/productos.json exista y tenga permisos

---

## 📞 Configuración de Dominio

### **Si el dominio es nuevo:**
1. **Apuntar** DNS a la IP del hosting
2. **Esperar** propagación (24-48 horas)
3. **Configurar** SSL/HTTPS en el hosting
4. **Verificar** que todas las URLs funcionen con HTTPS

### **Si migras de otro hosting:**
1. **Hacer backup** del sitio anterior
2. **Subir** archivos nuevos
3. **Probar** funcionamiento
4. **Cambiar** DNS cuando todo funcione

---

## ✅ Checklist Final

### **Verificación Completa:**
- [ ] **Web pública** carga correctamente
- [ ] **Productos** se muestran dinámicamente
- [ ] **Panel admin** accesible con credenciales
- [ ] **Dashboard** muestra estadísticas
- [ ] **Agregar producto** funciona
- [ ] **Subir imagen** funciona
- [ ] **Editar producto** funciona
- [ ] **Eliminar producto** funciona
- [ ] **Activar/desactivar** funciona
- [ ] **Cambios** se reflejan en web pública inmediatamente
- [ ] **Formulario de contacto** original funciona
- [ ] **WhatsApp** links funcionan
- [ ] **Responsive** en móvil funciona
- [ ] **SSL/HTTPS** configurado

### **Entrega al Cliente:**
- [ ] **URLs** de acceso proporcionadas
- [ ] **Credenciales** entregadas
- [ ] **Manual de uso** explicado
- [ ] **Backup inicial** realizado
- [ ] **Soporte** post-entrega acordado

---

## 📋 Información para el Cliente

### **URLs de Acceso:**
- **Sitio web:** `https://tudominio.com/`
- **Panel admin:** `https://tudominio.com/admin/`

### **Credenciales Iniciales:**
- **Usuario:** `admin`
- **Contraseña:** `admin`
- ⚠️ **Cambiar** después de la primera sesión

### **Archivos Importantes:**
- **Productos:** `data/productos.json` (hacer backup periódico)
- **Imágenes:** `data/uploads/` (hacer backup periódico)
- **Configuración:** `admin/config.php`

### **Soporte:**
- **Manual completo:** README.md
- **Documentación técnica:** Este archivo
- **Sistema probado** y funcionando al 100%

---

## 🎯 Resultado Esperado

Después de seguir esta guía, Elena tendrá:
- ✅ **Sitio web** funcionando con su dominio
- ✅ **Panel de administración** accesible
- ✅ **Control total** sobre su catálogo
- ✅ **Sistema seguro** y confiable
- ✅ **Autonomía completa** para gestionar productos

**¡El sistema estará listo para usar inmediatamente!** 🚀
