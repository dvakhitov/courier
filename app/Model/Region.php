<?php
namespace App\Model;

use App\Attribute\Column;

class Region {
    #[Column(name: 'id', primary: true)]
    private ?int $id = null;

    #[Column(name: 'name')]
    private string $name;

    #[Column(name: 'travel_duration')]
    private int $travelDuration; // время в пути в часах

    public function __construct(string $name = '', int $travel_duration = 0)
    {
        $this->name = $name;
        $this->travelDuration = $travel_duration;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getTravelDuration(): int {
        return $this->travelDuration;
    }

    public function setTravelDuration(int $travelDuration): void {
        $this->travelDuration = $travelDuration;
    }
}
