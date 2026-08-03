<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $this->render('admin/dashboard/index', [
            'title' => 'İdarə paneli',
            'categoryCount' => Category::count(),
            'productCount' => Product::count(),
            'settings' => Setting::all(),
        ], 'admin/layouts/app');
    }
}
