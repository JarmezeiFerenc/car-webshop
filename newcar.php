<?php
session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['isAdmin']) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autó Létrehozása</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <h1><a href="index.php" style="color: white; text-decoration: none;">iKarRental</a></h1>
        <nav>
            <a href="profileadmin.php"><span class="profile">Üdv, <?= htmlspecialchars($_SESSION['user']['username']); ?>!</span></a>
            <a href="logout.php"><button>Kijelentkezés</button></a>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <h2>Új Autó Hozzáadása</h2>
            <form action="addcar.php" method="POST" class="styled-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="brand">Márka:</label>
                        <input type="text" id="brand" name="brand" required>
                    </div>
                    <div class="form-group">
                        <label for="model">Modell:</label>
                        <input type="text" id="model" name="model" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="year">Gyártási Év:</label>
                        <input type="number" id="year" name="year" required>
                    </div>
                    <div class="form-group">
                        <label for="passengers">Férőhelyek Száma:</label>
                        <input type="number" id="passengers" name="passengers" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="transmission">Váltó:</label>
                        <select id="transmission" name="transmission" required>
                            <option value="Manual">Manuális</option>
                            <option value="Automatic">Automata</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fuel_type">Üzemanyag Típus:</label>
                        <select id="fuel_type" name="fuel_type" required>
                            <option value="Petrol">Benzin</option>
                            <option value="Diesel">Dízel</option>
                            <option value="Electric">Elektromos</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="daily_price_huf">Napi Ár (HUF):</label>
                    <input type="number" id="daily_price_huf" name="daily_price_huf" required>
                </div>
                <div class="form-group">
                    <label for="image">Kép URL:</label>
                    <input type="url" id="image" name="image" required>
                </div>
                <div class="form-actions">
                    <a href="index.php"><button type="button" class="btn-secondary">Mégse</button></a>
                    <button type="submit">Hozzáadás</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>