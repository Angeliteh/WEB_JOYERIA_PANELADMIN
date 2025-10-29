-- ============================================
-- JOYERÍA MATT - Estructura de Base de Datos
-- ============================================
-- Este archivo crea todas las tablas necesarias
-- Úsalo en: phpMyAdmin local y en InfinityFree
-- ============================================

-- Tabla: productos
-- Almacena el catálogo de joyas
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(500),
    material VARCHAR(200),
    tiempo_entrega VARCHAR(100),
    personalizacion VARCHAR(500),
    peso_aproximado VARCHAR(100),
    garantia VARCHAR(500),
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: solicitudes
-- Almacena los pedidos personalizados de clientes
CREATE TABLE IF NOT EXISTS solicitudes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    tipo_joya VARCHAR(100) NOT NULL,
    texto_grabar VARCHAR(500),
    archivo_diseno VARCHAR(500),
    mensaje TEXT NOT NULL,
    producto_base_id INT NULL,
    estado ENUM('pendiente', 'contactado', 'en_proceso', 'completado', 'cancelado') DEFAULT 'pendiente',
    notas_admin TEXT,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_estado (estado),
    INDEX idx_fecha (fecha_solicitud),
    FOREIGN KEY (producto_base_id) REFERENCES productos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: configuracion
-- Almacena configuraciones del sitio (opcional, para futuro)
CREATE TABLE IF NOT EXISTS configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descripcion VARCHAR(255),
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar configuraciones iniciales
INSERT INTO configuracion (clave, valor, descripcion) VALUES
('whatsapp_numero', '526565563197', 'Número de WhatsApp para contacto'),
('email_contacto', 'danielillocontreras@gmail.com', 'Email de contacto'),
('horario_atencion', 'Lunes a Domingo: 8:00 AM - 8:00 PM', 'Horario de atención'),
('sitio_activo', '1', 'Estado del sitio (1=activo, 0=mantenimiento)')
ON DUPLICATE KEY UPDATE valor=valor;

-- ============================================
-- NOTAS IMPORTANTES:
-- ============================================
-- 1. Las imágenes NO se guardan en la base de datos
--    Solo se guarda la RUTA en los campos 'imagen' y 'archivo_diseno'
--
-- 2. Archivos físicos se guardan en:
--    - Productos: data/uploads/
--    - Solicitudes: data/solicitudes/
--
-- 3. Para InfinityFree:
--    - Copia este archivo completo
--    - Pégalo en phpMyAdmin
--    - Ejecuta todo de una vez
--
-- 4. Para desarrollo local:
--    - Usa XAMPP/MAMP
--    - Crea una base de datos llamada: joyeria_matt
--    - Ejecuta este script
-- ============================================
