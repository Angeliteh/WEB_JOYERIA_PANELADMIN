# 🌸 Elena Martínez - Sistema de Gestión de Catálogo de Fragancias

## 📋 Resumen del Proyecto

Este proyecto transforma el sitio web estático de Elena Martínez (consultora en fragancias) en un **sistema dinámico** que le permite gestionar su catálogo de productos de forma autónoma, sin conocimientos técnicos.

### 🎯 Objetivo Principal
Permitir que Elena pueda **agregar, editar, eliminar y gestionar** sus productos de perfumes directamente desde un panel de administración web, manteniendo la apariencia y funcionalidad original del sitio para los visitantes.

---

## 🚀 ¿Qué hemos logrado?

### ✅ **Sistema Completo Implementado:**

#### 1. **Panel de Administración Profesional**
- **URL de acceso:** `tudominio.com/admin/`
- **Credenciales:** Usuario: `admin` / Contraseña: `admin`
- **Diseño:** Interfaz moderna con los colores de la marca (dorado/morado)
- **Responsive:** Funciona en computadora, tablet y móvil

#### 2. **Gestión Completa de Productos**
- ➕ **Agregar productos** con formulario intuitivo
- ✏️ **Editar productos** existentes
- 🗑️ **Eliminar productos** con confirmación
- 👁️ **Activar/Desactivar** productos (ocultar sin eliminar)
- 📊 **Dashboard** con estadísticas en tiempo real

#### 3. **Sistema de Imágenes Integrado**
- 📸 **Subida directa** de imágenes desde el formulario
- 🖼️ **Vista previa** inmediata
- 🔄 **Redimensionado automático** (optimización)
- ✅ **Validación** de formatos y tamaños
- 💾 **Almacenamiento seguro** en `data/uploads/`

#### 4. **Web Pública Dinámica**
- 🔄 **Carga automática** de productos desde el panel admin
- 👀 **Solo productos activos** visibles al público
- 🎨 **Misma apariencia** original mantenida
- 📱 **Responsive** y optimizada
- ⚡ **Tiempo real** - cambios inmediatos

---

## 🏗️ Arquitectura Técnica

### **Tecnologías Utilizadas:**
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend:** PHP 7.4+
- **Base de Datos:** JSON (sin MySQL necesario)
- **Imágenes:** Sistema de upload con redimensionado
- **Autenticación:** Sesiones PHP seguras

### **Estructura de Archivos:**
```
📁 Sitio Web/
├── 📄 index.html (web pública dinámica)
├── 📁 admin/ (panel de administración)
│   ├── 📄 index.php (login)
│   ├── 📄 dashboard.php (estadísticas)
│   ├── 📄 productos.php (gestión CRUD)
│   ├── 📄 auth.php (autenticación)
│   └── 📄 config.php (configuración)
├── 📁 api/ (conexión datos)
│   ├── 📄 productos.php (API pública)
│   └── 📄 upload.php (subida imágenes)
├── 📁 data/ (almacenamiento)
│   ├── 📄 productos.json (base de datos)
│   └── 📁 uploads/ (imágenes subidas)
├── 📁 css/ (estilos originales)
├── 📁 js/ (scripts originales)
└── 📁 images/ (imágenes originales)
```

---

## 🔄 Flujo de Funcionamiento

### **Para Elena (Administradora):**
1. **Accede** a `tudominio.com/admin/`
2. **Se loguea** con sus credenciales
3. **Ve dashboard** con estadísticas de sus productos
4. **Gestiona productos:**
   - Agrega nuevos perfumes con toda la información
   - Sube imágenes directamente
   - Edita precios, descripciones, etc.
   - Activa/desactiva productos según disponibilidad
5. **Los cambios** aparecen inmediatamente en la web pública

### **Para los Visitantes (Clientes):**
1. **Visitan** `tudominio.com/`
2. **Ven catálogo** actualizado automáticamente
3. **Solo productos activos** son visibles
4. **Misma experiencia** de navegación original
5. **Contactan** por WhatsApp para compras

---

## 📦 Instalación en Hosting

### **Requisitos del Hosting:**
- ✅ **PHP 7.4 o superior**
- ✅ **Extensiones:** GD (para imágenes), JSON
- ✅ **Permisos de escritura** en carpeta `data/`
- ❌ **NO requiere MySQL** ni base de datos

### **Pasos de Instalación:**

#### 1. **Subir Archivos**
```bash
# Subir toda la carpeta del proyecto al hosting
# Estructura final en el servidor:
public_html/
├── index.html
├── admin/
├── api/
├── data/
├── css/
├── js/
└── images/
```

#### 2. **Configurar Permisos**
```bash
# Dar permisos de escritura a la carpeta data
chmod 755 data/
chmod 755 data/uploads/
chmod 644 data/productos.json
```

