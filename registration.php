<?php
require_once 'usermanagement.php';

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
    <h1>Regisztráció</h1>
    <?php if (isset($message)) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>
    <form method="POST">
        <label for="username">Felhasználónév:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Jelszó:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Regisztráció</button>
    </form>
</body>
</html>