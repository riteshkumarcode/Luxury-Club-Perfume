<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?PDO $instance = null;
    private static array $config = [];

    public static function init(array $config): void
    {
        self::$config = $config;
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
            self::ensureTables();
        }
        return self::$instance;
    }

    private static function createConnection(): PDO
    {
        if (empty(self::$config)) {
            $appConfig = require dirname(__DIR__, 2) . '/config/config.php';
            self::$config = $appConfig['db'];
        }

        $driver = self::$config['connection'] ?? 'mysql';

        try {
            if ($driver === 'sqlite') {
                $dbPath = self::$config['sqlite_path'] ?? dirname(__DIR__, 2) . '/storage/database.sqlite';
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $pdo = new PDO("sqlite:$dbPath");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $pdo->exec("PRAGMA foreign_keys = ON;");
                return $pdo;
            }

            // Default MySQL
            $host = self::$config['host'] ?? '127.0.0.1';
            $port = self::$config['port'] ?? 3306;
            $dbName = self::$config['database'] ?? 'luxury_club';
            $user = self::$config['username'] ?? 'root';
            $pass = self::$config['password'] ?? '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // If MySQL failed and we are in development, fallback to SQLite so site works immediately
            if ($driver === 'mysql') {
                error_log("MySQL connection failed ({$e->getMessage()}). Attempting SQLite fallback...");
                $dbPath = dirname(__DIR__, 2) . '/storage/database.sqlite';
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $pdo = new PDO("sqlite:$dbPath");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $pdo->exec("PRAGMA foreign_keys = ON;");
                return $pdo;
            }
            throw new Exception("Database connection error: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    public static function ensureTables(): void
    {
        $pdo = self::$instance;
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        // Check if categories table exists
        $hasTables = false;
        try {
            if ($driver === 'sqlite') {
                $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='categories'");
                $hasTables = (bool)$stmt->fetch();
            } else {
                $stmt = $pdo->query("SHOW TABLES LIKE 'categories'");
                $hasTables = (bool)$stmt->fetch();
            }
        } catch (Exception $e) {
            $hasTables = false;
        }

        if (!$hasTables) {
            self::runMigrations($pdo, $driver);
        }
    }

    public static function runMigrations(PDO $pdo, string $driver): void
    {
        $root = dirname(__DIR__, 2);
        
        if ($driver === 'sqlite') {
            // Create SQLite compatible schema
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS categories (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    slug TEXT NOT NULL UNIQUE,
                    description TEXT,
                    cover_image TEXT,
                    sort_order INTEGER NOT NULL DEFAULT 0,
                    is_active INTEGER NOT NULL DEFAULT 1,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE IF NOT EXISTS products (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    category_id INTEGER NOT NULL,
                    name TEXT NOT NULL,
                    slug TEXT NOT NULL UNIQUE,
                    size_label TEXT NOT NULL,
                    price INTEGER NOT NULL,
                    compare_at_price INTEGER,
                    image TEXT NOT NULL,
                    tint TEXT NOT NULL DEFAULT '#F3EDE2',
                    hero_bg TEXT,
                    badge TEXT,
                    short_description TEXT,
                    description TEXT,
                    notes_top TEXT,
                    notes_heart TEXT,
                    notes_base TEXT,
                    how_to_use TEXT,
                    tags TEXT,
                    stock_qty INTEGER NOT NULL DEFAULT 50,
                    is_featured INTEGER NOT NULL DEFAULT 0,
                    is_active INTEGER NOT NULL DEFAULT 1,
                    sort_order INTEGER NOT NULL DEFAULT 0,
                    meta_title TEXT,
                    meta_description TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
                );

                CREATE TABLE IF NOT EXISTS hero_slides (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    product_id INTEGER,
                    eyebrow TEXT NOT NULL,
                    title TEXT NOT NULL,
                    subline TEXT NOT NULL,
                    cta_label TEXT NOT NULL DEFAULT 'Explore Now',
                    cta_url TEXT NOT NULL DEFAULT '/shop',
                    cta2_label TEXT,
                    cta2_url TEXT,
                    image TEXT NOT NULL,
                    tint TEXT NOT NULL DEFAULT '#E3E7F3',
                    bg_color TEXT NOT NULL DEFAULT '#0E1633',
                    outline_word TEXT NOT NULL DEFAULT 'Luxury',
                    sort_order INTEGER NOT NULL DEFAULT 0,
                    is_active INTEGER NOT NULL DEFAULT 1,
                    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL
                );

                CREATE TABLE IF NOT EXISTS orders (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    order_no TEXT NOT NULL UNIQUE,
                    customer_name TEXT NOT NULL,
                    email TEXT NOT NULL,
                    phone TEXT NOT NULL,
                    address_line1 TEXT NOT NULL,
                    address_line2 TEXT,
                    city TEXT NOT NULL,
                    state TEXT NOT NULL,
                    pincode TEXT NOT NULL,
                    subtotal INTEGER NOT NULL,
                    shipping INTEGER NOT NULL DEFAULT 0,
                    total INTEGER NOT NULL,
                    payment_method TEXT NOT NULL DEFAULT 'razorpay',
                    payment_status TEXT NOT NULL DEFAULT 'pending',
                    razorpay_order_id TEXT,
                    razorpay_payment_id TEXT,
                    status TEXT NOT NULL DEFAULT 'new',
                    tracking_number TEXT,
                    notes TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE IF NOT EXISTS order_items (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    order_id INTEGER NOT NULL,
                    product_id INTEGER,
                    name_snapshot TEXT NOT NULL,
                    price_snapshot INTEGER NOT NULL,
                    qty INTEGER NOT NULL DEFAULT 1,
                    line_total INTEGER NOT NULL,
                    FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
                    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL
                );

                CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL,
                    phone TEXT,
                    topic TEXT NOT NULL,
                    message TEXT NOT NULL,
                    ip TEXT,
                    is_read INTEGER NOT NULL DEFAULT 0,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE IF NOT EXISTS subscribers (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    email TEXT NOT NULL UNIQUE,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE IF NOT EXISTS admin_users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    password_hash TEXT NOT NULL,
                    last_login_at DATETIME,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE IF NOT EXISTS settings (
                    key TEXT PRIMARY KEY,
                    value TEXT
                );
            ");
        } else {
            $schema = file_get_contents($root . '/database/schema.sql');
            $pdo->exec($schema);
        }

        // Run seed
        if (file_exists($root . '/database/seed.sql')) {
            $seedSql = file_get_contents($root . '/database/seed.sql');
            $lines = explode("\n", $seedSql);
            $cleanSql = '';
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (!str_starts_with($trimmed, '--')) {
                    $cleanSql .= $line . "\n";
                }
            }
            $statements = array_filter(array_map('trim', explode(';', $cleanSql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    try {
                        $pdo->exec($stmt);
                    } catch (Exception $e) {
                        error_log("Seed execution error: " . $e->getMessage());
                    }
                }
            }
        }
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $pdo = self::getInstance();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function insert(string $table, array $data): int
    {
        $pdo = self::getInstance();
        $keys = array_keys($data);
        $fields = implode(', ', array_map(fn($k) => "`$k`", $keys));
        $placeholders = implode(', ', array_map(fn($k) => ":$k", $keys));
        
        $sql = "INSERT INTO `$table` ($fields) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return (int)$pdo->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $pdo = self::getInstance();
        $setClauses = [];
        $params = [];
        foreach ($data as $key => $val) {
            $setClauses[] = "`$key` = :set_$key";
            $params["set_$key"] = $val;
        }
        $params = array_merge($params, $whereParams);
        $sql = "UPDATE `$table` SET " . implode(', ', $setClauses) . " WHERE $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        $pdo = self::getInstance();
        $sql = "DELETE FROM `$table` WHERE $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
}
