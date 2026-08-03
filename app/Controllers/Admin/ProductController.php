<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Uploader;
use App\Models\Category;
use App\Models\Product;
use RuntimeException;

final class ProductController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $categoryId = isset($_GET['category_id']) && $_GET['category_id'] !== ''
            ? (int) $_GET['category_id']
            : null;

        $this->render('admin/products/index', [
            'title' => 'Məhsullar',
            'products' => Product::all($categoryId),
            'categories' => Category::all(),
            'selectedCategoryId' => $categoryId,
        ], 'admin/layouts/app');
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('admin/products/form', [
            'title' => 'Yeni məhsul',
            'product' => null,
            'categories' => Category::all(),
        ], 'admin/layouts/app');
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $data = $this->validate($_POST);
        if ($data === null) {
            $this->redirect('/admin/products/create');
        }

        try {
            if (!empty($_FILES['image']['name'])) {
                $data['image'] = Uploader::store($_FILES['image'], 'products');
            }
            Product::create($data);
            clear_old();
            flash('success', 'Məhsul əlavə edildi.');
            $this->redirect('/admin/products');
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
            $this->redirect('/admin/products/create');
        }
    }

    public function edit(string $id): void
    {
        $this->requireAuth();
        $product = Product::find((int) $id);
        if ($product === null) {
            flash('error', 'Məhsul tapılmadı.');
            $this->redirect('/admin/products');
        }

        $this->render('admin/products/form', [
            'title' => 'Məhsulu redaktə et',
            'product' => $product,
            'categories' => Category::all(),
        ], 'admin/layouts/app');
    }

    public function update(string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $product = Product::find((int) $id);
        if ($product === null) {
            flash('error', 'Məhsul tapılmadı.');
            $this->redirect('/admin/products');
        }

        $data = $this->validate($_POST);
        if ($data === null) {
            $this->redirect('/admin/products/' . $id . '/edit');
        }

        try {
            $data['image'] = $product['image'];
            if (!empty($_FILES['image']['name'])) {
                $newImage = Uploader::store($_FILES['image'], 'products');
                Uploader::delete($product['image'] ?? null);
                $data['image'] = $newImage;
            }
            if (!empty($_POST['remove_image'])) {
                Uploader::delete($product['image'] ?? null);
                $data['image'] = null;
            }

            Product::update((int) $id, $data);
            clear_old();
            flash('success', 'Məhsul yeniləndi.');
            $this->redirect('/admin/products');
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
            $this->redirect('/admin/products/' . $id . '/edit');
        }
    }

    public function destroy(string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $product = Product::find((int) $id);
        if ($product === null) {
            flash('error', 'Məhsul tapılmadı.');
            $this->redirect('/admin/products');
        }

        Uploader::delete($product['image'] ?? null);
        Product::delete((int) $id);
        flash('success', 'Məhsul silindi.');
        $this->redirect('/admin/products');
    }

    private function validate(array $input): ?array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));
        $categoryId = (int) ($input['category_id'] ?? 0);
        $price = str_replace(',', '.', trim((string) ($input['price'] ?? '0')));
        $sortOrder = (int) ($input['sort_order'] ?? 0);
        $isActive = isset($input['is_active']) ? 1 : 0;
        $isAvailable = isset($input['is_available']) ? 1 : 0;

        store_old([
            'name' => $name,
            'description' => $description,
            'category_id' => (string) $categoryId,
            'price' => $price,
            'sort_order' => (string) $sortOrder,
            'is_active' => (string) $isActive,
            'is_available' => (string) $isAvailable,
        ]);

        if ($name === '') {
            flash('error', 'Məhsul adı tələb olunur.');
            return null;
        }

        if ($categoryId <= 0 || Category::find($categoryId) === null) {
            flash('error', 'Kateqoriya seçilməlidir.');
            return null;
        }

        if (!is_numeric($price) || (float) $price < 0) {
            flash('error', 'Qiymət düzgün deyil.');
            return null;
        }

        return [
            'name' => $name,
            'description' => $description !== '' ? $description : null,
            'category_id' => $categoryId,
            'price' => number_format((float) $price, 2, '.', ''),
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
            'is_available' => $isAvailable,
        ];
    }
}
