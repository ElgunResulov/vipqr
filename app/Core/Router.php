<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, array{controller: class-string, action: string}>> */
    private array $routes = [];

    public function get(string $path, string $controller, string $action): void
    {
        $this->addRoute('GET', $path, $controller, $action);
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->addRoute('POST', $path, $controller, $action);
    }

    private function addRoute(string $method, string $path, string $controller, string $action): void
    {
        $normalized = $this->normalizePath($path);
        $this->routes[$method][$normalized] = [
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = $this->stripBasePath($path);
        $path = $this->normalizePath($path);

        $route = $this->routes[$method][$path] ?? null;

        if ($route === null) {
            foreach ($this->routes[$method] ?? [] as $pattern => $candidate) {
                $params = $this->match($pattern, $path);
                if ($params !== null) {
                    $this->invoke($candidate['controller'], $candidate['action'], $params);
                    return;
                }
            }

            http_response_code(404);
            $settings = [];
            try {
                $settings = \App\Models\Setting::all();
            } catch (Throwable) {
                $settings = ['restaurant_name' => 'VIP Karvan'];
            }
            view('errors/404', ['title' => 'Səhifə tapılmadı', 'settings' => $settings]);
            return;
        }

        $this->invoke($route['controller'], $route['action'], []);
    }

    /**
     * @param class-string $controller
     * @param array<string, string> $params
     */
    private function invoke(string $controller, string $action, array $params): void
    {
        if (!class_exists($controller)) {
            throw new \RuntimeException("Controller tapılmadı: {$controller}");
        }

        $instance = new $controller();

        if (!method_exists($instance, $action)) {
            throw new \RuntimeException("Action tapılmadı: {$controller}@{$action}");
        }

        $instance->{$action}(...array_values($params));
    }

    /**
     * @return array<string, string>|null
     */
    private function match(string $pattern, string $path): ?array
    {
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    private function stripBasePath(string $path): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $base = str_replace('\\', '/', dirname($scriptName));

        if ($base !== '/' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }

        // Support /vipqr/public and /vipqr rewrite
        foreach (['/vipqr/public', '/vipqr'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix)) ?: '/';
                break;
            }
        }

        return $path;
    }
}
