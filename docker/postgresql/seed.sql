-- Очистка таблиц с сбросом идентификаторов
TRUNCATE TABLE trips RESTART IDENTITY CASCADE;
TRUNCATE TABLE couriers RESTART IDENTITY CASCADE;
TRUNCATE TABLE regions RESTART IDENTITY CASCADE;

-- Курьеры (10 человек)
INSERT INTO couriers (full_name)
VALUES ('Иванов Иван Иванович'),
       ('Петров Петр Петрович'),
       ('Сидоров Сидор Сидорович'),
       ('Козлов Алексей Алексеевич'),
       ('Морозова Ольга Петровна'),
       ('Смирнова Елена Сергеевна'),
       ('Волков Дмитрий Николаевич'),
       ('Новикова Анна Владимировна'),
       ('Фёдоров Михаил Фёдорович'),
       ('Егорова Наталья Александровна');

-- Регионы (travel_duration указывается в часах для полной поездки туда и обратно)
INSERT INTO regions (name, travel_duration)
VALUES ('Санкт-Петербург', 10),
       ('Уфа', 26),
       ('Нижний Новгород', 6),
       ('Владимир', 3),
       ('Кострома', 6),
       ('Екатеринбург', 30),
       ('Ковров', 4),
       ('Воронеж', 7),
       ('Самара', 14),
       ('Астрахань', 17);

-- Генерация расписания поездок для каждого курьера на три месяца, начиная с сегодняшнего дня в 08:00.
DO $$ DECLARE
        courier_id INTEGER;
        depart_ts TIMESTAMP;
        depart_epoch DOUBLE PRECISION;
        new_depart_epoch DOUBLE PRECISION;
        end_epoch DOUBLE PRECISION;
        region_id INTEGER;
        travel_hours INTEGER;
        arrival_epoch DOUBLE PRECISION;
BEGIN
    -- Конечная дата – начало текущего дня + 90 дней (в секундах)
     end_epoch := extract(epoch from date_trunc('day', now())) + (90 * 24 * 3600);

FOR courier_id IN 1..10 LOOP
    -- Начинаем с сегодняшнего дня в 08:00
    depart_ts := date_trunc('day', now())::timestamp + '08:00:00'::time;
    depart_epoch := extract(epoch from depart_ts);

WHILE depart_epoch < end_epoch LOOP
      -- Выбираем случайный регион (id от 1 до 10)
      region_id := floor(random() * 10 + 1)::int;

      -- Получаем travel_duration для выбранного региона (в часах)
SELECT travel_duration
INTO travel_hours
FROM regions
WHERE id = region_id;

-- Рассчитываем время прибытия: departure + (travel_hours * 3600) секунд
arrival_epoch := depart_epoch + (travel_hours * 3600);

INSERT INTO trips (courier_id, region_id, departure_date, arrival_date)
VALUES (courier_id,
        region_id,
        to_timestamp(depart_epoch),
        to_timestamp(arrival_epoch));

-- Следующий выезд всегда завтра в 08:00:
IF arrival_epoch <= depart_epoch + 86400 THEN
        new_depart_epoch := depart_epoch + 86400;  -- завтра в 08:00
ELSE
        new_depart_epoch := extract(epoch from date_trunc('day', to_timestamp(arrival_epoch))) + (8 * 3600) + 86400;
END IF;

depart_epoch := new_depart_epoch;
END LOOP;
END LOOP;
END$$;

