-- Crear base de datos
CREATE DATABASE IF NOT EXISTS perlux;
USE perlux;

-- Tabla para almacenar las categorías de los productos
CREATE TABLE Categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Tabla para almacenar las marcas de perfumes
CREATE TABLE Marca (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    logo TEXT,
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria)
);

-- Tabla para almacenar los productos
CREATE TABLE Producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    imagen TEXT,
    id_marca INT,
    FOREIGN KEY (id_marca) REFERENCES Marca (id_marca)
);

-- Tabla para los roles de usuarios
CREATE TABLE Rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    rol VARCHAR(15) NOT NULL
);

-- Tabla para los usuarios
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(15),
    id_rol INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES Rol (id_rol)
);

-- Tabla para almacenar los estados de los pedidos
CREATE TABLE EstadoPedido (
    id_estado_pedido INT AUTO_INCREMENT PRIMARY KEY,
    estado_pedido VARCHAR(20)
);

-- Tabla para almacenar métodos de pago
CREATE TABLE MetodoPago (
    id_metodo_pago INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Tabla para almacenar los pedidos
CREATE TABLE Pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2),
    id_usuario INT NOT NULL,
    id_estado_pedido INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_estado_pedido) REFERENCES EstadoPedido(id_estado_pedido),
    FOREIGN KEY (id_metodo_pago) REFERENCES MetodoPago(id_metodo_pago)
);

-- Tabla para almacenar los detalles del pedido
CREATE TABLE DetallePedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto)
);

-- Tabla para almacenar el tipo de envío
CREATE TABLE EstadoEnvio(
    id_estado_envio INT AUTO_INCREMENT PRIMARY KEY,
    estado VARCHAR(20)
);

-- Tabla para los envíos
CREATE TABLE Envio (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    direccion_envio TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_pedido INT NOT NULL,
    id_estado_envio INT NOT NULL, 
    FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido),
    FOREIGN KEY (id_estado_envio) REFERENCES EstadoEnvio(id_estado_envio)
);

-- Crear usuario 'cliente' con acceso limitado
CREATE USER 'cliente'@'localhost' IDENTIFIED BY 'cliente';

-- Asignar permisos limitados al usuario 'cliente'
GRANT SELECT, INSERT, UPDATE ON perlux.Producto TO 'cliente'@'localhost';
GRANT SELECT, INSERT, UPDATE ON perlux.Categoria TO 'cliente'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON perlux.Carrito TO 'cliente'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON perlux.Pedido TO 'cliente'@'localhost';
GRANT SELECT, INSERT ON perlux.DetallePedido TO 'cliente'@'localhost';

-- Establecer la base de datos 'perlux' como predeterminada para el usuario 'cliente'
GRANT USAGE ON *.* TO 'cliente'@'localhost';

-- Crear usuario 'admin' con acceso completo
CREATE USER 'boss'@'localhost' IDENTIFIED BY 'admin';

-- Asignar permisos avanzados al usuario 'admin'
GRANT ALL PRIVILEGES ON perlux.* TO 'boss'@'localhost';

-- Aplicar los cambios
FLUSH PRIVILEGES;


-- Insertar categorías de ejemplo
INSERT INTO Categoria (nombre, descripcion)
VALUES 
('Perfumes de Hombre', 'Fragancias diseñadas para hombres'),
('Perfumes de Mujer', 'Fragancias diseñadas para mujeres');

-- Insertar marcas para hombres
INSERT INTO Marca (nombre, descripcion, id_categoria)
VALUES 
('Hugo Boss', 'Fragancias elegantes y modernas para hombres', 1),
('Tom Ford', 'Perfumes masculinos de lujo y sofisticación', 1),
('Dior', 'Fragancias icónicas para hombres', 1),
('Paco Rabanne', 'Perfumes masculinos con un toque de audacia', 1);

-- Insertar marcas para mujeres
INSERT INTO Marca (nombre, descripcion, id_categoria)
VALUES 
('Chanel', 'Fragancias elegantes y atemporales para mujeres', 2),
('Ariana Grande', 'Perfumes modernos y juveniles para mujeres', 2),
('Gucci', 'Fragancias de lujo para mujeres', 2),
('Lancôme', 'Perfumes femeninos con un toque romántico', 2);

-- Insertar productos para hombres
INSERT INTO Producto (nombre, descripcion, precio, stock, imagen, id_marca)
VALUES 
('Hugo Boss Bottled', 'Fragancia masculina con notas de manzana y canela', 85.50, 100, 'hugo_boss_bottled.jpeg', 1),
('Tom Ford Noir Extreme', 'Perfume masculino oriental con un toque especiado', 120.99, 50, 'tom_ford_noir_extreme.jpeg', 2),
('Dior Sauvage', 'Fragancia fresca y robusta con notas de bergamota', 95.00, 75, 'dior_sauvage.jpeg', 3),
('Paco Rabanne 1 Million', 'Perfume masculino con notas de cuero y canela', 89.99, 80, 'paco_rabanne_1_million.jpeg', 4);

-- Insertar productos para mujeres
INSERT INTO Producto (nombre, descripcion, precio, stock, imagen, id_marca)
VALUES 
('Chanel No. 5', 'Fragancia floral icónica con notas de jazmín y rosa', 150.00, 40, 'chanel_no_5.jpeg', 5),
('Ariana Grande Cloud', 'Perfume dulce y fresco con notas de lavanda y pera', 65.00, 120, 'ariana_grande_cloud.jpeg', 6),
('Gucci Bloom', 'Fragancia femenina floral con notas de jazmín y nardo', 110.00, 60, 'gucci_bloom.jpeg', 7),
('Lancôme La Vie Est Belle', 'Perfume dulce y elegante con notas de vainilla y iris', 99.99, 70, 'lancome_la_vie_est_belle.jpeg', 8);

INSERT INTO Rol (rol)
VALUES 
('Invitado'),
('Usuario');

INSERT INTO EstadoEnvio (estado)
VALUES 
('En proceso'),
('Cancelado'),
('Enviado');

INSERT INTO EstadoPedido (estado_pedido)
VALUES
('Pendiente'),
('Cancelado'),
('Aceptado');

INSERT INTO MetodoPago (nombre, descripcion)
VALUES
('Efectivo', 'Pago con efectivo'),
('Débito/Crédito', 'Pago realizado en línea con alguna tarjeta débito o crédito VISA, AMEX, Mastercard, Discover');