<?php

namespace App\Repository;

use App\Model\Trip;
use PDO;

class TripRepository extends Repository
{
    protected string $table = 'trips';
    protected string $primaryKey = 'id';
    protected string $modelClass = Trip::class;

    protected array $relations = [
        'courier' => [
            'model' => \App\Model\Courier::class,
            'prefix' => 'courier_obj_'
        ],
        'region' => [
            'model' => \App\Model\Region::class,
            'prefix' => 'region_obj_'
        ],
    ];

    /**
     * Возвращает список поездок с объединением (JOIN) связанных таблиц.
     * Обратите внимание: SQL-запрос использует алиасы с префиксами, как задано в $relations.
     * @throws \Exception
     */
    public function findAll(string $orderBy = 'departure_date', string $direction = 'ASC'): array
    {
        $db = \App\Config\DB::getConnection();
        $stmt = $db->prepare(
            "
            SELECT 
                t.id,
                t.courier_id,
                t.region_id,
                t.departure_date,
                t.arrival_date,
                c.id AS courier_obj_id,
                c.full_name AS courier_obj_full_name,
                r.id AS region_obj_id,
                r.name AS region_obj_name,
                r.travel_duration AS region_obj_travel_duration
            FROM trips t
            JOIN couriers c ON t.courier_id = c.id
            JOIN regions r ON t.region_id = r.id
            ORDER BY t.$orderBy $direction;
        "
        );
        $stmt->execute();
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $this->mapDataToModel($row);
        }
        return $results;
    }

    /**
     * @param int $courierId
     * @param string $date
     * @return Trip[]
     * @throws \Exception
     */
    public function findByCourierAndDate(int $courierId, string $date): array {
        $db = \App\Config\DB::getConnection();
        $stmt = $db->prepare("
        SELECT 
            t.id,
            t.courier_id,
            t.region_id,
            t.departure_date,
            t.arrival_date,
            c.full_name AS courier_obj_full_name,
            r.name AS region_obj_name,
            r.travel_duration AS region_obj_travel_duration
        FROM trips t
        JOIN couriers c ON t.courier_id = c.id
        JOIN regions r ON t.region_id = r.id
        WHERE t.courier_id = :courier_id
          AND :date BETWEEN t.departure_date AND t.arrival_date
        ORDER BY t.id;
    ");
        $stmt->execute(['courier_id' => $courierId, 'date' => $date]);
        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $this->mapDataToModel($row);
        }

        return $results;
    }
}
