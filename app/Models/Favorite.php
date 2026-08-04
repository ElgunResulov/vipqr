<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Favorite
{
    public const MAX = 50;

    /**
     * @return list<int>
     */
    public static function idsForGuest(string $guestToken): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT product_id FROM guest_favorites
             WHERE guest_token = :token
             ORDER BY created_at ASC, id ASC'
        );
        $stmt->execute(['token' => $guestToken]);
        $rows = $stmt->fetchAll();
        $ids = [];
        foreach ($rows as $row) {
            $ids[] = (int) $row['product_id'];
        }
        return $ids;
    }

    /**
     * Replace guest favorites with the given product ids (validated active products).
     *
     * @param list<int> $productIds
     * @return list<int>
     */
    public static function syncForGuest(string $guestToken, array $productIds): array
    {
        $clean = [];
        foreach ($productIds as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $clean[$id] = $id;
            }
        }
        $clean = array_values($clean);
        $clean = array_slice($clean, 0, self::MAX);

        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $del = $pdo->prepare('DELETE FROM guest_favorites WHERE guest_token = :token');
            $del->execute(['token' => $guestToken]);

            if ($clean !== []) {
                $placeholders = implode(',', array_fill(0, count($clean), '?'));
                $validStmt = $pdo->prepare(
                    "SELECT id FROM products WHERE is_active = 1 AND id IN ($placeholders)"
                );
                $validStmt->execute($clean);
                $valid = [];
                foreach ($validStmt->fetchAll() as $row) {
                    $valid[(int) $row['id']] = (int) $row['id'];
                }

                // Keep original order from client, only valid ids
                $ordered = [];
                foreach ($clean as $id) {
                    if (isset($valid[$id])) {
                        $ordered[] = $id;
                    }
                }

                $ins = $pdo->prepare(
                    'INSERT INTO guest_favorites (guest_token, product_id) VALUES (:token, :product_id)'
                );
                foreach ($ordered as $id) {
                    $ins->execute([
                        'token' => $guestToken,
                        'product_id' => $id,
                    ]);
                }
                $clean = $ordered;
            }

            $pdo->commit();
            return $clean;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
