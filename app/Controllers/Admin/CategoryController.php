<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Uploader;
use App\Models\Category;
use RuntimeException;

final class CategoryController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('admin/categories/index', [
            'title' => 'Kateqoriyalar',
            'categories' => Category::all(),
        ], 'admin/layouts/app');
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('admin/categories/form', [
            'title' => 'Yeni kateqoriya',
            'category' => null,
        ], 'admin/layouts/app');
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $data = $this->validate($_POST);
        if ($data === null) {
            $this->redirect('/admin/categories/create');
        }

        try {
            if (!empty($_FILES['image']['name'])) {
                $data['image'] = Uploader::store($_FILES['image'], 'categories');
            }
            Category::create($data);
            clear_old();
            flash('success', 'Kateqoriya əlavə edildi.');
            $this->redirect('/admin/categories');
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
            $this->redirect('/admin/categories/create');
        }
    }

    public function edit(string $id): void
    {
        $this->requireAuth();
        $category = Category::find((int) $id);
        if ($category === null) {
            flash('error', 'Kateqoriya tapılmadı.');
            $this->redirect('/admin/categories');
        }

        $this->render('admin/categories/form', [
            'title' => 'Kateqoriyanı redaktə et',
            'category' => $category,
        ], 'admin/layouts/app');
    }

    public function update(string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $category = Category::find((int) $id);
        if ($category === null) {
            flash('error', 'Kateqoriya tapılmadı.');
            $this->redirect('/admin/categories');
        }

        $data = $this->validate($_POST, (int) $id);
        if ($data === null) {
            $this->redirect('/admin/categories/' . $id . '/edit');
        }

        try {
            $data['image'] = $category['image'];
            if (!empty($_FILES['image']['name'])) {
                $newImage = Uploader::store($_FILES['image'], 'categories');
                Uploader::delete($category['image'] ?? null);
                $data['image'] = $newImage;
            }
            if (!empty($_POST['remove_image'])) {
                Uploader::delete($category['image'] ?? null);
                $data['image'] = null;
            }

            Category::update((int) $id, $data);
            clear_old();
            flash('success', 'Kateqoriya yeniləndi.');
            $this->redirect('/admin/categories');
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
            $this->redirect('/admin/categories/' . $id . '/edit');
        }
    }

    public function destroy(string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $category = Category::find((int) $id);
        if ($category === null) {
            flash('error', 'Kateqoriya tapılmadı.');
            $this->redirect('/admin/categories');
        }

        Uploader::delete($category['image'] ?? null);
        Category::delete((int) $id);
        flash('success', 'Kateqoriya silindi.');
        $this->redirect('/admin/categories');
    }

    private function validate(array $input, ?int $excludeId = null): ?array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $slug = trim((string) ($input['slug'] ?? ''));
        $sortOrder = (int) ($input['sort_order'] ?? 0);
        $isActive = isset($input['is_active']) ? 1 : 0;

        store_old([
            'name' => $name,
            'slug' => $slug,
            'sort_order' => (string) $sortOrder,
            'is_active' => (string) $isActive,
        ]);

        if ($name === '') {
            flash('error', 'Kateqoriya adı tələb olunur.');
            return null;
        }

        if ($slug === '') {
            $slug = slugify($name);
        } else {
            $slug = slugify($slug);
        }

        if (Category::slugExists($slug, $excludeId)) {
            flash('error', 'Bu slug artıq mövcuddur.');
            return null;
        }

        return [
            'name' => $name,
            'slug' => $slug,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];
    }
}
