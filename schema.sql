DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE yeticave;


CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(128) NOT NULL
);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cat_id INT NOT NULL,
  author_id INT NOT NULL,
  user_id INT NOT NULL,
  title CHAR(128) NOT NULL,
  description TEXT,
  img_path CHAR(128),
  start_price INT NOT NULL,
  rate_step INT,
  end_date DATE,
  created TIMESTAMP
);


CREATE TABLE rate (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date TIMESTAMP,
  amount INT,
  user_id INT,
  lot_id INT
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(50) NOT NULL,
  passwd CHAR(32) NOT NULL,
  img CHAR(128),
  contact CHAR(10),
  lot_id INT,
  rate_id INT
);

CREATE INDEX category ON category(title);

CREATE INDEX lot ON lot(title);