<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Product
{
    public static function allActive(): array
    {
        return Database::fetchAll("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.is_active = 1
            ORDER BY p.sort_order ASC, p.id ASC
        ");
    }

    public static function all(): array
    {
        return Database::fetchAll("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            ORDER BY p.sort_order ASC, p.id DESC
        ");
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.id = :id
        ", ['id' => $id]);
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::fetch("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.slug = :slug AND p.is_active = 1
        ", ['slug' => $slug]);
    }

    public static function findFeatured(int $limit = 12): array
    {
        return Database::fetchAll("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.is_active = 1 AND (p.is_featured = 1 OR p.badge IS NOT NULL)
            ORDER BY p.sort_order ASC, p.id ASC
            LIMIT {$limit}
        ");
    }

    public static function getRelated(int $productId, int $categoryId, int $limit = 4): array
    {
        // First fetch products in same category, then fallback to others
        $sameCategory = Database::fetchAll("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.is_active = 1 AND p.category_id = :cat_id AND p.id != :prod_id
            ORDER BY p.sort_order ASC
            LIMIT {$limit}
        ", ['cat_id' => $categoryId, 'prod_id' => $productId]);

        if (count($sameCategory) < $limit) {
            $needed = $limit - count($sameCategory);
            $excludeIds = array_merge([$productId], array_column($sameCategory, 'id'));
            $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));
            
            $others = Database::fetchAll("
                SELECT p.*, c.name AS category_name, c.slug AS category_slug 
                FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE p.is_active = 1 AND p.id NOT IN ($placeholders)
                ORDER BY p.is_featured DESC, p.sort_order ASC
                LIMIT {$needed}
            ", $excludeIds);

            return array_merge($sameCategory, $others);
        }

        return $sameCategory;
    }

    public static function getCategorySiblings(int $categoryId, int $currentProductId): array
    {
        return Database::fetchAll("
            SELECT id, name, slug, image, tint, price, size_label 
            FROM products 
            WHERE category_id = :cat_id AND is_active = 1
            ORDER BY sort_order ASC
        ", ['cat_id' => $categoryId]);
    }

    public static function getByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $validIds = array_filter(array_map('intval', $ids));
        if (empty($validIds)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($validIds), '?'));
        return Database::fetchAll("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.id IN ($placeholders) AND p.is_active = 1
        ", $validIds);
    }

    public static function search(string $query, int $limit = 10): array
    {
        $q = '%' . trim($query) . '%';
        return Database::fetchAll("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.is_active = 1 AND (
                p.name LIKE :q1 OR 
                c.name LIKE :q2 OR 
                p.notes_top LIKE :q3 OR 
                p.notes_heart LIKE :q4 OR 
                p.notes_base LIKE :q5 OR 
                p.tags LIKE :q6
            )
            ORDER BY p.is_featured DESC, p.sort_order ASC
            LIMIT {$limit}
        ", [
            'q1' => $q, 'q2' => $q, 'q3' => $q, 'q4' => $q, 'q5' => $q, 'q6' => $q
        ]);
    }

    public static function filter(array $filters, int $limit = 12, int $offset = 0): array
    {
        $sql = "
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.is_active = 1
        ";
        $params = [];

        // Category filter
        if (!empty($filters['category'])) {
            if (is_numeric($filters['category'])) {
                $sql .= " AND p.category_id = :cat_id";
                $params['cat_id'] = (int)$filters['category'];
            } else {
                $sql .= " AND c.slug = :cat_slug";
                $params['cat_slug'] = $filters['category'];
            }
        }

        // Search filter
        if (!empty($filters['q'])) {
            $q = '%' . trim($filters['q']) . '%';
            $sql .= " AND (p.name LIKE :q1 OR p.description LIKE :q2 OR p.tags LIKE :q3)";
            $params['q1'] = $q;
            $params['q2'] = $q;
            $params['q3'] = $q;
        }

        // Price range
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $sql .= " AND p.price >= :min_p";
            $params['min_p'] = (int)$filters['min_price'];
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $sql .= " AND p.price <= :max_p";
            $params['max_p'] = (int)$filters['max_price'];
        }

        // Size filter
        if (!empty($filters['size'])) {
            $sql .= " AND p.size_label = :size";
            $params['size'] = $filters['size'];
        }

        // Bestsellers only
        if (!empty($filters['bestseller'])) {
            $sql .= " AND (p.badge = 'Bestseller' OR p.is_featured = 1)";
        }

        // Sorting
        $sort = $filters['sort'] ?? 'featured';
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC, p.id ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC, p.id ASC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY p.name ASC";
                break;
            case 'newest':
                $sql .= " ORDER BY p.id DESC";
                break;
            case 'featured':
            default:
                $sql .= " ORDER BY p.is_featured DESC, p.sort_order ASC, p.id ASC";
                break;
        }

        $sql .= " LIMIT {$limit} OFFSET {$offset}";

        return Database::fetchAll($sql, $params);
    }

    public static function countFiltered(array $filters): int
    {
        $sql = "
            SELECT COUNT(*) AS cnt 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.is_active = 1
        ";
        $params = [];

        if (!empty($filters['category'])) {
            if (is_numeric($filters['category'])) {
                $sql .= " AND p.category_id = :cat_id";
                $params['cat_id'] = (int)$filters['category'];
            } else {
                $sql .= " AND c.slug = :cat_slug";
                $params['cat_slug'] = $filters['category'];
            }
        }
        if (!empty($filters['q'])) {
            $q = '%' . trim($filters['q']) . '%';
            $sql .= " AND (p.name LIKE :q1 OR p.description LIKE :q2 OR p.tags LIKE :q3)";
            $params['q1'] = $q;
            $params['q2'] = $q;
            $params['q3'] = $q;
        }
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $sql .= " AND p.price >= :min_p";
            $params['min_p'] = (int)$filters['min_price'];
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $sql .= " AND p.price <= :max_p";
            $params['max_p'] = (int)$filters['max_price'];
        }
        if (!empty($filters['size'])) {
            $sql .= " AND p.size_label = :size";
            $params['size'] = $filters['size'];
        }
        if (!empty($filters['bestseller'])) {
            $sql .= " AND (p.badge = 'Bestseller' OR p.is_featured = 1)";
        }

        $row = Database::fetch($sql, $params);
        return (int)($row['cnt'] ?? 0);
    }

    public static function reduceStock(int $productId, int $qty): void
    {
        Database::query("UPDATE products SET stock_qty = MAX(0, stock_qty - :qty) WHERE id = :id", [
            'qty' => $qty,
            'id' => $productId
        ]);
    }

    public static function create(array $data): int
    {
        return Database::insert('products', [
            'category_id' => (int)$data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'size_label' => $data['size_label'],
            'price' => (int)$data['price'],
            'compare_at_price' => !empty($data['compare_at_price']) ? (int)$data['compare_at_price'] : null,
            'image' => $data['image'],
            'tint' => $data['tint'] ?? '#F3EDE2',
            'hero_bg' => !empty($data['hero_bg']) ? $data['hero_bg'] : null,
            'badge' => !empty($data['badge']) ? $data['badge'] : null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'notes_top' => $data['notes_top'] ?? null,
            'notes_heart' => $data['notes_heart'] ?? null,
            'notes_base' => $data['notes_base'] ?? null,
            'how_to_use' => $data['how_to_use'] ?? null,
            'tags' => $data['tags'] ?? null,
            'stock_qty' => (int)($data['stock_qty'] ?? 50),
            'is_featured' => (int)($data['is_featured'] ?? 0),
            'is_active' => (int)($data['is_active'] ?? 1),
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('products', [
            'category_id' => (int)$data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'size_label' => $data['size_label'],
            'price' => (int)$data['price'],
            'compare_at_price' => !empty($data['compare_at_price']) ? (int)$data['compare_at_price'] : null,
            'image' => $data['image'],
            'tint' => $data['tint'] ?? '#F3EDE2',
            'hero_bg' => !empty($data['hero_bg']) ? $data['hero_bg'] : null,
            'badge' => !empty($data['badge']) ? $data['badge'] : null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'notes_top' => $data['notes_top'] ?? null,
            'notes_heart' => $data['notes_heart'] ?? null,
            'notes_base' => $data['notes_base'] ?? null,
            'how_to_use' => $data['how_to_use'] ?? null,
            'tags' => $data['tags'] ?? null,
            'stock_qty' => (int)($data['stock_qty'] ?? 50),
            'is_featured' => (int)($data['is_featured'] ?? 0),
            'is_active' => (int)($data['is_active'] ?? 1),
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ], 'id = :where_id', ['where_id' => $id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('products', 'id = :id', ['id' => $id]);
    }
}
