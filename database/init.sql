SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS associates;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rooms (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  number VARCHAR(10) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL DEFAULT 1,
  photo VARCHAR(255) DEFAULT NULL,
  category_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY rooms_number_unique (number),
  KEY rooms_category_idx (category_id),
  CONSTRAINT rooms_category_fk FOREIGN KEY (category_id) REFERENCES categories (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT rooms_quantity_chk CHECK (quantity >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE clients (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  last_name VARCHAR(50) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  middle_name VARCHAR(50) DEFAULT NULL,
  birth_date DATE NOT NULL,
  phone VARCHAR(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE associates (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  last_name VARCHAR(50) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  middle_name VARCHAR(50) DEFAULT NULL,
  position VARCHAR(50) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  birth_date DATE NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bookings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  date_in DATE NOT NULL,
  date_out DATE NOT NULL,
  status ENUM('reserved', 'confirmed', 'cancelled') NOT NULL DEFAULT 'reserved',
  total DECIMAL(10,2) NOT NULL,
  client_id INT UNSIGNED NOT NULL,
  associate_id INT UNSIGNED NOT NULL,
  room_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY bookings_client_idx (client_id),
  KEY bookings_associate_idx (associate_id),
  KEY bookings_room_idx (room_id),
  KEY bookings_room_status_dates_idx (room_id, status, date_in, date_out),
  CONSTRAINT bookings_client_fk FOREIGN KEY (client_id) REFERENCES clients (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT bookings_associate_fk FOREIGN KEY (associate_id) REFERENCES associates (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT bookings_room_fk FOREIGN KEY (room_id) REFERENCES rooms (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT bookings_dates_chk CHECK (date_out > date_in)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  login VARCHAR(64) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY users_login_unique (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (name) VALUES
  ('Люкс'),
  ('Одноместный'),
  ('Двухместный');

INSERT INTO rooms (number, price, quantity, photo, category_id) VALUES
  ('01', 1500.00, 5, 'Stan.jpg', 1),
  ('02', 2000.00, 3, 'StUl.jpg', 2),
  ('03', 3000.00, 2, 'Lux.jpg', 1);

INSERT INTO clients (last_name, first_name, middle_name, birth_date, phone) VALUES
  ('Сорокин', 'Тимофей', 'Алексеевич', '1999-03-06', '89028257843');

INSERT INTO associates (last_name, first_name, middle_name, position, phone, birth_date) VALUES
  ('Сорокин', 'Тимофей', 'Алексеевич', 'Директор', '89028256734', '1999-03-06');

INSERT INTO bookings (date_in, date_out, status, total, client_id, associate_id, room_id) VALUES
  ('2019-06-11', '2019-06-19', 'confirmed', 20000.00, 1, 1, 2);

SET FOREIGN_KEY_CHECKS = 1;
