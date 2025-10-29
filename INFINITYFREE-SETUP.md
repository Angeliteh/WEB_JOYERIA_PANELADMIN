# 🚀 GUÍA: Subir a InfinityFree

## 📋 RESUMEN RÁPIDO

**¿Funcionará todo en InfinityFree?**
✅ **SÍ**, el sistema está diseñado para funcionar perfectamente en InfinityFree.

**¿Necesitamos permisos especiales?**
✅ **NO**, InfinityFree ya tiene los permisos necesarios por defecto.

**¿Las imágenes se subirán automáticamente?**
✅ **SÍ**, el sistema crea la carpeta y sube las imágenes automáticamente.

**¿Las referencias serán correctas?**
✅ **SÍ**, todo se referencia automáticamente desde MySQL.

---

## 🎯 CÓMO FUNCIONA EL SISTEMA

### **1. Cliente sube imagen desde admin:**
```
Cliente → Productos → Agregar/Editar
    ↓
Selecciona imagen desde su computadora
    ↓
PHP valida la imagen (tipo, tamaño)
    ↓
PHP crea carpeta data/uploads/ (si no existe)
    ↓
PHP genera nombre único: anillo-oro-1698765432.jpg
    ↓
PHP guarda imagen en: data/uploads/anillo-oro-1698765432.jpg
    ↓
PHP guarda ruta en MySQL: "data/uploads/anillo-oro-1698765432.jpg"
    ↓
✅ Producto guardado con su imagen
```

### **2. Frontend muestra el producto:**
```
Usuario visita el sitio web
    ↓
JavaScript carga: api/productos.php
    ↓
API consulta MySQL: SELECT * FROM productos WHERE activo = 1
    ↓
MySQL devuelve: {imagen: "data/uploads/anillo-oro-1698765432.jpg"}
    ↓
HTML: <img src="data/uploads/anillo-oro-1698765432.jpg">
    ↓
✅ Imagen se muestra correctamente
```

---

## 🔐 PERMISOS EN INFINITYFREE

### **¿Qué permisos necesitamos?**

InfinityFree ya tiene configurados los permisos correctos:

✅ **Lectura** - Para leer archivos PHP, HTML, CSS, JS
✅ **Escritura** - Para crear carpetas y subir imágenes
✅ **Ejecución** - Para ejecutar scripts PHP

### **¿Necesitamos configurar algo?**

**NO.** El código ya maneja todo automáticamente:

```php
// En productos.php línea 23-25
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);  // Crea carpeta con permisos correctos
}
```

**Explicación:**
- `0755` = Permisos estándar (lectura/escritura/ejecución)
- `true` = Crea carpetas padre si no existen
- InfinityFree permite esto por defecto

---

## 📁 ESTRUCTURA DE ARCHIVOS EN INFINITYFREE

### **Cómo se verá en el servidor:**

```
htdocs/                              ← Raíz de tu sitio
│
├── 📄 index.html                    ← Sitio web principal
├── 📄 .htaccess                     ← Configuración (opcional)
│
├── 📁 admin/                        ← Panel de administración
│   ├── index.php                    ← Login
│   ├── dashboard.php                ← Dashboard
│   ├── productos.php                ← Gestión de productos
│   ├── database.php                 ← Conexión MySQL
│   └── auth.php                     ← Autenticación
│
├── 📁 api/                          ← API para el frontend
│   └── productos.php                ← Devuelve productos en JSON
│
├── 📁 data/                         ← Datos del sistema
│   └── 📁 uploads/                  ← Imágenes subidas
│       ├── anillo-oro-1698765432.jpg
│       ├── argollas-1698765433.jpg
│       └── compromiso-1698765434.jpg
│
├── 📁 css/                          ← Estilos
├── 📁 js/                           ← JavaScript
└── 📁 images/                       ← Imágenes del diseño
```

---

## 🗄️ CONFIGURACIÓN DE MYSQL EN INFINITYFREE

### **Paso 1: Crear Base de Datos**

1. Entra a tu panel de InfinityFree
2. Ve a **"MySQL Databases"**
3. Click en **"Create Database"**
4. Anota estos datos:
   ```
   Database Name: epiz_12345678_joyeria
   Database User: epiz_12345678
   Database Password: [tu contraseña]
   Database Host: sql123.infinityfree.com
   ```

### **Paso 2: Crear Tablas**

1. Click en **"phpMyAdmin"**
2. Selecciona tu base de datos
3. Ve a la pestaña **"SQL"**
4. Copia y pega el contenido de `database/schema.sql`
5. Click **"Go"**
6. ✅ Tablas creadas

### **Paso 3: Configurar Conexión**

Edita `admin/database.php` líneas 18-33:

```php
// PRODUCCIÓN (InfinityFree)
if (!IS_LOCAL) {
    define('DB_HOST', 'sql123.infinityfree.com');      // ← Cambiar
    define('DB_USER', 'epiz_12345678');                // ← Cambiar
    define('DB_PASS', 'tu_contraseña_aqui');           // ← Cambiar
    define('DB_NAME', 'epiz_12345678_joyeria');        // ← Cambiar
}
```

---

## 📤 SUBIR ARCHIVOS A INFINITYFREE

### **Opción 1: FileZilla (Recomendado)**

1. **Descargar FileZilla:**
   - https://filezilla-project.org/

