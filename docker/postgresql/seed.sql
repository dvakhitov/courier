-- Очистка таблиц (если необходимо)
TRUNCATE TABLE trips;
TRUNCATE TABLE couriers;
TRUNCATE TABLE regions;

-- Курьеры (не менее 10 человек)
INSERT INTO couriers (full_name) VALUES
                                     ('Иванов Иван Иванович'),
                                     ('Петров Петр Петрович'),
                                     ('Сидоров Сидор Сидорович'),
                                     ('Козлов Алексей Алексеевич'),
                                     ('Морозова Ольга Петровна'),
                                     ('Смирнова Елена Сергеевна'),
                                     ('Волков Дмитрий Николаевич'),
                                     ('Новикова Анна Владимировна'),
                                     ('Фёдоров Михаил Фёдорович'),
                                     ('Егорова Наталья Александровна');

-- Регионы
-- В поле travel_duration указываем время в пути (в часах) при средней скорости 70 км/ч (округлено)
INSERT INTO regions (name, travel_duration) VALUES
                                                ('Санкт-Петербург', 10),    -- 710 км → ≈ 10 часов
                                                ('Уфа', 26),                -- 1800 км → ≈ 26 часов
                                                ('Нижний Новгород', 6),      -- 400 км → ≈ 6 часов
                                                ('Владимир', 3),            -- 180 км → ≈ 3 часов
                                                ('Кострома', 6),            -- 450 км → ≈ 6 часов
                                                ('Екатеринбург', 30),       -- 2100 км → 30 часов
                                                ('Ковров', 4),              -- 250 км → ≈ 4 часов
                                                ('Воронеж', 7),             -- 520 км → ≈ 7 часов
                                                ('Самара', 14),             -- 1000 км → ≈ 14 часов
                                                ('Астрахань', 17);          -- 1200 км → ≈ 17 часов

-- Расписание поездок за три месяца.
-- Время прибытия вычисляется как departure_date + travel_duration (в часах).
INSERT INTO trips (courier_id, region_id, departure_date, arrival_date) VALUES
-- Санкт-Петербург
(1, 1, '2025-03-01 08:00:00', '2025-03-01 18:00:00'),
-- Уфа
(2, 2, '2025-03-05 07:00:00', '2025-03-06 09:00:00'),
-- Нижний Новгород
(3, 3, '2025-03-10 09:00:00', '2025-03-10 15:00:00'),
-- Владимир
(4, 4, '2025-03-15 06:30:00', '2025-03-15 09:30:00'),
-- Кострома
(5, 5, '2025-04-01 08:00:00', '2025-04-01 14:00:00'),
-- Екатеринбург
(6, 6, '2025-04-05 07:30:00', '2025-04-06 13:30:00'),
-- Ковров
(7, 7, '2025-04-10 08:00:00', '2025-04-10 12:00:00'),
-- Воронеж
(8, 8, '2025-05-01 09:30:00', '2025-05-01 16:30:00'),
-- Самара
(9, 9, '2025-05-05 08:00:00', '2025-05-05 22:00:00'),
-- Астрахань
(10, 10, '2025-05-10 07:00:00', '2025-05-11 00:00:00');
