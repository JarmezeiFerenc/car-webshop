<?php
session_start();
require_once 'usermanagement.php';

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$userManager = new usermanagement('users.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $message = $userManager->register($username, $email, $password, $admin = FALSE);
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Regisztráció</title>
</head>
<body id="reg">
    <div class="auth-container">
        <h1><a href="index.php" style="color: white; text-decoration: none;">iKarRental</a></h1>
        <h2>Regisztráció</h2>
        <?php if (isset($message)): ?>
            <p class="<?= $message === 'Sikeres regisztráció!' ? 'success-msg' : 'error-msg' ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Regisztráció</button>
        </form>
        <p class="auth-link">Van már fiókod? <a href="login.php">Jelentkezz be!</a></p>
        <a href="index.php" class="back-link">&larr; Vissza a főoldalra</a>
    </div>
</body>
</html>