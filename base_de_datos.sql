-- Usar la base de datos del proyecto
USE sistema_inventario;

-- 1. Tabla para el módulo de Login y Seguridad
CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_completo VARCHAR(100) NOT NULL,
usuario VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
rol VARCHAR(20) NOT NULL
);

-- 2. NUEVA TABLA RAÍZ: Categorías del sistema
CREATE TABLE categorias (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_categoria VARCHAR(50) NOT NULL UNIQUE
);

-- 3. TABLA DEPENDIENTE MODIFICADA: Productos con Llave Foránea
CREATE TABLE productos (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_producto VARCHAR(100) NOT NULL,
categoria_id INT NOT NULL,
stock INT NOT NULL,
precio DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- 4. Inserción de los catálogos base (deben insertarse primero)
INSERT INTO categorias (nombre_categoria) VALUES
('Computadoras'),
('Accesorios'),
('Oficina');

-- 5. Inserción de Productos (Observa cómo ahora vinculamos usando números
enteros)
INSERT INTO productos (nombre_producto, categoria_id, stock, precio) VALUES
('Laptop Dell Inspiron 15', 1, 15, 720.00),
('Mouse Inalámbrico Logitech', 2, 25, 12.00);

-- guia 11 reportes relacionales avanzados 
--1 reporte general de inventario
SELECT p.id, p.nombre_producto, c.nombre_categoria, p.stock, p.precio
FROM productos p
INNER JOIN categorias c
ON p.categoria_id=c.id;

--2 reporte filtrado por departamento
SELECT p.id, p.nombre_producto, c.nombre_categoria, p.stock, p.precio
FROM productos p
INNER JOIN categorias c
ON p.categoria_id=c.id
WHERE c.nombre_categoria = "Accesorios";