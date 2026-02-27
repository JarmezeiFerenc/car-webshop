<?php
session_start();
require_once 'storage.php'; 

if (!isset($_SESSION['user']) || !$_SESSION['user']['isAdmin']) {
    die("Hozzáférés megtagadva.");
}

if (!isset($_POST['car_id'])) {
    die("Hiányzó autó azonosító.");
}

$carStorage = new Storage(new JsonIO('cars.json'));

$car_id = $_POST['car_id'];
$carStorage->delete($car_id);

header("Location: index.php");
exit;