USE yeticave;

-- Categories
INSERT INTO category (title) VALUES('Доски и лыжи');
INSERT INTO category (title) VALUES('Крепления');
INSERT INTO category (title) VALUES('Ботинки');
INSERT INTO category (title) VALUES('Одежда');
INSERT INTO category (title) VALUES('Инструменты');
INSERT INTO category (title) VALUES('Разное');

-- Users
INSERT INTO user(name, email, passwd, contact) VALUES('Игорь', 'igor@test.mail', 'test', 'work');
INSERT INTO user(name, email, passwd, contact) VALUES('Иван', 'ivan@test.mail', 'test', 'home');
INSERT INTO user(name, email, passwd, contact) VALUES('John', 'john@test.mail', 'test', 'work');

-- Lots
INSERT INTO lot(title, cat_id, start_price, img_path, rate_step, author_id, winner_id, end_date) VALUES('2014 Rossignol District Snowboard', 1, 10999, 'img/lot-1.jpg', 100, 1, 2, '2019-03-19');
INSERT INTO lot(title, cat_id, start_price, img_path, rate_step, author_id, winner_id, end_date) VALUES('DC Ply Mens 2016/2017 Snowboard', 1, 159999, 'img/lot-2.jpg', 100,  2, 3, '2019-03-20');
INSERT INTO lot(title, cat_id, start_price, img_path, rate_step, author_id, winner_id, end_date) VALUES('Крепления Union Contact Pro 2015 года размер L/XL', 2, 8000, 'img/lot-3.jpg', 100,  3, 1, '2019-03-22');
INSERT INTO lot(title, cat_id, start_price, img_path, rate_step, author_id, winner_id, end_date) VALUES('Ботинки для сноуборда DC Mutiny Charocal', 3, 10999, 'img/lot-4.jpg', 100,  1, 2, '2019-03-18');
INSERT INTO lot(title, cat_id, start_price, img_path, rate_step, author_id, winner_id, end_date) VALUES('Куртка для сноуборда DC Mutiny Charocal', 4, 7500, 'img/lot-5.jpg', 100,  2, 3, '2019-03-17');
INSERT INTO lot(title, cat_id, start_price, img_path, rate_step, author_id, winner_id, end_date) VALUES('Маска Oakley Canopy', 6, 5400, 'img/lot-6.jpg', 100, 3, 1, '2019-03-07');

-- Rates
INSERT INTO rate(amount, user_id, lot_id) VALUES(11999, 1, 1);
INSERT INTO rate(amount, user_id, lot_id) VALUES(11500, 2, 1);
INSERT INTO rate(amount, user_id, lot_id) VALUES(160000, 3, 2);
INSERT INTO rate(amount, user_id, lot_id) VALUES(160100, 1, 2);
INSERT INTO rate(amount, user_id, lot_id) VALUES(8100, 3, 3);
INSERT INTO rate(amount, user_id, lot_id) VALUES(8500, 1, 3);

-- Select all
SELECT * FROM category;

-- Get new opened lots
SELECT l.id, l.title, l.start_price, l.img_path, COALESCE(MAX(r.amount), l.start_price) AS price , c.title
FROM lot l
JOIN category c ON l.cat_id = c.id
LEFT JOIN rate r ON r.lot_id = l.id
WHERE l.end_date > NOW()
GROUP BY l.id
ORDER BY l.created DESC;

-- Show lot by id
SELECT l.title, c.title FROM lot l
JOIN category c ON l.cat_id = c.id WHERE l.id = 5;

-- Update lot name by id
UPDATE lot SET title = '2018 Rossignol District Snowboard' WHERE id = 1;

-- Show latest rates by lot id
SELECT amount FROM rate WHERE lot_id = 2 ORDER BY created DESC;
