<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Setting
{
    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT setting_key, setting_value FROM settings');
        $rows = $stmt->fetchAll();
        $map = [];
        foreach ($rows as $row) {
            $map[$row['setting_key']] = $row['setting_value'];
        }
        return $map;
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $stmt = Database::connection()->prepare(
            'SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1'
        );
        $stmt->execute(['key' => $key]);
        $value = $stmt->fetchColumn();
        return $value === false ? $default : (string) $value;
    }

    public static function set(string $key, ?string $value): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO settings (setting_key, setting_value)
             VALUES (:key, :value)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        $stmt->execute([
            'key' => $key,
            'value' => $value,
        ]);
    }

    public static function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            self::set((string) $key, $value === null ? null : (string) $value);
        }
    }
}
