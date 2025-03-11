<?php

namespace App\Controller;

use App\Model\Trip;
use App\Repository\TripRepository;
use App\Repository\RegionRepository;
use App\Repository\CourierRepository;
use App\Utils\InputValidator;

class TripController
{
    public function __construct(
        protected TripRepository $tripRepo,
        protected RegionRepository $regionRepo,
        protected CourierRepository $courierRepo,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function scheduleForm(): void
    {
        $regions = $this->regionRepo->findAll();
        $couriers = $this->courierRepo->findAll();
        include __DIR__ . '/../Views/trips.php';
    }

    public function addTrip(): void
    {
        $courierId = InputValidator::getInt('courier_id');
        $regionId = InputValidator::getInt('region_id');
        $departureDate = InputValidator::validateDate('departure_date');

        if ($courierId === null || $regionId === null || $departureDate === null) {
            echo json_encode(['status' => 'error', 'message' => 'Неверные входные данные']);
            exit;
        }

        $region = $this->regionRepo->find($regionId);
        if (!$region) {
            echo json_encode(['status' => 'error', 'message' => 'Неверный регион']);
            exit;
        }

        $trips = $this->tripRepo->findByCourierAndDate($courierId, $departureDate);

        if (count($trips) > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => sprintf(
                    'Курьеру %s уже назначена поездка на указанную дату %s',
                    $trips[0]->getCourier()->getFullName(),
                    $departureDate
                )
            ]);
            exit;
        }

        $arrivalDate = date('Y-m-d', strtotime($departureDate . ' +' . $region->getTravelDuration() . ' hours'));

        $trip = new Trip($courierId, $regionId, $departureDate, $arrivalDate);
        $result = $this->tripRepo->save($trip);

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка сохранения']);
        }
    }

    /**
     * @throws \Exception
     */
    public function listTrips(): void
    {
        $trips = $this->tripRepo->findAll();

        include __DIR__ . '/../Views/listTrips.php';
    }
}
