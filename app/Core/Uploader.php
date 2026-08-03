<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Uploader
{
    /**
     * @param array{name: string, type: string, tmp_name: string, error: int, size: int} $file
     */
    public static function store(array $file, string $subdir): string
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            throw new RuntimeException('Fayl seçilməyib.');
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Fayl yüklənərkən xəta baş verdi.');
        }

        if (($file['size'] ?? 0) > (int) $config['upload_max_bytes']) {
            throw new RuntimeException('Fayl ölçüsü 2MB-dan böyük ola bilməz.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, $config['upload_allowed_mimes'], true)) {
            throw new RuntimeException('Yalnız JPEG, PNG, WEBP və GIF şəkillərinə icazə verilir.');
        }

        $original = (string) ($file['name'] ?? '');
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        if (!in_array($ext, $config['upload_allowed_ext'], true)) {
            throw new RuntimeException('Fayl uzantısı icazə verilmir.');
        }

        // Normalize extension by mime
        $extByMime = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => $ext,
        };

        $subdir = trim(str_replace(['..', '\\'], '', $subdir), '/');
        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/' . $subdir;

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            throw new RuntimeException('Yükləmə qovluğu yaradıla bilmədi.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extByMime;
        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('Fayl yadda saxlanılmadı.');
        }

        return $subdir . '/' . $filename;
    }

    public static function delete(?string $relativePath): void
    {
        if ($relativePath === null || $relativePath === '') {
            return;
        }

        $relativePath = str_replace(['..', '\\'], '', $relativePath);
        $full = dirname(__DIR__, 2) . '/public/uploads/' . ltrim($relativePath, '/');

        if (is_file($full)) {
            unlink($full);
        }
    }
}
