 ANÁLISIS DE LA SITUACIÓN ACTUAL
Lo que tienes:

✅ Web completa de perfumería con sistema dinámico
✅ Panel de administración funcional (PHP + JSON)
✅ Sistema de gestión de productos CRUD
✅ Carga dinámica de productos con JavaScript
✅ Modales para detalles de productos
✅ Sistema de imágenes con upload
✅ Diseño responsive y profesional
✅ Estructura de archivos bien organizada
Arquitectura actual:

Frontend: HTML5, CSS3, JavaScript, Bootstrap 5
Backend: PHP 7.4+ con autenticación por sesiones
Base de datos: JSON (productos.json)
Imágenes: Sistema de upload en data/uploads/
🎯 ESTRATEGIA DE ADAPTACIÓN
Enfoque recomendado: Reutilizar máximo la infraestructura existente, modificando solo lo necesario para el MVP de joyería.

📋 PLAN DE EJECUCIÓN DETALLADO
FASE 1: Adaptación del Modelo de Datos ⏱️ ~30 min
Objetivo: Modificar la estructura de datos para joyas

Acciones:

Backup del productos.json actual
Redefinir campos del modelo:
FASE 2: Actualización del Panel Admin ⏱️ ~45 min
Objetivo: Adaptar formularios y gestión para joyas

Acciones:

Modificar admin/productos.php:
Cambiar campos del formulario
Actualizar validaciones
Adaptar categorías predefinidas
Actualizar labels y textos del admin
Modificar dashboard para mostrar estadísticas de joyas
FASE 3: Adaptación de la Interfaz Pública ⏱️ ~60 min
Objetivo: Transformar la galería de perfumes en portafolio de joyas

Acciones:

Modificar index.html:
Cambiar textos del hero section
Actualizar sección "Sobre nosotros"
Adaptar la galería con filtros por categoría
Modificar modales para mostrar información de joyas
Actualizar css/style.css:
Cambiar paleta de colores (dorado/plateado para joyería)
Adaptar estilos para el nuevo contenido
Modificar JavaScript:
Adaptar función generateProductsHTML() para joyas
Actualizar modales con campos específicos de joyería
FASE 4: Implementación de Formularios de Solicitud ⏱️ ~45 min
Objetivo: Crear sistema de pedidos personalizados

Acciones:

Crear formulario general de solicitud:
Campos: nombre, email, teléfono, tipo de joya, texto a grabar, upload de imagen, comentarios
Crear formulario específico por producto:
Prellenar con datos del producto seleccionado
Implementar procesamiento PHP:
Validación de datos
Manejo de archivos subidos
Envío por email o guardado en JSON
FASE 5: Integración con WhatsApp ⏱️ ~20 min
Objetivo: Facilitar comunicación directa con el joyero

Acciones:

Botón flotante WhatsApp:
Posición fija en toda la web
Mensaje preformateado
Enlaces directos desde productos:
Mensajes específicos por joya
Integración en formularios:
Opción de enviar solicitud vía WhatsApp
FASE 6: Actualización de Branding ⏱️ ~30 min
Objetivo: Adaptar identidad visual para joyería

Acciones:

Cambiar metadatos y SEO:
Title, description, keywords
Open Graph para joyería
Actualizar colores y tipografías
Cambiar imágenes de demo
Actualizar textos y copy
FASE 7: Testing y Validación ⏱️ ~30 min
Objetivo: Verificar funcionamiento completo del MVP

Acciones:

Pruebas funcionales:
CRUD de productos desde admin
Carga dinámica en frontend
Formularios de solicitud
Integración WhatsApp
Pruebas de usabilidad
Verificación responsive
⚡ DECISIONES TÉCNICAS CLAVE
Mantener arquitectura PHP + JSON: Es suficiente para MVP y funciona bien
Reutilizar sistema de imágenes: Solo adaptar validaciones para fotos de joyas
Conservar estructura de modales: Cambiar solo el contenido mostrado
Aprovechar sistema de autenticación: No requiere cambios
🎯 RESULTADO ESPERADO DEL MVP
Para el cliente joyero:

✅ Galería de trabajos anteriores organizada por categorías
✅ Sistema para subir nuevos trabajos desde panel admin
✅ Formularios de solicitud de joyas personalizadas
✅ Integración directa con WhatsApp
✅ Web profesional lista para mostrar a clientes
Para los visitantes:

✅ Portafolio visual de trabajos de joyería
✅ Filtros por tipo de joya (anillos, relojes, etc.)
✅ Formularios fáciles para solicitar trabajos similares
✅ Contacto directo vía WhatsApp
✅ Experiencia móvil optimizada
📊 ESTIMACIÓN DE TIEMPO TOTAL
Desarrollo: ~4.5 horas
Testing: ~30 minutos
Total: ~5 horas para MVP completo
🚀 PRÓXIMOS PASOS RECOMENDADOS
¿Empezamos con la Fase 1? - Adaptar el modelo de datos
¿Tienes preferencias sobre:
Categorías específicas de joyas
Campos adicionales necesarios
Flujo preferido para recibir solicitudes (email vs WhatsApp)
Colores/branding específicos