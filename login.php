<?php
require_once 'usermanagement.php';

session_start(); 

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$userManager = new usermanagement('users.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $userManager->login($email, $password);
    if (is_array($user)) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'isAdmin' => $user['isAdmin']
        ];

        header("Location: index.php");
        exit;
    } else {
        $error = $user; 
    }
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Bejelentkezés</title>
</head>
<body id="login">
    <div class="auth-container">
        <h1><a href="index.php" style="color: white; text-decoration: none;">iKarRental</a></h1>
        <h2>Bejelentkezés</h2>
        <?php if (isset($error)): ?>
            <p class="error-msg"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Bejelentkezés</button>
        </form>
        <p class="auth-link">Nincs még fiókod? <a href="registration.php">Regisztrálj itt!</a></p>
        <a href="index.php" class="back-link">&larr; Vissza a főoldalra</a>
    </div>
</body>
</html>