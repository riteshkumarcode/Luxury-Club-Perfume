<?php
declare(strict_types=1);

namespace App\Core;

class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function getToken(): string
    {
        Session::start();
        $token = Session::get(self::SESSION_KEY);
        if (empty($token) || !is_string($token)) {
            $token = bin2hex(random_bytes(32));
            Session::set(self::SESSION_KEY, $token);
        }
        return $token;
    }

    public static function validate(?string $token = null): bool
    {
        Session::start();
        $sessionToken = Session::get(self::SESSION_KEY);
        if (empty($sessionToken) || !is_string($sessionToken)) {
            return false;
        }

        if ($token === null) {
            // Check POST payload or HTTP header
            $token = $_POST['_csrf_token'] ?? 
                     $_SERVER['HTTP_X_CSRF_TOKEN'] ?? 
                     $_SERVER['HTTP_X_XSRF_TOKEN'] ?? 
                     null;

            // Also check json body if application/json
            if ($token === null && str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
                $raw = file_get_contents('php://input');
                $decoded = json_decode($raw, true);
                if (is_array($decoded) && isset($decoded['_csrf_token'])) {
                    $token = $decoded['_csrf_token'];
                }
            }
        }

        if (empty($token) || !is_string($token)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }
}
