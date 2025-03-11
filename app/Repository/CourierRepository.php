<?php
namespace App\Repository;

use App\Model\Courier;

class CourierRepository extends Repository
{
    protected string $table = 'couriers';
    protected string $primaryKey = 'id';
    protected string $modelClass = Courier::class;
}
