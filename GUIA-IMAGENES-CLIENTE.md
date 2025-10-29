# 📸 GUÍA: Cómo Gestionar Imágenes de Productos

## 🎯 Para el Cliente (Joyería Matt)

Esta guía explica cómo subir y usar imágenes en los productos de forma fácil.

---

## 📋 PASO A PASO

### **1. Acceder al Gestor de Imágenes**

1. Entra al panel admin: `http://tu-sitio.com/admin/`
2. Inicia sesión con tus credenciales
3. En el menú superior, click en **"Imágenes"**

---

### **2. Subir una Nueva Imagen**

#### **Opción A: Arrastrando**
1. Arrastra la foto de tu joya desde tu computadora
2. Suéltala en el área que dice "Arrastra una imagen aquí"
3. Verás una vista previa
4. Click en **"Subir Imagen"**

#### **Opción B: Seleccionando**
1. Click en **"Seleccionar Archivo"**
2. Busca la foto en tu computadora
3. Selecciónala
4. Verás una vista previa
5. Click en **"Subir Imagen"**

**Formatos permitidos:** JPG, PNG, GIF, WEBP  
**Tamaño máximo:** 5MB por imagen

---

### **3. Usar la Imagen en un Producto**

#### **Al AGREGAR un producto nuevo:**

1. Ve a **"Productos"** → **"Agregar Producto"**
2. Llena los datos del producto (nombre, precio, etc.)
3. En el campo **"Imagen del Producto"**:
   - Click en **"Gestor de Imágenes"** (se abre en nueva pestaña)
   - Busca tu imagen en la galería
   - Click en **"Copiar"** (botón azul junto a la ruta)
   - Vuelve a la pestaña del producto
   - Pega la ruta en el campo de imagen (Ctrl+V o Cmd+V)
4. Termina de llenar los demás campos
5. Click en **"Agregar Producto"**

#### **Al EDITAR un producto existente:**

1. Ve a **"Productos"**
2. Click en el botón azul (editar) del producto
3. En el campo **"Imagen del Producto"**:
   - Click en **"Gestor de Imágenes"**
   - Copia la ruta de la imagen que quieras usar
   - Pégala en el campo
4. Click en **"Guardar Cambios"**

---

### **4. Ver Todas las Imágenes**

En el **Gestor de Imágenes** verás:

- **Miniatura** de cada imagen
- **Nombre** del archivo
- **Tamaño** en KB
- **Fecha** de subida
- **Ruta** para copiar
- **Botón** para eliminar

---

### **5. Eliminar una Imagen**

⚠️ **IMPORTANTE:** Solo elimina imágenes que NO estés usando en ningún producto.

1. Ve al **Gestor de Imágenes**
2. Busca la imagen que quieres eliminar
3. Click en **"Eliminar"** (botón rojo)
4. Confirma la eliminación

**Consejo:** Antes de eliminar, verifica que ningún producto la esté usando.

---

## 💡 CONSEJOS PARA MEJORES RESULTADOS

### **Calidad de las Fotos:**

✅ **Buenas prácticas:**
- Usa fotos con buena iluminación
- Fondo blanco o neutro
- Enfoque en la joya
- Resolución mínima: 800x800 px
- Formato recomendado: JPG

❌ **Evita:**
- Fotos borrosas
- Muy oscuras o muy claras
- Demasiado pesadas (más de 2MB)
- Fondos distractores

### **Nombres de Archivo:**

✅ **Buenos nombres:**
- `anillo-oro-14k.jpg`
- `argollas-matrimoniales-plata.jpg`
- `compromiso-diamante.jpg`

❌ **Malos nombres:**
- `IMG_12345.jpg`
- `foto sin nombre.jpg`
- `DSC00001.jpg`

---

## 🔄 FLUJO COMPLETO (Ejemplo Real)

### **Ejemplo: Agregar un Anillo de Graduación**

1. **Preparar la foto:**
   - Toma foto del anillo
   - Asegúrate que se vea bien
   - Guárdala como: `anillo-graduacion-medicina.jpg`

2. **Subir la foto:**
   - Admin → Imágenes
   - Arrastra `anillo-graduacion-medicina.jpg`
   - Click "Subir Imagen"
   - ✅ Imagen subida

3. **Crear el producto:**
   - Admin → Productos → Agregar Producto
   - Nombre: "Anillo de Graduación Medicina"
   - Precio: 950
   - Categoría: Anillos de Graduación
   - Material: Oro 10k
   - Descripción: "Anillo especial para graduados..."
   
4. **Agregar la imagen:**
   - Click "Gestor de Imágenes"
   - Buscar `anillo-graduacion-medicina.jpg`
   - Click "Copiar"
   - Volver y pegar: `data/uploads/anillo-graduacion-medicina-1234567890.jpg`

5. **Guardar:**
   - Marcar "Producto activo"
   - Click "Agregar Producto"
   - ✅ ¡Listo!

6. **Verificar:**
   - Ir al sitio web
   - Buscar en "Anillos de Graduación"
   - ✅ El anillo aparece con su foto

---

## ❓ PREGUNTAS FRECUENTES

### **¿Puedo subir varias imágenes a la vez?**
No, por ahora es una por una. Pero es rápido: arrastra, sube, listo.

### **¿Qué pasa si subo una imagen muy grande?**
El sistema la rechazará si pesa más de 5MB. Reduce el tamaño antes de subir.

### **¿Puedo usar la misma imagen en varios productos?**
Sí, puedes copiar la misma ruta en varios productos.

### **¿Cómo sé qué imágenes están en uso?**
Por ahora, debes recordarlo. Consejo: usa nombres descriptivos.

### **¿Puedo cambiar la imagen de un producto después?**
Sí, edita el producto y pega una nueva ruta de imagen.

### **¿Las imágenes se guardan en la base de datos?**
No, se guardan en la carpeta `data/uploads/`. En la base de datos solo se guarda la ruta.

---

## 🎓 RESUMEN RÁPIDO

```
1. Admin → Imágenes
2. Subir foto
3. Copiar ruta
4. Admin → Productos → Agregar/Editar
5. Pegar ruta en campo de imagen
6. Guardar
7. ¡Listo! 🎉
```

---

## 📞 SOPORTE

Si tienes dudas o problemas:
1. Revisa esta guía
2. Verifica que la imagen no sea muy grande
3. Asegúrate de copiar la ruta completa
4. Contacta al desarrollador si persiste el problema

---

**Última actualización:** Octubre 28, 2025
