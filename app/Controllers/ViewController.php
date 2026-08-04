<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

final class ViewController extends Controller
{
    private const MAX_BATCH = 20;

    public function track(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->json(['ok' => false, 'error' => 'method'], 405);
        }

        if (!$this->isSameOrigin()) {
            $this->json(['ok' => false, 'error' => 'origin'], 403);
        }

        $raw = file_get_contents('php://input');
        $payload = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
        if (!is_array($payload)) {
            $this->json(['ok' => false, 'error' => 'json'], 422);
        }

        $ids = $payload['ids'] ?? [];
        if (!is_array($ids)) {
            $this->json(['ok' => false, 'error' => 'ids'], 422);
        }

        $ids = array_values(array_unique(array_map('intval', $ids)));
        $ids = array_values(array_filter($ids, static fn (int $id): bool => $id > 0));
        $ids = array_slice($ids, 0, self::MAX_BATCH);

        if ($ids === []) {
            $this->json(['ok' => true, 'counted' => 0]);
        }

        if (!isset($_SESSION['product_views']) || !is_array($_SESSION['product_views'])) {
            $_SESSION['product_views'] = [];
        }

        $fresh = [];
        foreach ($ids as $id) {
            $key = (string) $id;
            if (!isset($_SESSION['product_views'][$key])) {
                $_SESSION['product_views'][$key] = time();
                $fresh[] = $id;
            }
        }

        $counted = $fresh === [] ? 0 : Product::incrementViews($fresh);
        $this->json(['ok' => true, 'counted' => $counted]);
    }

    private function isSameOrigin(): bool
    {
        $allowedHost = parse_url((string) config('url'), PHP_URL_HOST);
        if (!is_string($allowedHost) || $allowedHost === '') {
            return false;
        }

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if (is_string($origin) && $origin !== '') {
            $host = parse_url($origin, PHP_URL_HOST);
            return is_string($host) && strcasecmp($host, $allowedHost) === 0;
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (is_string($referer) && $referer !== '') {
            $host = parse_url($referer, PHP_URL_HOST);
            return is_string($host) && strcasecmp($host, $allowedHost) === 0;
        }

        // Browsers may omit Origin/Referer on same-site fetch.
        return true;
    }
}
