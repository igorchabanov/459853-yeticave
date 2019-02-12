USE yeticave;

-- Categories
INSERT INTO category (title) VALUES('Доски и лыжи');
INSERT INTO category (title) VALUES('Крепления');
INSERT INTO category (title) VALUES('Ботинки');
INSERT INTO category (title) VALUES('Одежда');
INSERT INTO category (title) VALUES('Инструменты');
INSERT INTO category (title) VALUES('Разное');

-- Users
INSERT INTO user(name, email, passwd) VALUES('Игорь', 'igor@test.mail', 'test');
INSERT INTO user(name, email, passwd) VALUES('Иван', 'ivan@test.mail', 'test');
INSERT INTO user(name, email, passwd) VALUES('John', 'john@test.mail', 'test');

-- Lots
INSERT INTO lot(title, cat_id, start_price, img_path, author_id, user_id) VALUES('2014 Rossignol District Snowboard', 1, 10999, 'img/lot-1.jpg', 1, 2);
INSERT INTO lot(title, cat_id, start_price, img_path, author_id, user_id) VALUES('DC Ply Mens 2016/2017 Snowboard', 1, 159999, 'img/lot-2.jpg', 2, 3);
INSERT INTO lot(title, cat_id, start_price, img_path, author_id, user_id) VALUES('Крепления Union Contact Pro 2015 года размер L/XL', 2, 8000, 'img/lot-3.jpg', 3, 1);
INSERT INTO lot(title, cat_id, start_price, img_path, author_id, user_id) VALUES('Ботинки для сноуборда DC Mutiny Charocal', 3, 10999, 'img/lot-4.jpg', 1, 2);
INSERT INTO lot(title, cat_id, start_price, img_path, author_id, user_id) VALUES('Куртка для сноуборда DC Mutiny Charocal', 4, 7500, 'img/lot-5.jpg', 2, 3);
INSERT INTO lot(title, cat_id, start_price, img_path, author_id, user_id) VALUES('Маска Oakley Canopy', 6, 5400, 'img/lot-6.jpg', 3, 1);

-- Rates
INSERT INTO rate(amount, user_id, lot_id) VALUES(100, 1, 2);
INSERT INTO rate(amount, user_id, lot_id) VALUES(150, 2, 2);
INSERT INTO rate(amount, user_id, lot_id) VALUES(300, 3, 2);

-- Select all
SELECT title FROM category;

-- Get new opened lots
SELECT l.title, l.start_price, l.img_path,  c.title FROM lot l
JOIN category c ON l.cat_id = c.id
ORDER BY created DESC;

-- Show lot by id
SELECT l.title, c.title FROM lot l
JOIN category c ON l.cat_id = c.id WHERE l.id = 5;

-- Show latest rates by lot id
SELECT amount FROM rate WHERE lot_id = 2 ORDER BY created DESC;
