<?php
/**
 * Router script for PHP built-in web server:
 * php -S localhost:8000 public/router.php
 */
$publicDir = __DIR__;
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

$filePath = $publicDir . $uri;

// If a real static asset exists on disk (css, js, images, etc.)
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath) && !str_ends_with($filePath, '.php')) {
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'txt' => 'text/plain',
        'xml' => 'application/xml',
        'json' => 'application/json',
    ];

    if (isset($mimes[$ext])) {
        header("Content-Type: {$mimes[$ext]}");
        readfile($filePath);
        exit;
    }
    return false;
}

require_once $publicDir . '/index.php';
