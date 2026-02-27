<?php
session_start();
$name = $_GET['name'] ?? 'Nincs megadva';
$img = $_GET['img'] ?? 'Nincs megadva';
$passengers = $_GET['passengers'] ?? 'Nincs megadva';
$transmission = $_GET['transmission'] ?? 'Nincs megadva';
$fuel = $_GET['fuel'] ?? 'Nincs megadva';
$year = $_GET['year'] ?? 'Nincs megadva';
$price = $_GET['price'] ?? 'Nincs megadva';
$id = $_GET['id'] ?? 'Nincs megadva';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autó részletei</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <h1><a href="index.php">iKarRental</a></h1>
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
        <div style="margin-bottom: 15px;">
            <a href="index.php" class="back-link">&larr; Vissza a főoldalra</a>
        </div>
        <div id="auto">
            <div>
                <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($name) ?>">
            </div>
            <div id="auto-text">
                <h1><?= htmlspecialchars($name) ?></h1>
                <ul>
                    <li>Üzemanyag: <?= htmlspecialchars($fuel) ?></li>
                    <li>Váltó: <?= htmlspecialchars($transmission) ?></li>
                    <li>Gyártási év: <?= htmlspecialchars($year) ?></li>
                    <li>Férőhelyek száma: <?= htmlspecialchars($passengers) ?></li>
                </ul>
                <div>
                    <span><?= htmlspecialchars($price) ?> Ft</span><span>/nap</span>
                </div>
                <div>
                <form method="get" action="reservecar.php">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="hidden" name="name" value="<?= $name ?>">
                    <div>
                        <label for="from_date">Mettől</label>
                        <input type="date" id="from_date" name="from_date" required>
                    </div>
                    <div>
                        <label for="to_date">Meddig</label>
                        <input type="date" id="to_date" name="to_date" required>
                    </div>
                    <button type="submit">Foglalás</button>
                </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
