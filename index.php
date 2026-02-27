<?php
    session_start();
    include_once('storage.php');
    $jsonIO = new JsonIO('cars.json');
    $storage = new Storage($jsonIO);

    //szűrés
    $cars = $storage->findAll();
    $seats = $_GET['seats'] ?? null;
    $date_from = $_GET['date_from'] ?? null;
    $date_to = $_GET['date_to'] ?? null;
    $gearbox = $_GET['gearbox'] ?? null;
    $price_min = $_GET['price_min'] ?? null;
    $price_max = $_GET['price_max'] ?? null;

    $reservationStorage = new Storage(new JsonIO('reservations.json'));
    $reservations = $reservationStorage->findAll();

    $filtered_cars = array_filter($cars, function ($car) use ($seats, $date_from, $date_to, $gearbox, $price_min, $price_max, $reservations) {
        $isAvailable = true;
        if ($date_from && $date_to) {
            foreach ($reservations as $reservation) {
                if ($reservation['car_id'] == $car['id']) {
                    $reservedFrom = strtotime($reservation['from_date']);
                    $reservedTo = strtotime($reservation['to_date']);
                    $requestedFrom = strtotime($date_from);
                    $requestedTo = strtotime($date_to);
    
                    if (!($requestedTo < $reservedFrom || $requestedFrom > $reservedTo)) {
                        $isAvailable = false;
                        break;
                    }
                }
            }
        }
    
        return $isAvailable
            && (!$seats || $car['passengers'] >= $seats)
            && (!$gearbox || strtoupper($car['transmission']) === strtoupper($gearbox))
            && (!$price_min || $car['daily_price_huf'] >= $price_min)
            && (!$price_max || $car['daily_price_huf'] <= $price_max);
    });
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <h1>iKarRental</h1>
        <nav>
        <?php if (isset($_SESSION['user'])): ?>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['isAdmin']): ?>
            <a href="profileadmin.php"><span class="profile">Üdv, <?= htmlspecialchars($_SESSION['user']['username']); ?>!</span></a>
            <?php else: ?>
                <a href="profile.php" ><span class="profile">Üdv, <?= htmlspecialchars($_SESSION['user']['username']); ?>!</span></a>
            <?php endif; ?>
            <a href="logout.php"><button>Kijelentkezés</button></a>
        <?php else: ?>
            <a href="login.php"><button>Bejelentkezés</button></a>
            <a href="registration.php"><button>Regisztráció</button></a>
        <?php endif; ?>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['isAdmin']): ?>
            <a href="newcar.php"><button type="button">Új Autó Hozzáadása</button></a>
        <?php endif; ?>
        </nav>
    </header>
        <main>
            <h2>Kölcsönözz autókat könnyedén!</h2>

        <form method="GET" class="filter-row">
        <div class="filter-item">
            <label for="seats">Férőhely</label>
            <div class="seats-control">
                <input type="number" id="seats" name="seats" value="0" min="0">
            </div>
        </div>
        <div class="filter-item">
            <label for="date-from">Dátum -tól</label>
            <input type="date" id="date-from" name="date_from">
        </div>
        <div class="filter-item">
            <label for="date-to">Dátum -ig</label>
            <input type="date" id="date-to" name="date_to">
        </div>
        <div class="filter-item">
            <label for="gearbox">Váltó típusa</label>
            <select id="gearbox" name="gearbox">
                <option value="">Mindegy</option>
                <option value="manual">Manuális</option>
                <option value="automatic">Automata</option>
            </select>
        </div>
        <div class="filter-item">
            <label for="price-min">Ár (Ft) - Minimum</label>
            <input type="number" id="price-min" name="price_min" placeholder="14000" min="0">
        </div>
        <div class="filter-item">
            <label for="price-max">Ár (Ft) - Maximum</label>
            <input type="number" id="price-max" name="price_max" placeholder="21000" min="0">
        </div>
        <div class="filter-item">
            <button type="submit" id="filter-button">Szűrés</button>
        </div>
    </form> 
        <div class="row g-3">
            <?php foreach ($filtered_cars as $car): ?>
            <div class="col-md-3">
                <div class="card">
                    <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>" />
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['isAdmin']): ?>
                        <div class="d-flex justify-content-between mb-2">
                        <form action="deletecar.php" method="POST" onsubmit="return confirm('Biztosan törölni szeretnéd ezt az autót?');">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <button type="submit" class="btn btn-danger">Törlés</button>
                        </form>
                        <form action="editcar.php" method="GET">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <button type="submit" class="btn btn-outline-light">Szerkesztés</button>
                        </form>
                        </div>
                    <?php endif; ?>
                    <h5><?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['model']) ?></h5>
                    <p><?= htmlspecialchars($car['passengers']) ?> férőhely - <?= htmlspecialchars($car['transmission']) ?></p>
                    <div class="card-footer">
                        <span>Ár: <?= htmlspecialchars($car['daily_price_huf']) ?> Ft/nap</span>
                        <a href="auto.php?name=<?= $car['brand'] ?> <?= $car['model'] ?>&img=<?= $car['image'] ?>&id=<?= $car['id'] ?>&passengers=<?= $car['passengers'] ?>&transmission=<?= $car['transmission'] ?>&fuel=<?= $car['fuel_type'] ?>&year=<?= $car['year'] ?>&price=<?= $car['daily_price_huf'] ?>"><button>Foglalás</button></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>