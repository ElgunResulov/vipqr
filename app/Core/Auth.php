<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    private const SESSION_KEY = 'admin_id';

    public static function attempt(string $username, string $password): bool
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, username, email, password_hash FROM admins WHERE username = :username OR email = :email LIMIT 1'
        );
        $stmt->execute([
            'username' => $username,
            'email' => $username,
        ]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = (int) $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]) && (int) $_SESSION[self::SESSION_KEY] > 0;
    }

    public static function id(): ?int
    {
        return self::check() ? (int) $_SESSION[self::SESSION_KEY] : null;
    }

    public static function username(): ?string
    {
        return self::check() ? ($_SESSION['admin_username'] ?? null) : null;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY], $_SESSION['admin_username']);
        session_regenerate_id(true);
    }
}
