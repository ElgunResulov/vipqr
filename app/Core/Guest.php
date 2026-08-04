<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Anonymous guest identity via HttpOnly cookie — no login required.
 */
final class Guest
{
    public const COOKIE = 'vipqr_guest';
    private const TTL = 60 * 60 * 24 * 365; // 1 year

    public static function boot(): void
    {
        self::token();
    }

    public static function token(): string
    {
        $existing = $_COOKIE[self::COOKIE] ?? '';
        if (is_string($existing) && self::isValid($existing)) {
            return $existing;
        }

        $token = bin2hex(random_bytes(32));
        self::setCookie($token);
        $_COOKIE[self::COOKIE] = $token;
        return $token;
    }

    public static function isValid(string $token): bool
    {
        return (bool) preg_match('/^[a-f0-9]{64}$/', $token);
    }

    private static function setCookie(string $token): void
    {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ((int) ($_SERVER['SERVER_PORT'] ?? 80) === 443);

        setcookie(self::COOKIE, $token, [
            'expires' => time() + self::TTL,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}
