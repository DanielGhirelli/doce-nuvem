SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS cupcake_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cupcake_shop;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL,
  address VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cupcakes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  price DECIMAL(8,2) NOT NULL,
  image VARCHAR(100) NOT NULL,
  color VARCHAR(7) NOT NULL
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  payment ENUM('dinheiro','pix','cartao') NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Preparando para entrega',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
  order_id INT NOT NULL,
  cupcake_id INT NOT NULL,
  qty INT NOT NULL,
  price DECIMAL(8,2) NOT NULL,
  PRIMARY KEY (order_id, cupcake_id),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (cupcake_id) REFERENCES cupcakes(id)
);

INSERT INTO cupcakes (name, description, price, image, color) VALUES
('Red Velvet',      'Massa aveludada com cobertura de cream cheese', 9.90,  'red-velvet.jpg', '#ffd6dc'),
('Chocolate Belga', 'Ganache intensa e granulado crocante',          10.50, 'chocolate.jpg', '#e8d5c4'),
('Morango',         'Recheio de geleia artesanal de morango',        8.90,  'morango.jpg', '#ffe0ea'),
('Limão Siciliano', 'Cobertura de merengue maçaricado',              8.50,  'limao.jpg', '#fff4c2'),
('Baunilha',        'Clássico com buttercream de fava de baunilha',  7.90,  'baunilha.jpg', '#fdf1e1'),
('Mirtilo',         'Massa leve com mirtilos frescos',               10.90, 'mirtilo.jpg', '#dfe3ff');

-- Admin padrão: usuário admin / senha admin123 (troque em produção)
INSERT INTO users (username, password_hash, name, address, is_admin) VALUES
('admin', '$2y$12$21fhUer3r/Ep.qUAX8Jn/.nuARTOCf46B6.OdcBtLk8r01CxtdjFO', 'Administrador', 'Loja Doce Nuvem', 1);
