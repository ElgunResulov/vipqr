<?php

declare(strict_types=1);

use App\Core\Csrf;

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function config(string $key, mixed $default = null): mixed
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require dirname(__DIR__, 2) . '/config/app.php';
    }

    return $cfg[$key] ?? $default;
}

function base_url(string $path = ''): string
{
    $base = rtrim((string) config('url'), '/');
    $path = ltrim($path, '/');
    return $path === '' ? $base : $base . '/' . $path;
}

function url(string $path = ''): string
{
    return base_url($path);
}

function asset(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function upload_url(?string $path): string
{
    if ($path === null || $path === '') {
        return asset('img/placeholder.svg');
    }

    return base_url('uploads/' . ltrim($path, '/'));
}

/**
 * Absolute URL for an uploaded file, or null when empty.
 */
function uploaded_url(?string $path): ?string
{
    if ($path === null || trim($path) === '') {
        return null;
    }

    return base_url('uploads/' . ltrim($path, '/'));
}

/**
 * Hero atmosphere video: custom upload first, then bundled default clip.
 *
 * @param array<string, mixed> $settings
 */
function hero_video_url(array $settings): ?string
{
    $custom = uploaded_url(isset($settings['hero_video']) ? (string) $settings['hero_video'] : null);
    if ($custom !== null) {
        return $custom;
    }

    $defaultRel = 'media/hero-ambiance.mp4';
    $defaultAbs = dirname(__DIR__, 2) . '/public/assets/' . $defaultRel;
    if (is_file($defaultAbs)) {
        return asset($defaultRel);
    }

    return null;
}

/**
 * Hero poster still for video / reduced-motion fallback.
 *
 * @param array<string, mixed> $settings
 */
function hero_poster_url(array $settings): string
{
    $custom = uploaded_url(isset($settings['hero_poster']) ? (string) $settings['hero_poster'] : null);
    if ($custom !== null) {
        return $custom;
    }

    $defaultRel = 'media/hero-poster.jpg';
    $defaultAbs = dirname(__DIR__, 2) . '/public/assets/' . $defaultRel;
    if (is_file($defaultAbs)) {
        return asset($defaultRel);
    }

    return asset('img/placeholder.svg');
}

function redirect(string $path): never
{
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        header('Location: ' . $path);
        exit;
    }

    header('Location: ' . base_url(ltrim($path, '/')));
    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }

    if (!isset($_SESSION['_flash'][$key])) {
        return null;
    }

    $value = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);
    return is_string($value) ? $value : null;
}

function old(string $key, string $default = ''): string
{
    $old = $_SESSION['_old'][$key] ?? $default;
    return is_string($old) ? $old : $default;
}

function store_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function slugify(string $text): string
{
    $map = [
        'ə' => 'e', 'Ə' => 'e', 'ı' => 'i', 'İ' => 'i', 'ö' => 'o', 'Ö' => 'o',
        'ü' => 'u', 'Ü' => 'u', 'ş' => 's', 'Ş' => 's', 'ç' => 'c', 'Ç' => 'c',
        'ğ' => 'g', 'Ğ' => 'g',
    ];
    $text = strtr($text, $map);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'item';
}

function money_azn(float|string $amount): string
{
    return number_format((float) $amount, 2, '.', ' ') . ' ' . __('currency');
}

function __(string $key, array $replace = []): string
{
    return \App\Core\Lang::getLine($key, $replace);
}

function locale(): string
{
    return \App\Core\Lang::get();
}

/**
 * @param array<string, mixed> $row
 */
function localized(array $row, string $field): string
{
    return \App\Core\Lang::field($row, $field);
}

function setting_localized(array $settings, string $key, ?string $fallbackKey = null): string
{
    $locale = locale();
    if ($locale !== 'az') {
        $localized = trim((string) ($settings[$key . '_' . $locale] ?? ''));
        if ($localized !== '') {
            return $localized;
        }
    }

    $base = trim((string) ($settings[$key] ?? ''));
    if ($base !== '') {
        return $base;
    }

    return $fallbackKey !== null ? __($fallbackKey) : '';
}

function lang_url(string $locale): string
{
    $referer = $_SERVER['HTTP_REFERER'] ?? base_url();
    $safeReferer = str_starts_with($referer, base_url()) ? $referer : base_url();

    return base_url('lang/' . $locale) . '?redirect=' . rawurlencode($safeReferer);
}

function csrf_field(): string
{
    return Csrf::field();
}

function csrf_token(): string
{
    return Csrf::token();
}

function view(string $name, array $data = [], ?string $layout = 'layouts/main'): void
{
    $viewFile = dirname(__DIR__) . '/Views/' . str_replace('.', '/', $name) . '.php';
    if (!is_file($viewFile)) {
        throw new RuntimeException("View tapılmadı: {$name}");
    }

    extract($data, EXTR_SKIP);
    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    if ($layout === null) {
        echo $content;
        return;
    }

    $layoutFile = dirname(__DIR__) . '/Views/' . str_replace('.', '/', $layout) . '.php';
    if (!is_file($layoutFile)) {
        throw new RuntimeException("Layout tapılmadı: {$layout}");
    }

    require $layoutFile;
}

function is_active_path(string $needle): bool
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    return str_contains($uri, $needle);
}
