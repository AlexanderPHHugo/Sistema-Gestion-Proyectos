DROP DATABASE tecnosoluciones;
CREATE DATABASE IF NOT EXISTS tecnosoluciones;
use tecnosoluciones;

-- Tabla de usuarios del sistema
CREATE TABLE usuario (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(80)  NOT NULL,
    ape_pat     VARCHAR(80)  NOT NULL,
    ape_mat     VARCHAR(80)  NOT NULL,
    dni         VARCHAR(8)   NOT NULL UNIQUE,
    usuario     VARCHAR(50)  NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    rol         VARCHAR(20)  NOT NULL DEFAULT 'empleado',
    creado_en   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de clientes
CREATE TABLE cliente (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    razon_social VARCHAR(120) NOT NULL,
    ruc          VARCHAR(11)  NOT NULL UNIQUE,
    telefono     VARCHAR(15)  NULL,
    correo       VARCHAR(100) NULL,
    direccion    VARCHAR(150) NULL,
    creado_en    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de proyectos (relacionada con cliente)
CREATE TABLE proyecto (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id   INT          NOT NULL,
    nombre       VARCHAR(120) NOT NULL,
    descripcion  TEXT         NULL,
    estado       VARCHAR(30)  NOT NULL DEFAULT 'En proceso',
    fecha_inicio DATE         NOT NULL,
    fecha_fin    DATE         NULL,
    creado_en    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_proyecto_cliente FOREIGN KEY (cliente_id) REFERENCES cliente(id)
);

INSERT INTO usuario (nombre, ape_pat, ape_mat, dni, usuario, password, rol)
VALUES ('Alexander', 'Perez', 'Herencia', '75184818', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
