<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user']) || !$_SESSION['user']['isAdmin']) {
    die("Hozzáférés megtagadva.");
}

$carStorage = new Storage(new JsonIO('cars.json'));

if (!isset($_GET['car_id'])) {
    die("Hiányzó autó azonosító.");
}

$car_index = $_GET['car_id']-1;

$cars = $carStorage->findAll();
if (!isset(array_values($cars)[$car_index])) {
    die("Az autó nem található.");
}

$car = array_values($cars)[$car_index];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedCar = [
        'id' => $car['id'], 
        'brand' => $_POST['brand'],
        'model' => $_POST['model'],
        'year' => (int)$_POST['year'],
        'transmission' => $_POST['transmission'],
        'fuel_type' => $_POST['fuel_type'],
        'passengers' => (int)$_POST['passengers'],
        'daily_price_huf' => (int)$_POST['daily_price_huf'],
        'image' => $_POST['image']
    ];

    $carStorage->update($car_index, $updatedCar);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Autó adatainak módosítása</title>
</head>
<body>
    <h1>Autó adatainak módosítása</h1>
    <form method="post">
        <label for="brand">Márka:</label>
        <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required>
        
        <label for="model">Modell:</label>
        <input type="text" id="model" name="model" value="<?= htmlspecialchars($car['model']) ?>" required>
        
        <label for="year">Évjárat:</label>
        <input type="number" id="year" name="year" value="<?= htmlspecialchars($car['year']) ?>" required>
        
        <label for="transmission">Váltó:</label>
        <select id="transmission" name="transmission" required>
            <option value="Manual" <?= $car['transmission'] === 'Manual' ? 'selected' : '' ?>>Manuális</option>
            <option value="Automatic" <?= $car['transmission'] === 'Automatic' ? 'selected' : '' ?>>Automata</option>
        </select>
        
        <label for="fuel_type">Üzemanyag:</label>
        <select id="fuel_type" name="fuel_type" required>
            <option value="Petrol" <?= $car['fuel_type'] === 'Petrol' ? 'selected' : '' ?>>Benzin</option>
            <option value="Diesel" <?= $car['fuel_type'] === 'Diesel' ? 'selected' : '' ?>>Dízel</option>
            <option value="Electric" <?= $car['fuel_type'] === 'Electric' ? 'selected' : '' ?>>Elektromos</option>
        </select>
        
        <label for="passengers">Férőhelyek száma:</label>
        <input type="number" id="passengers" name="passengers" value="<?= htmlspecialchars($car['passengers']) ?>" required>
        
        <label for="daily_price_huf">Napi ár (Ft):</label>
        <input type="number" id="daily_price_huf" name="daily_price_huf" value="<?= htmlspecialchars($car['daily_price_huf']) ?>" required>
        
        <label for="image">Kép URL:</label>
        <input type="text" id="image" name="image" value="<?= htmlspecialchars($car['image']) ?>" required>
        
        <button type="submit">Mentés</button>
    </form>
</body>
</html>