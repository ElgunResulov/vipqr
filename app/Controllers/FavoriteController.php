<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Guest;
use App\Models\Favorite;

final class FavoriteController extends Controller
{
    public function index(): void
    {
        $token = Guest::token();
        $this->json([
            'ok' => true,
            'ids' => Favorite::idsForGuest($token),
        ]);
    }

    public function sync(): void
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

        $token = Guest::token();
        $saved = Favorite::syncForGuest($token, $ids);

        $this->json([
            'ok' => true,
            'ids' => $saved,
        ]);
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

        return true;
    }
}
