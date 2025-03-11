<?php
namespace App\Repository;

use App\Config\DB;
use App\Mapper\ModelMapper;
use PDO;

abstract class Repository
{
    protected string $table;

    protected string $modelClass;

    protected string $primaryKey = 'id';

    protected array $relations = [];

    /**
     * Преобразует массив данных из базы в объект модели с учетом связей.
     */
    protected function mapDataToModel(array $data): object
    {
        return ModelMapper::mapDataToModel($this->modelClass, $data, $this->relations);
    }

    /**
     * Преобразует объект модели в ассоциативный массив, используя атрибуты.
     */
    protected function mapModelToData(object $model): array
    {
        return ModelMapper::mapModelToData($model);
    }

    public function find(int $id): ?object
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        return $this->mapDataToModel($data);
    }

    public function findAll(): array
    {
        $db = DB::getConnection();
        $stmt = $db->query("SELECT * FROM {$this->table}");
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $this->mapDataToModel($row);
        }
        return $results;
    }

    public function save(object $model): bool
    {
        $db = DB::getConnection();
        $data = $this->mapModelToData($model);
        if (!isset($data[$this->primaryKey]) || $data[$this->primaryKey] === null) {
            unset($data[$this->primaryKey]);
        }
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->table, implode(', ', $columns), implode(', ', $placeholders));
        $stmt = $db->prepare($sql);
        $result = $stmt->execute($data);
        if ($result && (!isset($model->{$this->primaryKey}) || $model->{$this->primaryKey} === null)) {
            $id = (int)$db->lastInsertId();
            $setter = 'set' . ucfirst($this->primaryKey);
            if (method_exists($model, $setter)) {
                $model->{$setter}($id);
            }
        }
        return $result;
    }

    public function update(object $model): bool
    {
        $db = DB::getConnection();
        $data = $this->mapModelToData($model);
        if (!isset($data[$this->primaryKey]) || !$data[$this->primaryKey]) {
            throw new \Exception("Primary key value is required for update");
        }
        $id = $data[$this->primaryKey];
        unset($data[$this->primaryKey]);
        $columns = array_keys($data);
        $assignments = array_map(fn($col) => "$col = :$col", $columns);
        $sql = sprintf("UPDATE %s SET %s WHERE %s = :id", $this->table, implode(', ', $assignments), $this->primaryKey);
        $stmt = $db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }
}
