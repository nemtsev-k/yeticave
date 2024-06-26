USE yeticave;
INSERT INTO yeticave_categories
  (name, code)
VALUES ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'tools'),
       ('Разное', 'other');

INSERT INTO yeticave_users
  (date_create, email, name, password, contact)
VALUES ('2023-03-28', 'ivanivanov@mail.ru',
        'Ivan', 'qwerty', 'г. Екатеринбург'),
       ('2023-03-29', 'vityavitin@mail.ru',
        'Vitya', '12345', 'г. Москва'),
       ('2023-03-25', 'petyapetin@mail.ru',
        'Petya', '1234567', 'г. Москва');

INSERT INTO yeticave_lots
(date_create, title, description, image, price,
 finish_date, step, user_id, category_id)
VALUES ('2022-03-28', '2014 Rossignol District Snowboard',
        'Лучший в своем сегменте',
        'images/lot-1.jpg', 10999, '2023-04-24', 500, 1, 1),
       ('2022-03-30', 'DC Ply Mens 2016/2017 Snowboard',
        'Спортивный борд',
        'images/lot-2.jpg', 15999, '2023-04-25', 1000, 1, 1),
       ('2022-03-30', 'Крепления Union Contact Pro 2015 года размер L/XL',
        'Гарантия качества',
        'images/lot-3.jpg', 8000, '2023-04-27', 300, 2, 1),
       ('2022-03-31', 'Ботинки для сноуборда DC Mutiny Charocal',
        'Отличные боты',
        'images/lot-4.jpg', 10999, '2023-04-23', 500, 3, 3),
       ('2022-04-01', 'Куртка для сноуборда DC Mutiny Charocal',
        'Стиль и удобство',
        'images/lot-5.jpg', 7500, '2023-04-23', 300, 1, 4),
       ('2022-04-01', 'Маска Oakley Canopy',
        'Надежная защита',
        'images/lot-6.jpg', 5400, '2023-04-23', 300, 1, 6);

INSERT INTO yeticave_bets
  (date_create, price, user_id, lot_id)
VALUES ('2022-04-06', 11499, 2, 1),
       ('2022-04-06', 11999, 3, 1);



# Получить все категории
SELECT name
FROM yeticave_categories;

# Получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT l.title, l.price, l.image, MAX(b.price), c.name
FROM yeticave_lots l
       JOIN yeticave_categories c ON l.category_id = c.id
       LEFT JOIN yeticave_bets b ON l.id = b.lot_id
GROUP BY l.id, l.finish_date
ORDER BY l.finish_date DESC
LIMIT 3;

# Показать лот по его ID. Получите также название категории, к которой принадлежит лот;

SELECT l.date_create, l.title, l.image, l.price, c.name AS categorylo
FROM yeticave_lots l
       JOIN yeticave_categories c ON l.category_id = c.id
WHERE l.id = 4;

# Обновить название лота по его идентификатору;

UPDATE yeticave_lots
SET title = 'Курточка для сноуборда DC Mutiny Charocal'
WHERE id = 5;

# Получить список ставок для лота по его идентификатору с сортировкой по дате;

SELECT b.date_create, b.price, u.name, u.contact, l.title
FROM yeticave_bets b
       JOIN yeticave_users u ON b.user_id = u.id
       JOIN yeticave_lots l ON b.lot_id = l.id
WHERE l.id = 1
ORDER BY b.date_create DESC;
