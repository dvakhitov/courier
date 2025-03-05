-- Таблица курьеров
CREATE TABLE couriers (
                          id SERIAL PRIMARY KEY,
                          full_name VARCHAR(255) NOT NULL
);

-- Таблица регионов
CREATE TABLE regions (
                         id SERIAL PRIMARY KEY,
                         name VARCHAR(255) NOT NULL,
                         travel_duration INT NOT NULL
);
COMMENT ON COLUMN regions.travel_duration IS 'Длительность поездки в дни';

-- Таблица расписания поездок
CREATE TABLE trips (
                       id SERIAL PRIMARY KEY,
                       courier_id INT NOT NULL,
                       region_id INT NOT NULL,
                       departure_date DATE NOT NULL,
                       arrival_date DATE NOT NULL,
                       CONSTRAINT fk_courier FOREIGN KEY (courier_id) REFERENCES couriers(id),
                       CONSTRAINT fk_region FOREIGN KEY (region_id) REFERENCES regions(id)
);
