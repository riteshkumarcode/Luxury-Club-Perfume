<?php
/**
 * Luxury Club — Netlify Static Site Exporter (build.php)
 * Pre-renders all dynamic PHP routes, models, categories, and products into static HTML for Netlify CDN.
 */

// 1. Initialize environment & database
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Core\Router;
use App\Core\Database;
use App\Core\View;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;

// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

echo "✦ Luxury Club — Starting Netlify Static Site Build...\n";

// Ensure DB connection and tables
Database::getInstance();

$distDir = __DIR__ . '/dist';

// Helper to remove directory recursively
function cleanDir($dir) {
    if (!is_dir($dir)) return;
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = "$dir/$file";
        is_dir($path) ? cleanDir($path) : unlink($path);
    }
    rmdir($dir);
}

// Recreate dist directory
if (is_dir($distDir)) {
    cleanDir($distDir);
}
mkdir($distDir, 0777, true);

// Helper to copy directory recursively
function copyDir($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst, 0777, true);
    while (false !== ($file = readdir($dir))) {
        if ($file != '.' && $file != '..') {
            if (is_dir($src . '/' . $file)) {
                copyDir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// 2. Copy static assets
echo "  → Copying public assets to dist/assets...\n";
copyDir(__DIR__ . '/public/assets', $distDir . '/assets');
if (is_dir(__DIR__ . '/public/uploads')) {
    copyDir(__DIR__ . '/public/uploads', $distDir . '/uploads');
}

// 3. Helper to capture rendered output of a route
function renderRoute($controllerClass, $method, $params = []) {
    ob_start();
    $controller = new $controllerClass();
    call_user_func_array([$controller, $method], $params);
    return ob_get_clean();
}

// Helper to write static file
function writePage($relativePath, $html) {
    global $distDir;
    $fullPath = $distDir . '/' . ltrim($relativePath, '/');
    $dir = dirname($fullPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($fullPath, $html);
    echo "  ✓ Generated: {$relativePath}\n";
}

// 4. Generate Core Pages
echo "\n✦ Pre-rendering Core Pages...\n";

// Home
writePage('index.html', renderRoute(\App\Controllers\HomeController::class, 'index'));

// Shop Index
writePage('shop/index.html', renderRoute(\App\Controllers\ShopController::class, 'index'));

// Category Pages
$categories = Category::allActive();
foreach ($categories as $cat) {
    writePage("shop/{$cat['slug']}/index.html", renderRoute(\App\Controllers\ShopController::class, 'index', [$cat['slug']]));
}

// Product Detail Pages
$products = Product::allActive();
foreach ($products as $prod) {
    writePage("product/{$prod['slug']}/index.html", renderRoute(\App\Controllers\ProductController::class, 'show', [$prod['slug']]));
}

// About
writePage('about/index.html', renderRoute(\App\Controllers\PageController::class, 'about'));

// Contact
writePage('contact/index.html', renderRoute(\App\Controllers\ContactController::class, 'show'));

// FAQ
writePage('faq/index.html', renderRoute(\App\Controllers\PageController::class, 'faq'));

// Cart
writePage('cart/index.html', renderRoute(\App\Controllers\CartController::class, 'show'));

// Wishlist
writePage('wishlist/index.html', renderRoute(\App\Controllers\WishlistController::class, 'show'));

// Checkout
ob_start();
View::setMeta([
    'title' => 'Checkout | Luxury Club',
    'description' => 'Complete your purchase securely. Complimentary express shipping and gift packaging.'
]);
View::render('pages/checkout', [
    'items' => [],
    'totals' => ['subtotal' => 0, 'shipping' => 0, 'total' => 0, 'count' => 0, 'free_shipping_shortfall' => 999]
]);
$checkoutHtml = ob_get_clean();
writePage('checkout/index.html', $checkoutHtml);

// Policies
$policies = ['shipping', 'returns', 'privacy', 'terms'];
foreach ($policies as $slug) {
    writePage("policies/{$slug}/index.html", renderRoute(\App\Controllers\PageController::class, 'policy', [$slug]));
}

// 404 Page
ob_start();
View::setMeta(['title' => 'Page Not Found | Luxury Club']);
View::render('pages/404', ['path' => '/404']);
writePage('404.html', ob_get_clean());

// Sitemap & Robots
$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');
$staticUrls = [
    ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
    ['url' => url('/shop'), 'priority' => '0.9', 'changefreq' => 'daily'],
    ['url' => url('/about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['url' => url('/contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['url' => url('/faq'), 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['url' => url('/policies/shipping'), 'priority' => '0.5', 'changefreq' => 'yearly'],
    ['url' => url('/policies/returns'), 'priority' => '0.5', 'changefreq' => 'yearly'],
    ['url' => url('/policies/privacy'), 'priority' => '0.5', 'changefreq' => 'yearly'],
    ['url' => url('/policies/terms'), 'priority' => '0.5', 'changefreq' => 'yearly'],
];
foreach ($staticUrls as $s) {
    $u = $xml->addChild('url');
    $u->addChild('loc', $s['url']);
    $u->addChild('changefreq', $s['changefreq']);
    $u->addChild('priority', $s['priority']);
}
foreach ($categories as $cat) {
    $u = $xml->addChild('url');
    $u->addChild('loc', url('/shop/' . $cat['slug']));
    $u->addChild('changefreq', 'weekly');
    $u->addChild('priority', '0.8');
}
foreach ($products as $p) {
    $u = $xml->addChild('url');
    $u->addChild('loc', url('/product/' . $p['slug']));
    $u->addChild('lastmod', date('Y-m-d', strtotime($p['updated_at'] ?? $p['created_at'] ?? 'now')));
    $u->addChild('changefreq', 'weekly');
    $u->addChild('priority', '0.8');
}
writePage('sitemap.xml', $xml->asXML());

$robotsTxt = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /api\nDisallow: /checkout\nDisallow: /order/success\nSitemap: " . url('/sitemap.xml') . "\n";
writePage('robots.txt', $robotsTxt);

// 5. Generate Client-Side JSON APIs for Static Search & Product Indexing
echo "\n✦ Generating Static API JSON endpoints for client-side search...\n";
@mkdir($distDir . '/api', 0777, true);

$searchData = [];
foreach ($products as $prod) {
    $searchData[] = [
        'id' => $prod['id'],
        'name' => $prod['name'],
        'slug' => $prod['slug'],
        'price' => $prod['price'],
        'size_label' => $prod['size_label'],
        'image' => $prod['image'],
        'tint' => $prod['tint'],
        'category_name' => $prod['category_name'] ?? 'Fragrance',
        'category_slug' => $prod['category_slug'] ?? '',
        'badge' => $prod['badge'] ?? null,
        'short_description' => $prod['short_description'] ?? '',
        'notes' => trim(($prod['notes_top'] ?? '') . ' ' . ($prod['notes_heart'] ?? '') . ' ' . ($prod['notes_base'] ?? ''))
    ];
}

file_put_contents($distDir . '/api/search.json', json_encode([
    'success' => true,
    'count' => count($searchData),
    'results' => $searchData
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

file_put_contents($distDir . '/api/products.json', json_encode([
    'success' => true,
    'products' => $searchData
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "  ✓ Generated: api/search.json\n";
echo "  ✓ Generated: api/products.json\n";

echo "\n✦ Netlify Static Site Build Completed Successfully!\n";
echo "  → Output folder: " . realpath($distDir) . "\n";
