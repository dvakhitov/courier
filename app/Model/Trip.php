<?php
namespace App\Model;

use App\Attribute\Column;

class Trip
{
    #[Column(name: 'id', primary: true)]
    private ?int $id = null;

    #[Column(name: 'courier_id')]
    private int $courierId;

    #[Column(name: 'region_id')]
    private int $regionId;

    #[Column(name: 'departure_date')]
    private string $departureDate;

    #[Column(name: 'arrival_date')]
    private string $arrivalDate;

    private ?Courier $courier = null;

    private ?Region $region = null;

    public function __construct(
        int $courierId = 0,
        int $regionId = 0,
        string $departureDate = '',
        string $arrivalDate = ''
    ) {
        $this->courierId = $courierId;
        $this->regionId = $regionId;
        $this->departureDate = $departureDate;
        $this->arrivalDate = $arrivalDate;
    }

    public function getId(): ?int {
        return $this->id;
    }
    public function setId(?int $id): void {
        $this->id = $id;
    }
    public function getCourierId(): int {
        return $this->courierId;
    }
    public function setCourierId(int $courierId): void {
        $this->courierId = $courierId;
    }
    public function getRegionId(): int {
        return $this->regionId;
    }
    public function setRegionId(int $regionId): void {
        $this->regionId = $regionId;
    }
    public function getDepartureDate(): string {
        return $this->departureDate;
    }
    public function setDepartureDate(string $departureDate): void {
        $this->departureDate = $departureDate;
    }
    public function getArrivalDate(): string {
        return $this->arrivalDate;
    }
    public function setArrivalDate(string $arrivalDate): void {
        $this->arrivalDate = $arrivalDate;
    }

    public function getCourier(): ?Courier {
        return $this->courier;
    }
    public function setCourier(?Courier $courier): self {
        $this->courier = $courier;
        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): void
    {
        $this->region = $region;
    }
}
