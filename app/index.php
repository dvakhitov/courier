<?php
require __DIR__ . '/vendor/autoload.php';

use App\Controller\TripController;
use Dotenv\Dotenv;


// Загружаем переменные из файла .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

$action = $_GET['action'] ?? 'form';
$controller = new TripController(
    new \App\Repository\TripRepository(),
    new \App\Repository\RegionRepository(),
    new \App\Repository\CourierRepository()
);

switch ($action) {
    case 'add':
        $controller->addTrip();
        break;
    case 'list':
        $controller->listTrips();
        break;
    default:
        $controller->scheduleForm();
        break;
}

function dump($value): void
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}