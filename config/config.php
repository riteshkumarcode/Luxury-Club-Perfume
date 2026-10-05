<?php
declare(strict_types=1);

$rootPath = dirname(__DIR__);

// Load .env if present
if (file_exists($rootPath . '/.env')) {
    if (class_exists(\Dotenv\Dotenv::class)) {
        $dotenv = \Dotenv\Dotenv::createImmutable($rootPath);
        $dotenv->safeLoad();
    } else {
        // Fallback simple parser
        $lines = file($rootPath . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (str_contains($line, '=')) {
                [$k, $v] = explode('=', $line, 2);
                $k = trim($k);
                $v = trim($v, " \t\n\r\0\x0B\"'");
                $_ENV[$k] = $v;
                putenv("$k=$v");
            }
        }
    }
}

return [
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'Luxury Club',
        'tagline' => 'The Magic of Luxury Fragrances',
        'env' => $_ENV['APP_ENV'] ?? 'development',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'url' => rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/'),
        'root_path' => $rootPath,
        'public_path' => $rootPath . '/public',
        'storage_path' => $rootPath . '/storage',
    ],
    'db' => [
        'connection' => $_ENV['DB_CONNECTION'] ?? 'mysql',
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port' => (int)($_ENV['DB_PORT'] ?? 3306),
        'database' => $_ENV['DB_DATABASE'] ?? 'luxury_club',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'sqlite_path' => $rootPath . '/' . ($_ENV['DB_SQLITE_PATH'] ?? 'storage/database.sqlite'),
    ],
    'admin' => [
        'name' => $_ENV['ADMIN_NAME'] ?? 'Luxury Club Admin',
        'email' => $_ENV['ADMIN_EMAIL'] ?? 'admin@luxuryclub.com',
        'password' => $_ENV['ADMIN_PASSWORD'] ?? 'AdminLuxury2026!',
        'ip_allowlist' => array_filter(array_map('trim', explode(',', $_ENV['ADMIN_IP_ALLOWLIST'] ?? ''))),
    ],
    'razorpay' => [
        'key_id' => $_ENV['RAZORPAY_KEY_ID'] ?? '',
        'key_secret' => $_ENV['RAZORPAY_KEY_SECRET'] ?? '',
    ],
    'mail' => [
        'host' => $_ENV['SMTP_HOST'] ?? 'smtp.mailtrap.io',
        'port' => (int)($_ENV['SMTP_PORT'] ?? 2525),
        'username' => $_ENV['SMTP_USERNAME'] ?? '',
        'password' => $_ENV['SMTP_PASSWORD'] ?? '',
        'encryption' => $_ENV['SMTP_ENCRYPTION'] ?? 'tls',
        'from_address' => $_ENV['SMTP_FROM_ADDRESS'] ?? 'concierge@luxuryclub.com',
        'from_name' => $_ENV['SMTP_FROM_NAME'] ?? 'Luxury Club Concierge',
        'admin_notify' => $_ENV['ADMIN_NOTIFY_EMAIL'] ?? 'admin@luxuryclub.com',
    ],
    'session' => [
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200),
        'csrf_secret' => $_ENV['CSRF_SECRET'] ?? 'luxury_club_secret_2026',
    ]
];
