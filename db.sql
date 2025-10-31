-- Struktur tabel carts
CREATE TABLE `carts` (
  `id_cart` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT NOT NULL,
  `id_product` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1
);

-- Struktur tabel orders
CREATE TABLE `orders` (
  `id_order` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT NOT NULL,
  `total_price` INT NOT NULL,
  `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Struktur tabel order_items
CREATE TABLE `order_items` (
  `id_order_item` INT AUTO_INCREMENT PRIMARY KEY,
  `id_order` INT NOT NULL,
  `id_product` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` INT NOT NULL
);

-- Struktur tabel products
CREATE TABLE `products` (
  `id_product` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `price` INT NOT NULL,
  `description` TEXT,
  `image_url` VARCHAR(255)
);

-- Struktur tabel users
CREATE TABLE `users` (
  `id_user` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL
);

-- Data untuk tabel products (10 item)
INSERT INTO products (id_product, name, price, description, image_url) VALUES
(1, 'EMBERaja',      19999, 'Ember plastik serbaguna', 'benda2.jpg'),
(2, 'TABUNGdoang',   29999, 'Tabung gas 3kg', 'benda1.jpg'),
(3, 'SETRIKAsahaja', 39999, 'Setrika listrik hemat energi', 'benda.jpg'),
(4, 'GELASunik',      9999, 'Gelas kaca motif unik', 'gelas.jpg'),
(5, 'PIRINGcantik',  14999, 'Piring keramik cantik', 'piring.jpg'),
(6, 'KOMPORmini',    49999, 'Kompor gas mini portable', 'kompor.jpg'),
(7, 'SENDOKset',      4999, 'Set sendok stainless isi 6', 'sendok.jpg'),
(8, 'GAS3kg',        21000, 'Gas LPG 3kg subsidi', 'gas3kg.jpg'),
(9, 'BASKOMbesar',   15999, 'Baskom plastik besar', 'baskom.jpg'),
(10, 'LEMARIplastik', 79999, 'Lemari plastik 4 susun', 'lemari.jpg');
