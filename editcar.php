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

$car_id = $_GET['car_id'];
$car = $carStorage->findById($car_id);

if (!$car) {
    die("Az autó nem található.");
}

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

    $carStorage->update($car_id, $updatedCar);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
    <title>Autó adatainak módosítása</title>
</head>
<body>
    <header>
        <h1><a href="index.php" style="color: white; text-decoration: none;">iKarRental</a></h1>
        <nav>
            <a href="profileadmin.php"><span class="profile">Üdv, <?= htmlspecialchars($_SESSION['user']['username']); ?>!</span></a>
            <a href="logout.php"><button>Kijelentkezés</button></a>
            <a href="newcar.php"><button type="button">Új Autó Hozzáadása</button></a>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <h2>Autó adatainak módosítása</h2>
            <p class="subtitle"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></p>
            <?php if (!empty($car['image'])): ?>
                <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>" class="edit-car-preview">
            <?php endif; ?>
            <form method="post" class="styled-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="brand">Márka:</label>
                        <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="model">Modell:</label>
                        <input type="text" id="model" name="model" value="<?= htmlspecialchars($car['model']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="year">Évjárat:</label>
                        <input type="number" id="year" name="year" value="<?= htmlspecialchars($car['year']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="passengers">Férőhelyek száma:</label>
                        <input type="number" id="passengers" name="passengers" value="<?= htmlspecialchars($car['passengers']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="transmission">Váltó:</label>
                        <select id="transmission" name="transmission" required>
                            <option value="Manual" <?= $car['transmission'] === 'Manual' ? 'selected' : '' ?>>Manuális</option>
                            <option value="Automatic" <?= $car['transmission'] === 'Automatic' ? 'selected' : '' ?>>Automata</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fuel_type">Üzemanyag:</label>
                        <select id="fuel_type" name="fuel_type" required>
                            <option value="Petrol" <?= $car['fuel_type'] === 'Petrol' ? 'selected' : '' ?>>Benzin</option>
                            <option value="Diesel" <?= $car['fuel_type'] === 'Diesel' ? 'selected' : '' ?>>Dízel</option>
                            <option value="Electric" <?= $car['fuel_type'] === 'Electric' ? 'selected' : '' ?>>Elektromos</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="daily_price_huf">Napi ár (Ft):</label>
                    <input type="number" id="daily_price_huf" name="daily_price_huf" value="<?= htmlspecialchars($car['daily_price_huf']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="image">Kép URL:</label>
                    <input type="text" id="image" name="image" value="<?= htmlspecialchars($car['image']) ?>" required>
                </div>
                <div class="form-actions">
                    <a href="index.php"><button type="button" class="btn-secondary">Mégse</button></a>
                    <button type="submit">Mentés</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>