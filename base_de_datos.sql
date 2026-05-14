USE sistema_inventario;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    stock INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL
);
-- Insertar usuarios por defecto del sistema
INSERT INTO usuarios (nombre_completo, usuario, password, rol) VALUES 
('Administrador Principal', 'admin', 'admin123', 'Administrador'),
('Cajero de Turno', 'cajero1', 'ventas2024', 'Cajero');

-- Insertar lote de inventario inicial
INSERT INTO productos (nombre_producto, categoria, stock, precio) VALUES 
('Laptop Dell Inspiron 15', 'Computadoras', 10, 650.00),
('Mouse Inalámbrico Logitech', 'Accesorios', 25, 15.50),
('Impresora Epson EcoTank', 'Oficina', 5, 210.00),
('Resma de Papel Tamaño Carta', 'Papelería', 100, 4.25);

-- CONSULTAS DE PRÁCTICA DEL MÓDULO (Guía 9)

-- Actualización simultánea de precio y stock:
-- UPDATE productos SET precio = 720.00, stock = 15 WHERE id = 1;

-- Eliminación de producto descontinuado:
-- DELETE FROM productos WHERE id = 4;