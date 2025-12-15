-- Base de Datos: Sistema de Inventario de Autopartes
-- Creado para: Proyecto Final Ingeniería Web
-- Fecha: 2025-12-10

CREATE DATABASE IF NOT EXISTS inventario_autopartes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario_autopartes;

-- =====================================================
-- TABLA: roles
-- Descripción: Define los roles del sistema
-- =====================================================
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: permisos_rol
-- Descripción: Permisos granulares por rol
-- =====================================================
CREATE TABLE permisos_rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    modulo VARCHAR(50) NOT NULL COMMENT 'usuarios, inventario, categorias, reportes, etc',
    puede_crear TINYINT(1) DEFAULT 0,
    puede_leer TINYINT(1) DEFAULT 0,
    puede_actualizar TINYINT(1) DEFAULT 0,
    puede_eliminar TINYINT(1) DEFAULT 0,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_rol_modulo (rol_id, modulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: usuarios
-- Descripción: Usuarios del sistema (Admin, Operador, Cliente)
-- =====================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    estado TINYINT(1) DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_sesion TIMESTAMP NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id),
    INDEX idx_email (email),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: categorias
-- Descripción: Categorías de autopartes (Motor, Carrocería, etc)
-- =====================================================
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    imagen VARCHAR(255),
    estado TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: autopartes