#### 3. **Verificar Funcionamiento**
- ✅ Web pública: `tudominio.com/`
- ✅ Panel admin: `tudominio.com/admin/`
- ✅ API productos: `tudominio.com/api/productos.php`

#### 4. **Configuración de Seguridad (Opcional)**
```php
// En admin/config.php cambiar credenciales:
define('ADMIN_USER', 'elena');
define('ADMIN_PASS', 'contraseña_segura_aqui');
```

---

## 👩‍💼 Manual de Uso para Elena

### **Acceso al Panel:**
1. Ir a `tudominio.com/admin/`
2. Usuario: `admin` / Contraseña: `admin`
3. Hacer clic en "Iniciar Sesión"

### **Agregar Nuevo Producto:**
1. **Dashboard** → "Agregar Producto" (botón verde)
2. **Llenar información básica:**
   - Nombre del perfume
   - Precio en pesos mexicanos
   - Categoría (ej: "Floral Clásico")
   - Descripción detallada
3. **Subir imagen:**
   - Hacer clic en "Subir Imagen"
   - Seleccionar foto del perfume
   - Ver vista previa automática
4. **Completar notas olfativas:**
   - Notas de salida (primeras que se perciben)
   - Notas de corazón (principales del perfume)
   - Notas de fondo (que perduran más)
5. **Configurar detalles:**
   - Tamaños disponibles (ej: "50ml, 100ml")
   - Marcar "Producto activo" para que se vea en la web
6. **Guardar** → ¡El producto aparece inmediatamente en la web!

### **Editar Producto Existente:**
1. **Productos** → Hacer clic en ícono de editar (lápiz)
2. **Modificar** cualquier información
3. **Cambiar imagen** si es necesario
4. **Guardar cambios**

### **Ocultar Producto Temporalmente:**
1. **Productos** → Hacer clic en ícono de ojo
2. El producto se **desactiva** (no se elimina)
3. **No aparece** en la web pública
4. Se puede **reactivar** cuando quiera

### **Eliminar Producto Permanentemente:**
1. **Productos** → Hacer clic en ícono de basura
2. **Confirmar** eliminación
3. El producto se **borra completamente**

---

## 🔧 Mantenimiento y Soporte

### **Backup de Datos:**
- **Archivo principal:** `data/productos.json`
- **Imágenes:** Carpeta `data/uploads/`
- **Recomendación:** Descargar estos archivos periódicamente

### **Solución de Problemas Comunes:**

#### **"No puedo subir imágenes"**
- Verificar permisos de la carpeta `data/uploads/`
- Verificar que la imagen sea menor a 5MB
- Formatos permitidos: JPG, PNG, GIF, WEBP

#### **"Los cambios no se ven en la web"**
- Verificar que el producto esté marcado como "activo"
- Limpiar caché del navegador (Ctrl+F5)
- Verificar que el archivo `data/productos.json` tenga permisos de escritura

#### **"No puedo acceder al admin"**
- Verificar URL: `tudominio.com/admin/`
- Verificar credenciales: admin/admin
- Verificar que PHP esté funcionando en el hosting

---

## 📞 Información de Entrega

### **URLs Importantes:**
- **Sitio web público:** `https://tudominio.com/`
- **Panel de administración:** `https://tudominio.com/admin/`
- **API de productos:** `https://tudominio.com/api/productos.php`

### **Credenciales de Acceso:**
- **Usuario:** `admin`
- **Contraseña:** `admin`
- ⚠️ **Importante:** Cambiar credenciales después de la entrega

### **Archivos de Configuración:**
- **Configuración principal:** `admin/config.php`
- **Base de datos:** `data/productos.json`
- **Imágenes:** `data/uploads/`

### **Soporte Técnico:**
- **Documentación completa** incluida
- **Sistema probado** y funcionando
- **Interfaz intuitiva** para uso sin conocimientos técnicos

---

## ✅ Checklist de Entrega

- [x] **Sistema completo** desarrollado y probado
- [x] **Panel de administración** funcional
- [x] **Gestión de productos** CRUD completa
- [x] **Sistema de imágenes** integrado
- [x] **Web pública** dinámica funcionando
- [x] **Documentación** completa
- [x] **Manual de usuario** para Elena
- [x] **Instrucciones de instalación** en hosting
- [x] **Credenciales** configuradas
- [x] **Backup** de datos inicial

---

## 🎉 Resultado Final

Elena ahora tiene **control total** sobre su catálogo de fragancias con un sistema:
- **Fácil de usar** (como redes sociales)
- **Profesional** (diseño acorde a su marca)
- **Autónomo** (no necesita programador)
- **Escalable** (puede agregar tantos productos como quiera)
- **Seguro** (acceso protegido con contraseña)

**¡El sitio web estático se convirtió en una plataforma de gestión completa!** 🚀
