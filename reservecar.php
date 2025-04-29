<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user']['email'];
$car_name = $_GET['name'] ?? null;
$car_id = $_GET['id'] ?? null;
$from_date = $_GET['from_date'] ?? null;
$to_date = $_GET['to_date'] ?? null;
$message = '';

$reservationStorage = new Storage(new JsonIO('reservations.json'));
$reservations = $reservationStorage->findAll();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $car_id && $from_date && $to_date) {
    if ($from_date > $to_date) {
        $message = "A foglalás vége nem lehet előbb, mint a foglalás eleje!";
    } else {
        $isAvailable = true;
        foreach ($reservations as $reservation) {
            if ($reservation['car_id'] === $car_id && !($to_date < $reservation['from_date'] || $from_date > $reservation['to_date'])) {
                $isAvailable = false;
                $message = "Az autó már foglalt a megadott időszakban!";
                break;
            }
        }

        if ($isAvailable) {
            $new_reservation = [
                'id' => uniqid(),
                'from_date' => $from_date,
                'to_date' => $to_date,
                'user_email' => $user_email,
                'car_id' => $car_id,
            ];

            $reservationStorage->add($new_reservation);
            $message = "Sikeres foglalás! Köszönjük, hogy foglaltál! Az adatok mentésre kerültek.";
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foglalás</title>
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
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && $car_id && $from_date && $to_date && $isAvailable): ?>
        <div>
            <h3>Foglalás Részletei:</h3>
            <ul>
                <li><strong>Autó neve:</strong> <?= htmlspecialchars($car_name) ?></li>
                <li><strong>Email:</strong> <?= htmlspecialchars($user_email) ?></li>
                <li><strong>Foglalás kezdete:</strong> <?= htmlspecialchars($from_date) ?></li>
                <li><strong>Foglalás vége:</strong> <?= htmlspecialchars($to_date) ?></li>
            </ul>
        </div>
    <?php endif; ?>
    </main>
</body>
