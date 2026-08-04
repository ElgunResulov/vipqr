<?php

declare(strict_types=1);

namespace App\Core;

final class Lang
{
    public const DEFAULT = 'az';

    /** @var list<string> */
    public const SUPPORTED = ['az', 'ru', 'en'];

    private const SESSION_KEY = 'locale';

    /** @var array<string, string>|null */
    private static ?array $lines = null;

    private static string $locale = self::DEFAULT;

    public static function boot(): void
    {
        $requested = $_GET['lang'] ?? null;
        if (is_string($requested) && self::isSupported($requested)) {
            self::set($requested);
            return;
        }

        $sessionLocale = $_SESSION[self::SESSION_KEY] ?? null;
        if (is_string($sessionLocale) && self::isSupported($sessionLocale)) {
            self::$locale = $sessionLocale;
            self::load();
            return;
        }

        self::$locale = self::DEFAULT;
        self::load();
    }

    public static function set(string $locale): void
    {
        if (!self::isSupported($locale)) {
            $locale = self::DEFAULT;
        }

        self::$locale = $locale;
        $_SESSION[self::SESSION_KEY] = $locale;
        self::$lines = null;
        self::load();
    }

    public static function get(): string
    {
        return self::$locale;
    }

    public static function isSupported(string $locale): bool
    {
        return in_array($locale, self::SUPPORTED, true);
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            'az' => 'AZ',
            'ru' => 'RU',
            'en' => 'EN',
        ];
    }

    public static function getLine(string $key, array $replace = []): string
    {
        self::load();
        $text = self::$lines[$key] ?? $key;

        foreach ($replace as $search => $value) {
            $text = str_replace(':' . $search, (string) $value, $text);
        }

        return $text;
    }

    /**
     * Pick localized field from DB row with fallback to base (AZ) column.
     *
     * @param array<string, mixed> $row
     */
    public static function field(array $row, string $field): string
    {
        $locale = self::$locale;

        if ($locale !== self::DEFAULT) {
            $localizedKey = $field . '_' . $locale;
            if (!empty($row[$localizedKey]) && is_string($row[$localizedKey])) {
                return $row[$localizedKey];
            }
        }

        $base = $row[$field] ?? '';
        return is_string($base) ? $base : (string) $base;
    }

    private static function load(): void
    {
        if (self::$lines !== null) {
            return;
        }

        $file = dirname(__DIR__) . '/Lang/' . self::$locale . '.php';
        if (!is_file($file)) {
            $file = dirname(__DIR__) . '/Lang/' . self::DEFAULT . '.php';
        }

        /** @var array<string, string> $lines */
        $lines = require $file;
        self::$lines = $lines;
    }
}
