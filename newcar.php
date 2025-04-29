

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
    <h1>Új Autó Hozzáadása</h1>
    <form action="addcar.php" method="POST">
        <label for="brand">Márka:</label>
        <input type="text" id="brand" name="brand" required><br><br>

        <label for="model">Modell:</label>
        <input type="text" id="model" name="model" required><br><br>

        <label for="year">Gyártási Év:</label>
        <input type="number" id="year" name="year" required><br><br>

        <label for="transmission">Váltó:</label>
        <select id="transmission" name="transmission" required>
            <option value="Manual">Manuális</option>
            <option value="Automatic">Automata</option>
        </select><br><br>

        <label for="fuel_type">Üzemanyag Típus:</label>
        <select id="fuel_type" name="fuel_type" required>
            <option value="Petrol">Benzin</option>
            <option value="Diesel">Dízel</option>
            <option value="Electric">Elektromos</option>
        </select><br><br>

        <label for="passengers">Férőhelyek Száma:</label>
        <input type="number" id="passengers" name="passengers" required><br><br>

        <label for="daily_price_huf">Napi Ár (HUF):</label>
        <input type="number" id="daily_price_huf" name="daily_price_huf" required><br><br>

        <label for="image">Kép URL:</label>
        <input type="url" id="image" name="image" required><br><br>

        <button type="submit">Hozzáadás</button>
    </form>
</body>
</html>