-- Descripción: Inventario principal de autopartes
-- =====================================================
CREATE TABLE autopartes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    marca VARCHAR(100) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    anio YEAR NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    categoria_id INT NOT NULL,
    thumbnail VARCHAR(255) COMMENT 'Ruta imagen pequeña',
    imagen_grande VARCHAR(255) COMMENT 'Ruta imagen grande',
    descripcion TEXT,
    estado TINYINT(1) DEFAULT 1 COMMENT '1=Disponible, 0=No disponible',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    INDEX idx_marca (marca),
    INDEX idx_modelo (modelo),
    INDEX idx_anio (anio),
    INDEX idx_categoria (categoria_id),
    INDEX idx_estado (estado),
    FULLTEXT idx_busqueda (nombre, marca, modelo, descripcion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: carrito
-- Descripción: Carrito temporal de compras del cliente
-- =====================================================
CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    autoparte_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (autoparte_id) REFERENCES autopartes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_usuario_parte (usuario_id, autoparte_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: ventas
-- Descripción: Registro de ventas realizadas
-- =====================================================
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    itbms DECIMAL(10,2) NOT NULL COMMENT 'Impuesto 7%',
    total DECIMAL(10,2) NOT NULL,
    fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'completada' COMMENT 'completada, cancelada',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_fecha (fecha_venta),
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: detalle_venta
-- Descripción: Detalle de productos vendidos por venta
-- =====================================================
CREATE TABLE detalle_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    autoparte_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (autoparte_id) REFERENCES autopartes(id),
    INDEX idx_venta (venta_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: vendido_parte (Requisito del profesor - Punto 4)
-- Descripción: Historial de partes vendidas para tracking
-- =====================================================
CREATE TABLE vendido_parte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    autoparte_id INT NOT NULL,
    venta_id INT,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    usuario_id INT NOT NULL COMMENT 'Usuario que realizó la compra',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autoparte_id) REFERENCES autopartes(id),
    FOREIGN KEY (venta_id) REFERENCES ventas(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_fecha (fecha),
    INDEX idx_autoparte (autoparte_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: comentarios
-- Descripción: Comentarios públicos en autopartes
-- =====================================================
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    autoparte_id INT NOT NULL,
    usuario_id INT NULL COMMENT 'NULL si es comentario anónimo',
    nombre_usuario VARCHAR(100) COMMENT 'Para usuarios no registrados',
    comentario TEXT NOT NULL,
    publicar TINYINT(1) DEFAULT 0 COMMENT '1=Publicado, 0=Pendiente moderación',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autoparte_id) REFERENCES autopartes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_autoparte (autoparte_id),
    INDEX idx_publicar (publicar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: sesiones (Opcional para manejo avanzado)
-- Descripción: Control de sesiones activas
-- =====================================================
CREATE TABLE sesiones (
    id VARCHAR(128) PRIMARY KEY,
    usuario_id INT NOT NULL,
    datos TEXT,
    ultima_actividad TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_actividad (ultima_actividad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- Insertar roles básicos
INSERT INTO roles (nombre, descripcion) VALUES
('Administrador', 'Control total del sistema'),
('Operador', 'Gestión de inventario y ventas'),
('Cliente', 'Usuario que realiza compras');

-- Permisos para Administrador (acceso total)
INSERT INTO permisos_rol (rol_id, modulo, puede_crear, puede_leer, puede_actualizar, puede_eliminar) VALUES
(1, 'usuarios', 1, 1, 1, 1),
(1, 'roles', 1, 1, 1, 1),
(1, 'categorias', 1, 1, 1, 1),
(1, 'inventario', 1, 1, 1, 1),
(1, 'ventas', 1, 1, 1, 1),
(1, 'reportes', 1, 1, 1, 1),
(1, 'estadisticas', 1, 1, 1, 1),
(1, 'comentarios', 1, 1, 1, 1);

-- Permisos para Operador
INSERT INTO permisos_rol (rol_id, modulo, puede_crear, puede_leer, puede_actualizar, puede_eliminar) VALUES
(2, 'categorias', 0, 1, 0, 0),
(2, 'inventario', 1, 1, 1, 0),
(2, 'ventas', 0, 1, 0, 0),
(2, 'comentarios', 0, 1, 1, 1);

-- Permisos para Cliente
INSERT INTO permisos_rol (rol_id, modulo, puede_crear, puede_leer, puede_actualizar, puede_eliminar) VALUES
(3, 'catalogo', 0, 1, 0, 0),
(3, 'carrito', 1, 1, 1, 1),
(3, 'compras', 1, 1, 0, 0),
(3, 'comentarios', 1, 1, 0, 0);

-- Usuario administrador por defecto (Requisito del profesor)
-- Usuario: admin, Password: root2514
INSERT INTO usuarios (nombre, email, password, rol_id, estado) VALUES
('Administrador', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1);
-- Nota: La contraseña 'root2514' debe ser hasheada con password_hash() en PHP

-- Categorías iniciales
INSERT INTO categorias (nombre, descripcion) VALUES
('Motor', 'Piezas relacionadas con el motor del vehículo'),
('Carrocería', 'Puertas, cofres, parachoques y paneles'),
('Vidrios', 'Parabrisas, ventanas y espejos'),
('Eléctrico', 'Componentes eléctricos y electrónicos'),
('Interior', 'Asientos, tableros y accesorios internos'),
('Suspensión', 'Amortiguadores, muelles y componentes'),
('Frenos', 'Discos, pastillas y sistemas de frenado'),
('Transmisión', 'Cajas de cambio y componentes');

-- =====================================================
-- TRIGGERS ÚTILES
-- =====================================================

-- Trigger: Actualizar stock al registrar venta
DELIMITER //
CREATE TRIGGER after_detalle_venta_insert
AFTER INSERT ON detalle_venta
FOR EACH ROW
BEGIN
    UPDATE autopartes 
    SET stock = stock - NEW.cantidad 
    WHERE id = NEW.autoparte_id;
    
    -- Insertar en vendido_parte
    INSERT INTO vendido_parte (autoparte_id, venta_id, cantidad, precio, usuario_id)
    SELECT NEW.autoparte_id, NEW.venta_id, NEW.cantidad, NEW.precio_unitario, v.usuario_id
    FROM ventas v WHERE v.id = NEW.venta_id;
END//
DELIMITER ;

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista: Inventario con información completa
CREATE VIEW vista_inventario_completo AS
SELECT 
    a.id,
    a.nombre,
    a.marca,
    a.modelo,
    a.anio,
    a.precio,
    a.stock,
    a.thumbnail,
    a.imagen_grande,
    a.descripcion,
    a.estado,
    c.nombre AS categoria,
    c.id AS categoria_id,
    a.fecha_creacion
FROM autopartes a
INNER JOIN categorias c ON a.categoria_id = c.id;

-- Vista: Ventas con detalles
CREATE VIEW vista_ventas_completas AS
SELECT 
    v.id AS venta_id,
    v.fecha_venta,
    v.subtotal,
    v.itbms,
    v.total,
    u.nombre AS cliente,
    u.email AS cliente_email,
    COUNT(dv.id) AS total_items
FROM ventas v
INNER JOIN usuarios u ON v.usuario_id = u.id
LEFT JOIN detalle_venta dv ON v.id = dv.venta_id
GROUP BY v.id;

-- =====================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================

-- Índice para búsquedas rápidas en carrito
CREATE INDEX idx_carrito_usuario ON carrito(usuario_id, fecha_agregado);

-- Índice para reportes de ventas por mes
CREATE INDEX idx_ventas_mes ON ventas(YEAR(fecha_venta), MONTH(fecha_venta));

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================