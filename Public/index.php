<?php
declare(strict_types=1);

/**
 * Luxury Club — Front Controller (public/index.php)
 * Point domain or document root to this directory.
 */

$rootPath = dirname(__DIR__);

// 1. Error Reporting Configuration
error_reporting(E_ALL);
$logDir = $rootPath . '/storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/error.log');

// 2. Autoloading (Composer PSR-4 + Fallback)
if (file_exists($rootPath . '/vendor/autoload.php')) {
    require_once $rootPath . '/vendor/autoload.php';
} else {
    // Basic PSR-4 autoloader fallback if vendor is missing
    spl_autoload_register(function ($class) use ($rootPath) {
        if (str_starts_with($class, 'App\\')) {
            $relative = str_replace('App\\', '', $class);
            $file = $rootPath . '/app/' . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
        }
    });
}

// Ensure helpers are loaded
require_once $rootPath . '/app/core/Helpers.php';

// 3. Security HTTP Headers
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Set display_errors from config
$isDebug = config('app.debug', true);
ini_set('display_errors', $isDebug ? '1' : '0');

// 4. Session & Database Initialization
\App\Core\Session::start();
\App\Core\Database::init(config('db'));

// 5. Router & Route Declarations
$router = new \App\Core\Router();

// --- Frontend Web Routes ---
$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/shop', [\App\Controllers\ShopController::class, 'index']);
$router->get('/shop/{category}', [\App\Controllers\ShopController::class, 'index']);
$router->get('/product/{slug}', [\App\Controllers\ProductController::class, 'show']);
$router->get('/about', [\App\Controllers\PageController::class, 'about']);
$router->get('/contact', [\App\Controllers\ContactController::class, 'show']);
$router->get('/faq', [\App\Controllers\PageController::class, 'faq']);
$router->get('/cart', [\App\Controllers\CartController::class, 'show']);
$router->get('/wishlist', [\App\Controllers\WishlistController::class, 'show']);
$router->get('/checkout', [\App\Controllers\CheckoutController::class, 'show']);
$router->post('/checkout', [\App\Controllers\CheckoutController::class, 'process'], ['csrf']);
$router->get('/order/success/{order_no}', [\App\Controllers\CheckoutController::class, 'success']);
$router->get('/policies/{slug}', [\App\Controllers\PageController::class, 'policy']);

// SEO & Meta
$router->get('/sitemap.xml', [\App\Controllers\PageController::class, 'sitemap']);
$router->get('/robots.txt', [\App\Controllers\PageController::class, 'robots']);

// --- AJAX Endpoints ---
$router->get('/api/cart', [\App\Controllers\CartController::class, 'getCartApi']);
$router->post('/api/cart/add', [\App\Controllers\CartController::class, 'addApi'], ['csrf']);
$router->post('/api/cart/update', [\App\Controllers\CartController::class, 'updateApi'], ['csrf']);
$router->post('/api/cart/remove', [\App\Controllers\CartController::class, 'removeApi'], ['csrf']);
$router->post('/api/wishlist/toggle', [\App\Controllers\WishlistController::class, 'toggleApi'], ['csrf']);
$router->get('/api/search', [\App\Controllers\SearchController::class, 'search']);
$router->get('/api/products', [\App\Controllers\ProductController::class, 'getByIdsApi']);
$router->post('/api/pincode', [\App\Controllers\CheckoutController::class, 'checkPincodeApi'], ['csrf']);
$router->post('/api/newsletter', [\App\Controllers\NewsletterController::class, 'subscribe'], ['csrf']);
$router->post('/api/contact', [\App\Controllers\ContactController::class, 'submit'], ['csrf']);

// --- Admin Authentication Routes ---
$router->get('/admin/login', [\App\Controllers\Admin\AdminAuthController::class, 'showLogin']);
$router->post('/admin/login', [\App\Controllers\Admin\AdminAuthController::class, 'login'], ['csrf']);
$router->get('/admin/logout', [\App\Controllers\Admin\AdminAuthController::class, 'logout']);

// --- Protected Admin Routes ---
$router->get('/admin', [\App\Controllers\Admin\AdminDashboardController::class, 'index'], ['auth:admin']);

