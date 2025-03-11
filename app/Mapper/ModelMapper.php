<?php
namespace App\Mapper;

use App\Attribute\Column;
use ReflectionClass;

class ModelMapper
{
    /**
     * Преобразует массив данных из базы в объект модели.
     *
     * @param string $modelClass Полностью квалифицированное имя класса модели.
     * @param array  $data Ассоциативный массив данных, полученных из БД.
     * @param array  $relations Массив описания связей в формате:
     *               [ 'relationName' => [ 'model' => Fully\Qualified\Model::class, 'prefix' => 'alias_prefix_' ], ... ]
     * @return object
     * @throws \Exception
     */
    public static function mapDataToModel(string $modelClass, array $data, array $relations = []): object
    {
        $reflection = new ReflectionClass($modelClass);
        $model = $reflection->newInstance();

        // Маппинг простых полей на основании атрибутов
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Column::class);
            if (!empty($attributes)) {
                /** @var Column $columnAttr */
                $columnAttr = $attributes[0]->newInstance();
                $columnName = $columnAttr->name;
                if (array_key_exists($columnName, $data)) {
                    $setter = 'set' . ucfirst($property->getName());
                    $value = $data[$columnName];
                    if (method_exists($model, $setter)) {
                        $model->{$setter}($value);
                    } else {
                        $property->setAccessible(true);
                        $property->setValue($model, $value);
                    }
                }
            }
        }

        // Маппинг связанных объектов
        if (!empty($relations)) {
            foreach ($relations as $relationName => $relationConfig) {
                $prefix = $relationConfig['prefix'] ?? ($relationName . '_');
                $relatedData = [];
                // Собираем данные, ключи которых начинаются с префикса
                foreach ($data as $key => $value) {
                    if (strpos($key, $prefix) === 0) {
                        $relatedKey = substr($key, strlen($prefix));
                        $relatedData[$relatedKey] = $value;
                    }
                }
                if (!empty($relatedData)) {
                    $relatedModelClass = $relationConfig['model'];
                    // Рекурсивно создаем объект связанной модели
                    $relatedObject = self::mapDataToModel($relatedModelClass, $relatedData);
                    // Формируем имя сеттера для связи (например, setCourier для связи "courier")
                    $setter = 'set' . ucfirst(str_replace('_id', '', $relationName));
                    if (method_exists($model, $setter)) {
                        $model->{$setter}($relatedObject);
                    } else {
                        throw new \Exception("Model {$modelClass} does not have a setter method {$setter}() for relation {$relationName}");
                    }
                }
            }
        }

        return $model;
    }

    /**
     * Преобразует объект модели в ассоциативный массив, используя атрибуты.
     *
     * @param object $model
     * @return array
     */
    public static function mapModelToData(object $model): array
    {
        $data = [];
        $reflection = new ReflectionClass($model);
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Column::class);
            if (!empty($attributes)) {
                /** @var Column $columnAttr */
                $columnAttr = $attributes[0]->newInstance();
                $columnName = $columnAttr->name;
                $property->setAccessible(true);
                $value = $property->getValue($model);
                $data[$columnName] = $value;
            }
        }

        return $data;
    }
}
