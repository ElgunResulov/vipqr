<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$router = require dirname(__DIR__) . '/app/routes.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

try {
    $router->dispatch($method, $uri);
} catch (Throwable $e) {
    http_response_code(500);
    $settings = [];
    try {
        $settings = \App\Models\Setting::all();
    } catch (Throwable) {
        $settings = ['restaurant_name' => 'VIP Karvan'];
    }
    if (filter_var(getenv('APP_DEBUG') ?: '0', FILTER_VALIDATE_BOOLEAN)) {
        echo '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString(), ENT_QUOTES, 'UTF-8') . '</pre>';
    } else {
        view('errors/500', ['title' => 'Server xətası', 'settings' => $settings], 'layouts/main');
    }
}
