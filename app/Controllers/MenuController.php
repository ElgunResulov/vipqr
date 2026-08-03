<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

final class MenuController extends Controller
{
    public function category(string $slug): void
    {
        $category = Category::findBySlug($slug);
        if ($category === null) {
            http_response_code(404);
            $settings = Setting::all();
            view('errors/404', ['title' => 'Kateqoriya tapılmadı', 'settings' => $settings]);
            return;
        }

        $settings = Setting::all();
        $categories = Category::allActive();
        $search = trim((string) ($_GET['q'] ?? ''));
        $products = Product::allActive((int) $category['id'], $search !== '' ? $search : null);

        $this->render('menu/category', [
            'title' => $category['name'] . ' — ' . ($settings['restaurant_name'] ?? 'VIP Karvan'),
            'settings' => $settings,
            'categories' => $categories,
            'category' => $category,
            'products' => $products,
            'search' => $search,
        ]);
    }
}
