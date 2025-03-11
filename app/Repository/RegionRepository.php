<?php
namespace App\Repository;

use App\Model\Region;

class RegionRepository extends Repository
{
    protected string $table = 'regions';
    protected string $primaryKey = 'id';
    protected string $modelClass = Region::class;
}
