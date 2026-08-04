<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Lang;

final class LangController extends Controller
{
    public function switch(string $locale): void
    {
        if (!Lang::isSupported($locale)) {
            $locale = Lang::DEFAULT;
        }

        Lang::set($locale);

        $redirect = $_GET['redirect'] ?? '';
        if (!is_string($redirect) || $redirect === '' || !str_starts_with($redirect, base_url())) {
            $this->redirect('/');
        }

        header('Location: ' . $redirect);
        exit;
    }
}
