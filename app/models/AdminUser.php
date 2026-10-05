<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class AdminUser
{
    public static function findByEmail(string $email): ?array
    {
        return Database::fetch("SELECT * FROM admin_users WHERE email = :email", ['email' => strtolower(trim($email))]);
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM admin_users WHERE id = :id", ['id' => $id]);
    }

    public static function verify(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);
        if (!$user) {
            return null;
        }

        if (password_verify($password, $user['password_hash'])) {
            Database::update('admin_users', [
                'last_login_at' => date('Y-m-d H:i:s')
            ], 'id = :id', ['id' => $user['id']]);
            return $user;
        }

        return null;
    }

    public static function updatePassword(int $id, string $newPassword): void
    {
        Database::update('admin_users', [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT)
        ], 'id = :id', ['id' => $id]);
    }
}
