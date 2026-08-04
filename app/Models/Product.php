<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Product
{
    public static function allActive(?int $categoryId = null, ?string $search = null): array
    {
        $sql = 'SELECT p.*,
                       c.name AS category_name,
                       c.name_ru AS category_name_ru,
                       c.name_en AS category_name_en,
                       c.slug AS category_slug
                FROM products p
                INNER JOIN categories c ON c.id = p.category_id
                WHERE p.is_active = 1 AND c.is_active = 1';
        $params = [];

        if ($categoryId !== null) {
            $sql .= ' AND p.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($search !== null && $search !== '') {
            $sql .= ' AND (
                p.name LIKE :q OR p.name_ru LIKE :q2 OR p.name_en LIKE :q3
                OR p.description LIKE :q4 OR p.description_ru LIKE :q5 OR p.description_en LIKE :q6
            )';
            $like = '%' . $search . '%';
            $params['q'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
            $params['q4'] = $like;
            $params['q5'] = $like;
            $params['q6'] = $like;
        }

        $sql .= ' ORDER BY p.is_featured DESC, p.is_popular DESC, p.view_count DESC, p.sort_order ASC, p.name ASC';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function featured(int $limit = 2): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.*,
                    c.name AS category_name,
                    c.name_ru AS category_name_ru,
                    c.name_en AS category_name_en,
                    c.slug AS category_slug
             FROM products p
             INNER JOIN categories c ON c.id = p.category_id
             WHERE p.is_active = 1 AND p.is_available = 1 AND p.is_featured = 1
               AND c.is_active = 1
             ORDER BY p.sort_order ASC, p.name ASC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Manual popular picks first, then highest view counts.
     * Excludes chef-featured items to avoid duplicating the section above.
     */
    public static function popular(int $limit = 6): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.*,
                    c.name AS category_name,
                    c.name_ru AS category_name_ru,
                    c.name_en AS category_name_en,
                    c.slug AS category_slug
             FROM products p
             INNER JOIN categories c ON c.id = p.category_id
             WHERE p.is_active = 1 AND p.is_available = 1 AND p.is_featured = 0
               AND c.is_active = 1
               AND (p.is_popular = 1 OR p.view_count > 0)
             ORDER BY p.is_popular DESC, p.view_count DESC, p.sort_order ASC, p.name ASC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function incrementViews(array $ids): int
    {
        $clean = [];
        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $clean[$id] = $id;
            }
        }
        $clean = array_values($clean);
        if ($clean === []) {
            return 0;
        }

        $placeholders = implode(',', array_fill(0, count($clean), '?'));
        $stmt = Database::connection()->prepare(
            "UPDATE products SET view_count = view_count + 1
             WHERE id IN ($placeholders) AND is_active = 1"
        );
        $stmt->execute($clean);
        return $stmt->rowCount();
    }

    public static function countPopular(?int $excludeId = null): int
    {
        $sql = 'SELECT COUNT(*) FROM products WHERE is_popular = 1';
        $params = [];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
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
                (category_id, name, name_ru, name_en, description, description_ru, description_en,
                 price, image, sort_order, is_active, is_available, is_featured, is_popular, view_count)
             VALUES
                (:category_id, :name, :name_ru, :name_en, :description, :description_ru, :description_en,
                 :price, :image, :sort_order, :is_active, :is_available, :is_featured, :is_popular, :view_count)'
        );
        $stmt->execute([
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'name_ru' => $data['name_ru'] ?? null,
            'name_en' => $data['name_en'] ?? null,
            'description' => $data['description'] ?? null,
            'description_ru' => $data['description_ru'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'price' => $data['price'],
            'image' => $data['image'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => (int) ($data['is_active'] ?? 1),
            'is_available' => (int) ($data['is_available'] ?? 1),
            'is_featured' => (int) ($data['is_featured'] ?? 0),
            'is_popular' => (int) ($data['is_popular'] ?? 0),
            'view_count' => (int) ($data['view_count'] ?? 0),
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE products SET
                category_id = :category_id,
                name = :name,
                name_ru = :name_ru,
                name_en = :name_en,
                description = :description,
                description_ru = :description_ru,
                description_en = :description_en,
                price = :price,
                image = :image,
                sort_order = :sort_order,
                is_active = :is_active,
                is_available = :is_available,
                is_featured = :is_featured,
                is_popular = :is_popular
             WHERE id = :id'
        );
        return $stmt->execute([
            'id' => $id,
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'name_ru' => $data['name_ru'] ?? null,
            'name_en' => $data['name_en'] ?? null,
            'description' => $data['description'] ?? null,
            'description_ru' => $data['description_ru'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'price' => $data['price'],
            'image' => $data['image'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => (int) ($data['is_active'] ?? 1),
            'is_available' => (int) ($data['is_available'] ?? 1),
            'is_featured' => (int) ($data['is_featured'] ?? 0),
            'is_popular' => (int) ($data['is_popular'] ?? 0),
        ]);
    }

    public static function countFeatured(?int $excludeId = null): int
    {
        $sql = 'SELECT COUNT(*) FROM products WHERE is_featured = 1';
        $params = [];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
