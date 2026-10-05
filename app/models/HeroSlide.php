<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class HeroSlide
{
    public static function allActive(): array
    {
        return Database::fetchAll("
            SELECT s.*, p.slug AS product_slug, p.name AS product_name, p.price AS product_price, p.size_label AS product_size
            FROM hero_slides s
            LEFT JOIN products p ON p.id = s.product_id
            WHERE s.is_active = 1
            ORDER BY s.sort_order ASC, s.id ASC
        ");
    }

    public static function all(): array
    {
        return Database::fetchAll("
            SELECT s.*, p.name AS product_name 
            FROM hero_slides s
            LEFT JOIN products p ON p.id = s.product_id
            ORDER BY s.sort_order ASC, s.id ASC
        ");
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM hero_slides WHERE id = :id", ['id' => $id]);
    }

    public static function create(array $data): int
    {
        return Database::insert('hero_slides', [
            'product_id' => !empty($data['product_id']) ? (int)$data['product_id'] : null,
            'eyebrow' => $data['eyebrow'],
            'title' => $data['title'],
            'subline' => $data['subline'],
            'cta_label' => $data['cta_label'] ?? 'Explore Now',
            'cta_url' => $data['cta_url'] ?? '/shop',
            'cta2_label' => $data['cta2_label'] ?? null,
            'cta2_url' => $data['cta2_url'] ?? null,
            'image' => $data['image'],
            'tint' => $data['tint'] ?? '#E3E7F3',
            'bg_color' => $data['bg_color'] ?? '#0E1633',
            'outline_word' => $data['outline_word'] ?? 'Luxury',
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'is_active' => (int)($data['is_active'] ?? 1),
        ]);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('hero_slides', [
            'product_id' => !empty($data['product_id']) ? (int)$data['product_id'] : null,
            'eyebrow' => $data['eyebrow'],
            'title' => $data['title'],
            'subline' => $data['subline'],
            'cta_label' => $data['cta_label'] ?? 'Explore Now',
            'cta_url' => $data['cta_url'] ?? '/shop',
            'cta2_label' => $data['cta2_label'] ?? null,
            'cta2_url' => $data['cta2_url'] ?? null,
            'image' => $data['image'],
            'tint' => $data['tint'] ?? '#E3E7F3',
            'bg_color' => $data['bg_color'] ?? '#0E1633',
            'outline_word' => $data['outline_word'] ?? 'Luxury',
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'is_active' => (int)($data['is_active'] ?? 1),
        ], 'id = :where_id', ['where_id' => $id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('hero_slides', 'id = :id', ['id' => $id]);
    }
}
