<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Subscriber
{
    public static function subscribe(string $email): bool
    {
        $email = strtolower(trim($email));
        $existing = Database::fetch("SELECT id FROM subscribers WHERE email = :email", ['email' => $email]);
        if ($existing) {
            return true; // already subscribed
        }

        try {
            Database::insert('subscribers', [
                'email' => $email,
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function all(int $limit = 100, int $offset = 0): array
    {
        return Database::fetchAll("SELECT * FROM subscribers ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}");
    }

    public static function count(): int
    {
        $row = Database::fetch("SELECT COUNT(*) AS cnt FROM subscribers");
        return (int)($row['cnt'] ?? 0);
    }
}
