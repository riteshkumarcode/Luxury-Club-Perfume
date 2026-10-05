<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Message
{
    public static function create(array $data): int
    {
        return Database::insert('messages', [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'topic' => $data['topic'],
            'message' => $data['message'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'is_read' => 0,
        ]);
    }

    public static function all(int $limit = 30, int $offset = 0): array
    {
        return Database::fetchAll("
            SELECT * FROM messages 
            ORDER BY is_read ASC, id DESC 
            LIMIT {$limit} OFFSET {$offset}
        ");
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM messages WHERE id = :id", ['id' => $id]);
    }

    public static function markAsRead(int $id): void
    {
        Database::update('messages', ['is_read' => 1], 'id = :id', ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('messages', 'id = :id', ['id' => $id]);
    }
}
