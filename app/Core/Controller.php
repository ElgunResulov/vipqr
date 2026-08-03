<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        view($view, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        redirect($path);
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            flash('error', 'Davam etmək üçün daxil olun.');
            redirect('/admin/login');
        }
    }

    protected function verifyCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!Csrf::verify(is_string($token) ? $token : '')) {
            http_response_code(419);
            flash('error', 'CSRF token etibarsızdır. Yenidən cəhd edin.');
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            $safe = (is_string($referer) && str_starts_with($referer, base_url()))
                ? $referer
                : '/admin';
            redirect($safe);
        }
    }
}
