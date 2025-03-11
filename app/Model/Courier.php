<?php
namespace App\Model;

use App\Attribute\Column;

class Courier
{
    #[Column(name: 'id', primary: true)]
    private ?int $id = null;

    #[Column(name: 'full_name')]
    private string $fullName = '';

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getFullName(): string {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void {
        $this->fullName = $fullName;
    }
}
