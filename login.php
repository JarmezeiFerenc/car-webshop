<?php
require_once 'usermanagement.php';

session_start(); 

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

        echo "Sikeres bejelentkezés! Üdvözlünk, " . htmlspecialchars($user['username']) . "!";
        if ($user['isAdmin']) {
            echo " (Admin)";
        }

        header("Location: index.php");
    } else {
        echo $user; 
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
    <h1>Bejelentkezés</h1>
        <form method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Bejelentkezés</button>
        </form>
</body>
</html>