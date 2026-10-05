<?php
declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Core\Database;

if (!function_exists('e')) {
    /**
     * Escape HTML output securely.
     */
    function e(?string $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('formatInr')) {
    /**
     * Format numbers into Indian Rupees currency format e.g. ₹1,499 or ₹1,00,000.
     */
    function formatInr(int|float $amount): string
    {
        $amount = (int)round($amount);
        if (class_exists(\NumberFormatter::class)) {
            try {
                $formatter = new \NumberFormatter('en_IN', \NumberFormatter::CURRENCY);
                $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
                $formatted = $formatter->formatCurrency($amount, 'INR');
                if ($formatted !== false) {
                    return $formatted;
                }
            } catch (\Exception $e) {
                // fallback below
            }
        }

        // Manual Indian digit grouping fallback
        $negative = $amount < 0 ? '-' : '';
        $num = (string)abs($amount);
        $len = strlen($num);
        if ($len > 3) {
            $lastThree = substr($num, -3);
            $rest = substr($num, 0, $len - 3);
            $restFormatted = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
            return '₹' . $negative . $restFormatted . ',' . $lastThree;
        }
        return '₹' . $negative . $num;
    }
}

if (!function_exists('config')) {
    /**
     * Retrieve configuration values by dot-notation.
     */
    function config(string $key, mixed $default = null): mixed
    {
        static $cfg = null;
        if ($cfg === null) {
            $cfg = require dirname(__DIR__, 2) . '/config/config.php';
        }
        $parts = explode('.', $key);
        $current = $cfg;
        foreach ($parts as $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return $default;
            }
            $current = $current[$part];
        }
        return $current;
    }
}

if (!function_exists('url')) {
    /**
     * Return normalized root-relative or absolute URL.
     */
    function url(string $path = '', bool $absolute = false): string
    {
        $cleanPath = '/' . ltrim($path, '/');
        if ($cleanPath === '//') {
            $cleanPath = '/';
        }

        if ($absolute) {
            $baseUrl = rtrim((string)config('app.url', ''), '/');
            if (empty($baseUrl) || str_contains($baseUrl, 'localhost')) {
                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $host = $_SERVER['HTTP_HOST'] ?? '';
                if ($host && !str_contains($host, 'localhost')) {
                    $baseUrl = $scheme . $host;
                } else {
                    $baseUrl = '';
                }
            }
            return $baseUrl ? $baseUrl . $cleanPath : $cleanPath;
        }

        return $cleanPath;
    }
}

if (!function_exists('asset')) {
    /**
     * Return asset URL with cache-busting timestamp version query parameter.
     */
    function asset(string $path): string
    {
        $cleanPath = '/' . ltrim($path, '/');
        $filePath = config('app.public_path') . $cleanPath;
        $version = file_exists($filePath) ? filemtime($filePath) : time();
        return $cleanPath . '?v=' . $version;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get or generate the current CSRF token.
     */
    function csrf_token(): string
    {
        return Csrf::getToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Output a hidden CSRF token input field.
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('session')) {
    /**
     * Get or set session variables.
     */
    function session(string $key, mixed $default = null): mixed
    {
        return Session::get($key, $default);
    }
}

if (!function_exists('flash')) {
    /**
     * Flash a message to the session for the next request.
     */
    function flash(string $key, ?string $message = null): ?string
    {
        if ($message === null) {
            return Session::getFlash($key);
        }
        Session::flash($key, $message);
        return null;
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to another URL and terminate execution.
     */
    function redirect(string $url, int $statusCode = 302): never
    {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = url($url);
        }
        header("Location: $url", true, $statusCode);
        exit;
    }
}

if (!function_exists('json_response')) {
    /**
     * Send a JSON response with status code.
     */
    function json_response(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if (!function_exists('slugify')) {
    /**
     * Generate a clean URL slug from string.
     */
    function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}

if (!function_exists('get_setting')) {
    /**
     * Retrieve a setting value from settings table with memory caching.
     */
    function get_setting(string $key, string $default = ''): string
    {
        static $settings = null;
        if ($settings === null) {
            $settings = [];
            try {
                $rows = Database::fetchAll("SELECT `key`, `value` FROM `settings`");
                foreach ($rows as $row) {
                    $settings[$row['key']] = $row['value'];
                }
            } catch (\Exception $e) {
                // Table might not be ready yet
            }
        }
        return $settings[$key] ?? $default;
    }
}

if (!function_exists('icon')) {
    /**
     * Render an inline SVG icon partial from app/views/icons/{name}.php.
     */
    function icon(string $name, string $class = ''): string
    {
        $path = dirname(__DIR__) . "/views/icons/{$name}.php";
        if (file_exists($path)) {
            ob_start();
            $iconClass = $class;
            require $path;
            return ob_get_clean();
        }
        return "<!-- icon:$name missing -->";
    }
}
