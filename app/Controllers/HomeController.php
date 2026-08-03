<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

final class HomeController extends Controller
{
    public function index(): void
    {
        $settings = Setting::all();
        $categories = Category::allActive();
        $search = trim((string) ($_GET['q'] ?? ''));
        $products = Product::allActive(null, $search !== '' ? $search : null);

        $this->render('home/index', [
            'title' => ($settings['restaurant_name'] ?? 'VIP Karvan') . ' — Menyü',
            'settings' => $settings,
            'categories' => $categories,
            'products' => $products,
            'search' => $search,
        ]);
    }
}
