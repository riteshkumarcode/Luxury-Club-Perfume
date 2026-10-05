<?php
declare(strict_types=1);

namespace App\Core;

class Session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $isHttps = (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            ($_SERVER['SERVER_PORT'] ?? '') == 443 ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        );

        $lifetime = (int)config('session.lifetime', 7200);

        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');

        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path' => '/',
            'domain' => '',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        session_start();
        self::$started = true;

        // Clean expired flash messages
        if (isset($_SESSION['_flashes_prev'])) {
            unset($_SESSION['_flashes_prev']);
        }
        if (isset($_SESSION['_flashes'])) {
            $_SESSION['_flashes_prev'] = $_SESSION['_flashes'];
            unset($_SESSION['_flashes']);
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function regenerate(bool $deleteOld = true): void
    {
        self::start();
        session_regenerate_id($deleteOld);
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        self::$started = false;
    }

    public static function flash(string $key, string $message): void
    {
        self::start();
        $_SESSION['_flashes'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        self::start();
        return $_SESSION['_flashes_prev'][$key] ?? $_SESSION['_flashes'][$key] ?? null;
    }
}
