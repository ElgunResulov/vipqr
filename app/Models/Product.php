<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Product
{
    public static function allActive(?int $categoryId = null, ?string $search = null): array
    {
        $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug
                FROM products p
                INNER JOIN categories c ON c.id = p.category_id
                WHERE p.is_active = 1 AND c.is_active = 1';
        $params = [];

        if ($categoryId !== null) {
            $sql .= ' AND p.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($search !== null && $search !== '') {
            $sql .= ' AND (p.name LIKE :q OR p.description LIKE :q2)';
            $params['q'] = '%' . $search . '%';
            $params['q2'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY p.sort_order ASC, p.name ASC';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function all(?int $categoryId = null): array
    {
        $sql = 'SELECT p.*, c.name AS category_name
                FROM products p
                INNER JOIN categories c ON c.id = p.category_id';
        $params = [];

        if ($categoryId !== null) {
            $sql .= ' WHERE p.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $sql .= ' ORDER BY p.sort_order ASC, p.name ASC';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p
             INNER JOIN categories c ON c.id = p.category_id
             WHERE p.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function count(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM products')->fetchColumn();
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO products
                (category_id, name, description, price, image, sort_order, is_active, is_available)
             VALUES
                (:category_id, :name, :description, :price, :image, :sort_order, :is_active, :is_available)'
        );
        $stmt->execute([
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $data['image'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => (int) ($data['is_active'] ?? 1),
            'is_available' => (int) ($data['is_available'] ?? 1),
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE products SET
                category_id = :category_id,
                name = :name,
                description = :description,
                price = :price,
                image = :image,
                sort_order = :sort_order,
                is_active = :is_active,
                is_available = :is_available
             WHERE id = :id'
        );
        return $stmt->execute([
            'id' => $id,
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $data['image'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => (int) ($data['is_active'] ?? 1),
            'is_available' => (int) ($data['is_available'] ?? 1),
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
