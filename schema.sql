DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE yeticave;


CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(128) NOT NULL,
  FULLTEXT (title)
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
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FULLTEXT (title)
);


CREATE TABLE rate (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  amount INT,
  user_id INT,
  lot_id INT
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(50) NOT NULL,
  passwd CHAR(255) NOT NULL,
  img CHAR(128),
  contact CHAR(10),
  lot_id CHAR(128),
  rate_id CHAR(128)
);

CREATE INDEX user_lots ON user(lot_id);
CREATE INDEX rate_step ON lot(rate_step);
