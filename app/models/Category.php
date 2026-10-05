<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Category
{
    public static function allActive(): array
    {
        return Database::fetchAll("
            SELECT c.*, COUNT(p.id) AS product_count 
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.id ASC
        ");
    }

    public static function all(): array
    {
        return Database::fetchAll("
            SELECT c.*, COUNT(p.id) AS product_count 
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.id ASC
        ");
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM categories WHERE id = :id", ['id' => $id]);
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::fetch("SELECT * FROM categories WHERE slug = :slug AND is_active = 1", ['slug' => $slug]);
    }

    public static function create(array $data): int
    {
        return Database::insert('categories', [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'cover_image' => $data['cover_image'] ?? null,
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'is_active' => (int)($data['is_active'] ?? 1),
        ]);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('categories', [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'cover_image' => $data['cover_image'] ?? null,
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'is_active' => (int)($data['is_active'] ?? 1),
        ], 'id = :where_id', ['where_id' => $id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('categories', 'id = :id', ['id' => $id]);
    }
}
