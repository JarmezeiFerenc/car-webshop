<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user'])) {
    die('Előbb jelentkezz be!');
}

$user_email = $_SESSION['user']['email'];

$reservations_storage = new Storage(new JsonIO('reservations.json'));
$cars_storage = new Storage(new JsonIO('cars.json'));

$reservations = $reservations_storage->findAll();

foreach ($reservations as &$reservation) {
    $car = $cars_storage->findById($reservation['car_id'] -1);
    if ($car) {
        $reservation['car_name'] = $car['brand'] . ' ' . $car['model'];
        $reservation['daily_price_huf'] = $car['daily_price_huf'];
    } else {
        $reservation['car_name'] = 'Ismeretlen autó';
        $reservation['daily_price_huf'] = 0; 
    }
}
unset($reservation);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foglalásaim</title>
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
    <main class="profile-main">
        <?php if (empty($reservations)): ?>
            <p>Nincsenek foglalásai.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Autó</th>
                        <th>Mettől</th>
                        <th>Meddig</th>
                        <th>Napi ár</th>
                        <th>Teljes ár</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?= htmlspecialchars($reservation['car_name']) ?></td>
                            <td><?= htmlspecialchars($reservation['from_date']) ?></td>
                            <td><?= htmlspecialchars($reservation['to_date']) ?></td>
                            <td><?= $reservation['daily_price_huf'] ?> Ft</td>
                            <td>
                                <?php
                                $days = (strtotime($reservation['to_date']) - strtotime($reservation['from_date'])) / 86400;
                                $total_price = $days * $reservation['daily_price_huf'];
                                echo number_format($total_price, 0, ',', ' ') . ' Ft';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>