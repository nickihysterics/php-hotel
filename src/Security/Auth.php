<?php

declare(strict_types=1);

namespace App\Security;

use PDO;

final class Auth
{
    public function __construct(private PDO $pdo)
    {
    }

    public function attempt(string $login, string $password): bool
    {
        $stmt = $this->pdo->prepare('SELECT id, login, password_hash FROM users WHERE login = :login');
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        $this->loginUser((int) $user['id'], (string) $user['login']);

        return true;
    }

    public function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function requireLogin(): void
    {
        if ($this->check()) {
            return;
        }

        header('Location: /admin/login.php');
        exit;
    }

    public function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_login']);
        session_regenerate_id(true);
    }

    private function loginUser(int $id, string $login): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        $_SESSION['user_login'] = $login;
    }
}
