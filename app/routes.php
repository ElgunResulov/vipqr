<?php

declare(strict_types=1);

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\QrController;
use App\Controllers\Admin\SettingController;
use App\Controllers\FavoriteController;
use App\Controllers\HomeController;
use App\Controllers\LangController;
use App\Controllers\MenuController;
use App\Controllers\ViewController;
use App\Core\Router;

$router = new Router();

// Public
$router->get('/', HomeController::class, 'index');
$router->get('/menu/{slug}', MenuController::class, 'category');
$router->get('/lang/{locale}', LangController::class, 'switch');
$router->post('/api/views', ViewController::class, 'track');
$router->get('/api/favorites', FavoriteController::class, 'index');
$router->post('/api/favorites', FavoriteController::class, 'sync');

// Admin auth
$router->get('/admin/login', AuthController::class, 'loginForm');
$router->post('/admin/login', AuthController::class, 'login');
$router->post('/admin/logout', AuthController::class, 'logout');

// Admin dashboard
$router->get('/admin', DashboardController::class, 'index');

// Categories
$router->get('/admin/categories', CategoryController::class, 'index');
$router->get('/admin/categories/create', CategoryController::class, 'create');
$router->post('/admin/categories', CategoryController::class, 'store');
$router->get('/admin/categories/{id}/edit', CategoryController::class, 'edit');
$router->post('/admin/categories/{id}', CategoryController::class, 'update');
$router->post('/admin/categories/{id}/delete', CategoryController::class, 'destroy');

// Products
$router->get('/admin/products', ProductController::class, 'index');
$router->get('/admin/products/create', ProductController::class, 'create');
$router->post('/admin/products', ProductController::class, 'store');
$router->get('/admin/products/{id}/edit', ProductController::class, 'edit');
$router->post('/admin/products/{id}', ProductController::class, 'update');
$router->post('/admin/products/{id}/delete', ProductController::class, 'destroy');

// Settings & QR
$router->get('/admin/settings', SettingController::class, 'edit');
$router->post('/admin/settings', SettingController::class, 'update');
$router->get('/admin/qr', QrController::class, 'index');
$router->get('/admin/qr/download', QrController::class, 'download');

return $router;