2. **Conectar a InfinityFree:**
   ```
   Host: ftpupload.net
   Username: epiz_12345678  (tu usuario de InfinityFree)
   Password: [tu contraseña FTP]
   Port: 21
   ```

3. **Subir archivos:**
   - Panel izquierdo: Tu computadora
   - Panel derecho: Servidor (carpeta `htdocs`)
   - Arrastra todos los archivos del proyecto a `htdocs`

4. **Verificar estructura:**
   ```
   htdocs/
   ├── index.html
   ├── admin/
   ├── api/
   ├── css/
   ├── js/
   └── images/
   ```

### **Opción 2: File Manager (Panel de InfinityFree)**

1. Ve a **"File Manager"**
2. Navega a `htdocs`
3. Sube archivos uno por uno o en ZIP
4. Si subes ZIP, descomprímelo en el servidor

---

## ✅ VERIFICACIÓN POST-INSTALACIÓN

### **1. Verificar sitio web:**
```
http://tu-sitio.infinityfree.com/
```
✅ Debe mostrar tu sitio web

### **2. Verificar API:**
```
http://tu-sitio.infinityfree.com/api/productos.php
```
✅ Debe devolver JSON con productos

### **3. Verificar admin:**
```
http://tu-sitio.infinityfree.com/admin/
```
✅ Debe mostrar página de login

### **4. Probar login:**
```
Usuario: admin
Contraseña: admin
```
✅ Debe entrar al dashboard

### **5. Probar subida de imagen:**
1. Admin → Productos → Agregar Producto
2. Llenar formulario
3. Seleccionar imagen
4. Guardar
5. ✅ Producto debe aparecer con su imagen

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### **Problema 1: Error de conexión a MySQL**

**Síntoma:** "Error al conectar a la base de datos"

**Solución:**
1. Verifica credenciales en `admin/database.php`
2. Asegúrate de usar los datos correctos de InfinityFree
3. Verifica que la base de datos esté creada

### **Problema 2: No se suben imágenes**

**Síntoma:** "Error al subir la imagen"

**Solución:**
1. Verifica que la carpeta `data/uploads/` exista
2. Si no existe, créala manualmente en FileZilla
3. Permisos: 755 (InfinityFree los pone automáticamente)

### **Problema 3: Imágenes no se muestran**

**Síntoma:** Productos sin imagen en el sitio

**Solución:**
1. Verifica que la ruta en MySQL sea correcta
2. Debe ser: `data/uploads/nombre-imagen.jpg`
3. NO debe ser: `/data/uploads/` o `../data/uploads/`

### **Problema 4: Error 500 en admin**

**Síntoma:** "Internal Server Error"

**Solución:**
1. Verifica que todos los archivos se subieron correctamente
2. Revisa que `admin/database.php` tenga las credenciales correctas
3. Asegúrate de haber creado las tablas en phpMyAdmin

---

## 🎯 CHECKLIST DE MIGRACIÓN

Antes de subir a InfinityFree:

- [ ] **Base de datos creada** en InfinityFree
- [ ] **Tablas creadas** con schema.sql
- [ ] **Credenciales actualizadas** en database.php
- [ ] **Archivos subidos** vía FTP
- [ ] **Carpeta data/uploads/** existe
- [ ] **Sitio web funciona** (index.html)
- [ ] **API funciona** (api/productos.php)
- [ ] **Admin funciona** (login)
- [ ] **Subida de imágenes funciona**
- [ ] **Productos se muestran** con imágenes

---

## 💡 PREGUNTAS FRECUENTES

### **¿InfinityFree tiene límites?**
Sí, pero son generosos:
- **Espacio:** 5 GB (suficiente para miles de imágenes)
- **Ancho de banda:** Ilimitado
- **Bases de datos:** 400 MySQL databases
- **Subida de archivos:** Hasta 10 MB por archivo

### **¿Las imágenes se guardan en MySQL?**
NO. Las imágenes se guardan en `data/uploads/`.
En MySQL solo se guarda la RUTA: `data/uploads/imagen.jpg`

### **¿Qué pasa si el cliente sube desde cero?**
✅ Todo funciona automáticamente:
1. Cliente sube imagen
2. Sistema genera nombre único
3. Sistema guarda en data/uploads/
4. Sistema guarda ruta en MySQL
5. Frontend carga imagen correctamente

### **¿Necesitamos cambiar algo en el código?**
Solo las credenciales de MySQL en `admin/database.php`.
Todo lo demás funciona igual.

### **¿Podemos usar un dominio personalizado?**
Sí, InfinityFree permite:
- Subdominio gratis: `tu-sitio.infinityfree.com`
- Dominio propio: `www.joyeriamatt.com` (si lo compras)

---

## 🚀 RESUMEN EJECUTIVO

**Para tu cliente:**

1. ✅ **Funciona en InfinityFree** - Sin problemas
2. ✅ **Sin configuración especial** - Todo automático
3. ✅ **Sube imágenes fácilmente** - Desde el admin
4. ✅ **Sin preocuparse por rutas** - El sistema las maneja
5. ✅ **Gratis y confiable** - InfinityFree es estable

**Para ti (desarrollador):**

1. Crea base de datos en InfinityFree
2. Ejecuta schema.sql en phpMyAdmin
3. Actualiza credenciales en database.php
4. Sube archivos vía FTP
5. ¡Listo! Todo funciona

---

**Última actualización:** Octubre 29, 2025
