<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Setting
{
    public static function getAll(): array
    {
        $rows = Database::fetchAll("SELECT `key`, `value` FROM `settings`");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }

    public static function get(string $key, string $default = ''): string
    {
        $row = Database::fetch("SELECT `value` FROM `settings` WHERE `key` = :key", ['key' => $key]);
        return $row ? (string)$row['value'] : $default;
    }

    public static function set(string $key, string $value): void
    {
        $existing = Database::fetch("SELECT `key` FROM `settings` WHERE `key` = :key", ['key' => $key]);
        if ($existing) {
            Database::update('settings', ['value' => $value], '`key` = :where_key', ['where_key' => $key]);
        } else {
            Database::insert('settings', ['key' => $key, 'value' => $value]);
        }
    }

    public static function setMultiple(array $pairs): void
    {
        foreach ($pairs as $key => $val) {
            if (is_array($val)) {
                $val = json_encode($val, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            self::set((string)$key, (string)$val);
        }
    }
}
