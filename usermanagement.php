<?php

require_once 'Storage.php';

class usermanagement {
    private $storage;

    public function __construct($filename) {
        $io = new JsonIO($filename);
        $this->storage = new Storage($io);
    }

    public function register($username, $email, $password, $isAdmin = false): string {
        $existingUser = $this->storage->findOne(['email' => $email]);
        if ($existingUser) {
            return "Az e-mail cím már létezik.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $newUser = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'isAdmin' => $isAdmin
        ];
        $this->storage->add($newUser);

        return "Sikeres regisztráció!";
    }

    public function login($email, $password) {
        $user = $this->storage->findOne(['email' => $email]);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return "Helytelen e-mail vagy jelszó.";
    }

    public function listUsers(): array {
        return $this->storage->findAll();
    }

    public function isAdmin($email): bool {
        $user = $this->storage->findOne(['email' => $email]);
        return $user['isAdmin'] ?? false;
    }

    public function deleteUser($email): string {
        $user = $this->storage->findOne(['email' => $email]);
        if (!$user) {
            return "A felhasználó nem található.";
        }
        $this->storage->delete($user['id']);
        return "A felhasználó sikeresen törölve.";
    }
}