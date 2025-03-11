<?php
namespace App\Attribute;

use Attribute as BaseAttribute ;

#[BaseAttribute(BaseAttribute::TARGET_PROPERTY)]
class Column
{
    public function __construct(
        public string $name,
        public bool $primary = false
    ) {}
}
