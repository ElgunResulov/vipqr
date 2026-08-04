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

        return self::storeWithRules(
            $file,
            $subdir,
            (int) $config['upload_max_bytes'],
            $config['upload_allowed_mimes'],
            $config['upload_allowed_ext'],
            [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
            ],
            'Yalnız JPEG, PNG, WEBP və GIF şəkillərinə icazə verilir.',
            'Fayl ölçüsü 2MB-dan böyük ola bilməz.'
        );
    }

    /**
     * @param array{name: string, type: string, tmp_name: string, error: int, size: int} $file
     */
    public static function storeVideo(array $file, string $subdir): string
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';

        return self::storeWithRules(
            $file,
            $subdir,
            (int) $config['video_max_bytes'],
            $config['video_allowed_mimes'],
            $config['video_allowed_ext'],
            [
                'video/mp4' => 'mp4',
                'video/webm' => 'webm',
                'video/quicktime' => 'mov',
            ],
            'Yalnız MP4, WEBM və MOV videolarına icazə verilir.',
            'Video ölçüsü 12MB-dan böyük ola bilməz.'
        );
    }

    /**
     * @param array{name: string, type: string, tmp_name: string, error: int, size: int} $file
     * @param list<string> $allowedMimes
     * @param list<string> $allowedExt
     * @param array<string, string> $extByMime
     */
    private static function storeWithRules(
        array $file,
        string $subdir,
        int $maxBytes,
        array $allowedMimes,
        array $allowedExt,
        array $extByMime,
        string $mimeError,
        string $sizeError
    ): string {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            throw new RuntimeException('Fayl seçilməyib.');
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Fayl yüklənərkən xəta baş verdi.');
        }

        if (($file['size'] ?? 0) > $maxBytes) {
            throw new RuntimeException($sizeError);
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!is_string($mime) || !in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException($mimeError);
        }

        $original = (string) ($file['name'] ?? '');
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            throw new RuntimeException('Fayl uzantısı icazə verilmir.');
        }

        $extByMimeFinal = $extByMime[$mime] ?? $ext;

        $subdir = trim(str_replace(['..', '\\'], '', $subdir), '/');
        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/' . $subdir;

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            throw new RuntimeException('Yükləmə qovluğu yaradıla bilmədi.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extByMimeFinal;
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
