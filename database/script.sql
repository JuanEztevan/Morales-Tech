CREATE DATABASE IF NOT EXISTS morales_tech;

USE morales_tech;

-- tabla cliente
CREATE TABLE CLIENTE (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    numTelefono VARCHAR(20),
    numDNI VARCHAR(8),
    numRUC VARCHAR(11),
    pregunta1  VARCHAR(255),
    respuesta1 VARCHAR(255),
    pregunta2  VARCHAR(255),
    respuesta2 VARCHAR(255),
    pregunta3  VARCHAR(255),
    respuesta3 VARCHAR(255)
);

-- tabla admin
CREATE TABLE ADMIN (
    idAdmin INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    dni VARCHAR(8) NOT NULL,
    pregunta1  VARCHAR(255),
    respuesta1 VARCHAR(255),
    pregunta2  VARCHAR(255),
    respuesta2 VARCHAR(255),
    pregunta3  VARCHAR(255),
    respuesta3 VARCHAR(255)
);

-- tabla servicio
CREATE TABLE SERVICIO (
    idServicio INT AUTO_INCREMENT PRIMARY KEY,
    nomServicio VARCHAR(150) NOT NULL,
    tipo VARCHAR(100),
    precio DECIMAL(10,2) NOT NULL
);

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
);

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
);

-- tabla componente
CREATE TABLE COMPONENTE (
    idComponente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    categoria VARCHAR(100),
    codigoSKU VARCHAR(50) UNIQUE,
    stockActual INT NOT NULL DEFAULT 0,
    stockMinimo INT NOT NULL DEFAULT 0,
    precioUnitario DECIMAL(10,2) NOT NULL
);

-- tabla venta
CREATE TABLE VENTA (
    idVenta INT AUTO_INCREMENT PRIMARY KEY,
    idTicket INT NOT NULL,
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
);

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
