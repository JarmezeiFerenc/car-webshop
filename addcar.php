<?php
session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['isAdmin']) {
    header("Location: index.php");
    exit;
}
require_once 'storage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = (int)$_POST['year'];
    $transmission = $_POST['transmission'];
    $fuel_type = $_POST['fuel_type'];
    $passengers = (int)$_POST['passengers'];
    $daily_price_huf = (int)$_POST['daily_price_huf'];
    $image = $_POST['image'];

    $newCar = [
        "brand" => $brand,
        "model" => $model,
        "year" => $year,
        "transmission" => $transmission,
        "fuel_type" => $fuel_type,
        "passengers" => $passengers,
        "daily_price_huf" => $daily_price_huf,
        "image" => $image
    ];

    $storage = new Storage(new JsonIO('cars.json'));
    $storage->add($newCar);

    header("Location: index.php");
    exit;
} else {
    header("Location: newcar.php");
    exit;
}
?>