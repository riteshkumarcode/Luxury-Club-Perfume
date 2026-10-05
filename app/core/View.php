<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    private static array $meta = [
        'title' => 'Luxury Club — The Magic of Luxury Fragrances',
        'description' => 'Discover Luxury Club, India\'s premier artisanal perfumery featuring concentrated Eau de Parfum, alcohol-free roll-on attars, scented candles and car hanging pods.',
        'canonical' => '',
        'og_image' => '',
        'og_type' => 'website',
        'json_ld' => []
    ];

    public static function setMeta(array $meta): void
    {
        self::$meta = array_merge(self::$meta, $meta);
    }

    public static function getMeta(): array
    {
        if (empty(self::$meta['canonical'])) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
            $uri = explode('?', $_SERVER['REQUEST_URI'] ?? '/')[0];
            self::$meta['canonical'] = "$scheme://$host$uri";
        }
        if (empty(self::$meta['og_image'])) {
            self::$meta['og_image'] = url('/assets/img/products/blue-orchid.png');
        }
        return self::$meta;
    }

    public static function addJsonLd(array $schema): void
    {
        self::$meta['json_ld'][] = $schema;
    }

    /**
     * Render a view file inside a layout.
     */
    public static function render(string $viewPath, array $data = [], string $layout = 'layouts/main'): void
    {
        extract($data);
        $viewsDir = dirname(__DIR__) . '/views/';

        ob_start();
        $file = $viewsDir . ltrim($viewPath, '/') . '.php';
        if (!file_exists($file)) {
            throw new \Exception("View not found: $file");
        }
        require $file;
        $content = ob_get_clean();

        if ($layout === '' || $layout === null) {
            echo $content;
            return;
        }

        $layoutFile = $viewsDir . ltrim($layout, '/') . '.php';
        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout not found: $layoutFile");
        }
        require $layoutFile;
    }

    /**
     * Render a view partial and return its HTML string.
     */
    public static function partial(string $partialPath, array $data = []): string
    {
        extract($data);
        $file = dirname(__DIR__) . '/views/' . ltrim($partialPath, '/') . '.php';
        if (!file_exists($file)) {
            return "<!-- partial:$partialPath missing -->";
        }
        ob_start();
        require $file;
        return ob_get_clean();
    }
}