// Products CRUD
$router->get('/admin/products', [\App\Controllers\Admin\AdminProductController::class, 'index'], ['auth:admin']);
$router->get('/admin/products/create', [\App\Controllers\Admin\AdminProductController::class, 'create'], ['auth:admin']);
$router->post('/admin/products/create', [\App\Controllers\Admin\AdminProductController::class, 'store'], ['auth:admin', 'csrf']);
$router->get('/admin/products/edit/{id}', [\App\Controllers\Admin\AdminProductController::class, 'edit'], ['auth:admin']);
$router->post('/admin/products/edit/{id}', [\App\Controllers\Admin\AdminProductController::class, 'update'], ['auth:admin', 'csrf']);
$router->post('/admin/products/delete/{id}', [\App\Controllers\Admin\AdminProductController::class, 'delete'], ['auth:admin', 'csrf']);

// Categories CRUD
$router->get('/admin/categories', [\App\Controllers\Admin\AdminCategoryController::class, 'index'], ['auth:admin']);
$router->get('/admin/categories/create', [\App\Controllers\Admin\AdminCategoryController::class, 'create'], ['auth:admin']);
$router->post('/admin/categories/create', [\App\Controllers\Admin\AdminCategoryController::class, 'store'], ['auth:admin', 'csrf']);
$router->get('/admin/categories/edit/{id}', [\App\Controllers\Admin\AdminCategoryController::class, 'edit'], ['auth:admin']);
$router->post('/admin/categories/edit/{id}', [\App\Controllers\Admin\AdminCategoryController::class, 'update'], ['auth:admin', 'csrf']);
$router->post('/admin/categories/delete/{id}', [\App\Controllers\Admin\AdminCategoryController::class, 'delete'], ['auth:admin', 'csrf']);

// Hero Slides CRUD
$router->get('/admin/slides', [\App\Controllers\Admin\AdminSlideController::class, 'index'], ['auth:admin']);
$router->get('/admin/slides/create', [\App\Controllers\Admin\AdminSlideController::class, 'create'], ['auth:admin']);
$router->post('/admin/slides/create', [\App\Controllers\Admin\AdminSlideController::class, 'store'], ['auth:admin', 'csrf']);
$router->get('/admin/slides/edit/{id}', [\App\Controllers\Admin\AdminSlideController::class, 'edit'], ['auth:admin']);
$router->post('/admin/slides/edit/{id}', [\App\Controllers\Admin\AdminSlideController::class, 'update'], ['auth:admin', 'csrf']);
$router->post('/admin/slides/delete/{id}', [\App\Controllers\Admin\AdminSlideController::class, 'delete'], ['auth:admin', 'csrf']);

// Orders
$router->get('/admin/orders', [\App\Controllers\Admin\AdminOrderController::class, 'index'], ['auth:admin']);
$router->get('/admin/orders/{id}', [\App\Controllers\Admin\AdminOrderController::class, 'show'], ['auth:admin']);
$router->post('/admin/orders/{id}/status', [\App\Controllers\Admin\AdminOrderController::class, 'updateStatus'], ['auth:admin', 'csrf']);

// Messages / Inquiries
$router->get('/admin/messages', [\App\Controllers\Admin\AdminMessageController::class, 'index'], ['auth:admin']);
$router->post('/admin/messages/{id}/read', [\App\Controllers\Admin\AdminMessageController::class, 'markRead'], ['auth:admin', 'csrf']);
$router->post('/admin/messages/{id}/delete', [\App\Controllers\Admin\AdminMessageController::class, 'delete'], ['auth:admin', 'csrf']);

// Subscribers
$router->get('/admin/subscribers', [\App\Controllers\Admin\AdminSubscriberController::class, 'index'], ['auth:admin']);
$router->get('/admin/subscribers/export', [\App\Controllers\Admin\AdminSubscriberController::class, 'exportCsv'], ['auth:admin']);

// Settings
$router->get('/admin/settings', [\App\Controllers\Admin\AdminSettingController::class, 'index'], ['auth:admin']);
$router->post('/admin/settings', [\App\Controllers\Admin\AdminSettingController::class, 'save'], ['auth:admin', 'csrf']);

// 6. Dispatch Request
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($requestUri, $requestMethod);
