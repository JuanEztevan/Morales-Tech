SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS morales_tech
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE morales_tech;
-- demo
-- tabla cliente
CREATE TABLE CLIENTE (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    numTelefono VARCHAR(20),
    numDNI VARCHAR(8) UNIQUE NOT NULL,
    numRUC VARCHAR(11),
    pregunta1  VARCHAR(255),
    respuesta1 VARCHAR(255),
    pregunta2  VARCHAR(255),
    respuesta2 VARCHAR(255),
    pregunta3  VARCHAR(255),
    respuesta3 VARCHAR(255)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- tabla admin
CREATE TABLE ADMIN (
    idAdmin INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    dni VARCHAR(8) UNIQUE NOT NULL,
    pregunta1  VARCHAR(255),
    respuesta1 VARCHAR(255),
    pregunta2  VARCHAR(255),
    respuesta2 VARCHAR(255),
    pregunta3  VARCHAR(255),
    respuesta3 VARCHAR(255)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- tabla servicio
CREATE TABLE SERVICIO (
    idServicio INT AUTO_INCREMENT PRIMARY KEY,
    nomServicio VARCHAR(150) NOT NULL,
    tipo VARCHAR(100),
    precio DECIMAL(10,2) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- tabla equipo
CREATE TABLE EQUIPO (
    idEquipo INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    tipoEquipo VARCHAR(100),
    marca VARCHAR(100),
    modelo VARCHAR(100),
    numSerie VARCHAR(100),
    sistemaOperativo VARCHAR(100),
    observaciones VARCHAR(255),

    FOREIGN KEY (idCliente)
        REFERENCES CLIENTE(idCliente)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- tabla cotizacion
CREATE TABLE COTIZACION (
    idCotizacion INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    idEquipo INT NOT NULL,
    idAdmin INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    igv DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (idCliente)
        REFERENCES CLIENTE(idCliente),

    FOREIGN KEY (idEquipo)
        REFERENCES EQUIPO(idEquipo),

    FOREIGN KEY (idAdmin)
        REFERENCES ADMIN(idAdmin)
);

-- tabla cotizacion_servicio
CREATE TABLE COTIZACION_SERVICIO (
    idCotServicio INT AUTO_INCREMENT PRIMARY KEY,
    idCotizacion INT NOT NULL,
    idServicio INT NOT NULL,

    FOREIGN KEY (idCotizacion)
        REFERENCES COTIZACION(idCotizacion),

    FOREIGN KEY (idServicio)
        REFERENCES SERVICIO(idServicio)
);

-- tabla ticket
CREATE TABLE TICKET (
    idTicket INT AUTO_INCREMENT PRIMARY KEY,
    idCotizacion INT NOT NULL,
    codigo VARCHAR(50) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idCotizacion)
        REFERENCES COTIZACION(idCotizacion)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- tabla componente
CREATE TABLE COMPONENTE (
    idComponente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    categoria VARCHAR(100),
    stockActual INT NOT NULL DEFAULT 0,
    stockMinimo INT NOT NULL DEFAULT 0,
    precioUnitario DECIMAL(10,2) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- tabla venta
CREATE TABLE VENTA (
    idVenta INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT NULL,
    idAdmin INT NOT NULL,
    nombreCliente VARCHAR(150),
    dniCliente VARCHAR(8),
    rucCliente VARCHAR(11),
    tipo VARCHAR(50),
    metodoPago VARCHAR(50),
    subtotal DECIMAL(10,2) NOT NULL,
    igv DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fechaVenta DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idTicket)
        REFERENCES TICKET(idTicket),

    FOREIGN KEY (idAdmin)
        REFERENCES ADMIN(idAdmin)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- tabla detalle venta
CREATE TABLE DETALLE_VENTA (
    idDetalle INT AUTO_INCREMENT PRIMARY KEY,
    idVenta INT NOT NULL,
    idComponente INT NOT NULL,
    cantidad INT NOT NULL,

    FOREIGN KEY (idVenta)
        REFERENCES VENTA(idVenta),

    FOREIGN KEY (idComponente)
        REFERENCES COMPONENTE(idComponente)
);

-- ── Columnas de preguntas de seguridad (para BD ya existente) ──
-- Ejecuta esto si la BD ya estaba creada sin estas columnas.
-- Si creas la BD desde cero con este script, ignora esta sección.
ALTER TABLE CLIENTE
    ADD COLUMN IF NOT EXISTS pregunta1  VARCHAR(255) AFTER numRUC,
    ADD COLUMN IF NOT EXISTS respuesta1 VARCHAR(255) AFTER pregunta1,
    ADD COLUMN IF NOT EXISTS pregunta2  VARCHAR(255) AFTER respuesta1,
    ADD COLUMN IF NOT EXISTS respuesta2 VARCHAR(255) AFTER pregunta2,
    ADD COLUMN IF NOT EXISTS pregunta3  VARCHAR(255) AFTER respuesta2,
    ADD COLUMN IF NOT EXISTS respuesta3 VARCHAR(255) AFTER pregunta3;

ALTER TABLE ADMIN
    ADD COLUMN IF NOT EXISTS pregunta1  VARCHAR(255) AFTER dni,
    ADD COLUMN IF NOT EXISTS respuesta1 VARCHAR(255) AFTER pregunta1,
    ADD COLUMN IF NOT EXISTS pregunta2  VARCHAR(255) AFTER respuesta1,
    ADD COLUMN IF NOT EXISTS respuesta2 VARCHAR(255) AFTER pregunta2,
    ADD COLUMN IF NOT EXISTS pregunta3  VARCHAR(255) AFTER respuesta2,
    ADD COLUMN IF NOT EXISTS respuesta3 VARCHAR(255) AFTER pregunta3;

-- ── Datos iniciales: Servicios ──
INSERT INTO SERVICIO (nomServicio, tipo, precio) VALUES
    ('Diagnóstico',                    'Principal',  30.00),
    ('Mantenimiento preventivo',        'Principal',  60.00),
    ('Mantenimiento correctivo',        'Principal',  90.00),
    ('Instalación / Formateo',          'Principal',  80.00),
    ('Limpieza profunda',               'Adicional',  25.00),
    ('Instalación de programas',        'Adicional',  20.00),
    ('Optimización del sistema',        'Adicional',  30.00),
    ('Repotenciación (mano de obra)',   'Adicional',  50.00);


-- Eliminación de columna de CodigoSKU en Componente
ALTER TABLE COMPONENTE
DROP COLUMN IF EXISTS codigoSKU;

-- Inserts de Componentes básicos
INSERT INTO componente (nombre, categoria, stockActual, stockMinimo, precioUnitario) VALUES

-- CONSUMIBLES Y LIMPIEZA
('Alcohol Isopropílico 1L (99%)', 'Consumibles y Limpieza', 20, 5, 25.00),
('Pasta Térmica Arctic MX-4 4g', 'Consumibles y Limpieza', 15, 5, 35.00),
('Aire Comprimido 400ml', 'Consumibles y Limpieza', 25, 8, 18.00),
('Limpiador de Contactos Electrónicos', 'Consumibles y Limpieza', 10, 4, 22.00),

-- HERRAMIENTAS Y KITS
('Kit Destornilladores 32 en 1', 'Herramientas y Kits', 10, 3, 45.00),
('Kit Destornilladores de Precisión 115 en 1', 'Herramientas y Kits', 8, 3, 65.00),
('Multímetro Digital Básico', 'Herramientas y Kits', 5, 2, 55.00),
('Pulsera Antiestática', 'Herramientas y Kits', 12, 4, 12.00),

-- ALMACENAMIENTO
('SSD Kingston 480GB SATA', 'Almacenamiento', 10, 3, 110.00),
('SSD Crucial 1TB SATA', 'Almacenamiento', 6, 2, 210.00),
('SSD NVMe Kingston 500GB', 'Almacenamiento', 8, 3, 150.00),
('Memoria USB Kingston 32GB', 'Almacenamiento', 20, 5, 25.00),

-- MEMORIA RAM
('RAM DDR4 8GB 2666MHz Kingston', 'Memoria RAM', 12, 4, 90.00),
('RAM DDR4 16GB 3200MHz Kingston', 'Memoria RAM', 8, 3, 160.00),
('RAM DDR5 16GB 4800MHz Crucial', 'Memoria RAM', 5, 2, 240.00),
('RAM DDR4 4GB 2400MHz Genérica', 'Memoria RAM', 10, 3, 55.00);

-- Actualización para Ventas por poducto
ALTER TABLE VENTA MODIFY idTicket INT NULL;
