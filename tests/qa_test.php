<?php
/**
 * Automated QA & Integration Test Suite for Luxury Club
 */

$baseUrl = 'http://localhost:8000';
$cookieFile = __DIR__ . '/test_cookie.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

function http_request($url, $method = 'GET', $data = null, $headers = []) {
    global $cookieFile;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if (is_array($data)) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $headers[] = 'Content-Type: application/json';
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    curl_close($ch);

    return ['code' => $httpCode, 'headers' => $header, 'body' => $body];
}

echo "=== LUXURY CLUB AUTOMATED QA SUITE ===\n\n";

// 1. Home Page
$res = http_request("$baseUrl/");
echo "[TEST 1] GET / -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// Extract CSRF token from Home page
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $res['body'], $matches);
$csrfToken = $matches[1] ?? '';
echo "Extracted CSRF Token: " . substr($csrfToken, 0, 16) . "...\n";

// 2. Shop Page
$res = http_request("$baseUrl/shop");
echo "[TEST 2] GET /shop -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 3. Category Page
$res = http_request("$baseUrl/shop/eau-de-parfum");
echo "[TEST 3] GET /shop/eau-de-parfum -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 4. Product Detail Page
$res = http_request("$baseUrl/product/blue-orchid");
echo "[TEST 4] GET /product/blue-orchid -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 5. Non-existent Product (404 check)
$res = http_request("$baseUrl/product/unknown-scent-xyz");
echo "[TEST 5] GET /product/unknown-scent-xyz -> HTTP {$res['code']} " . ($res['code'] === 404 ? "PASS (Correct 404)" : "FAIL") . "\n";

// 6. Live Search API
$res = http_request("$baseUrl/api/search?q=oud");
$searchData = json_decode($res['body'], true);
echo "[TEST 6] GET /api/search?q=oud -> HTTP {$res['code']}, Found: " . ($searchData['count'] ?? 0) . " results " . ($res['code'] === 200 && ($searchData['count'] ?? 0) > 0 ? "PASS" : "FAIL") . "\n";

// 7. Cart Add API
$res = http_request("$baseUrl/api/cart/add", 'POST', ['product_id' => 1, 'qty' => 2], ["X-CSRF-Token: $csrfToken"]);
$cartData = json_decode($res['body'], true);
echo "[TEST 7] POST /api/cart/add (Blue Orchid x2) -> HTTP {$res['code']} Total: ₹" . ($cartData['totals']['total'] ?? 0) . " " . ($res['code'] === 200 && ($cartData['totals']['count'] ?? 0) === 2 ? "PASS" : "FAIL") . "\n";

// 8. Wishlist Toggle API
$res = http_request("$baseUrl/api/wishlist/toggle", 'POST', ['product_id' => 2], ["X-CSRF-Token: $csrfToken"]);
$wishData = json_decode($res['body'], true);
echo "[TEST 8] POST /api/wishlist/toggle (Red Crystal) -> HTTP {$res['code']} is_added: " . ($wishData['is_added'] ? 'true' : 'false') . " " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 9. Pincode Checker API
$res = http_request("$baseUrl/api/pincode", 'POST', ['pincode' => '400001'], ["X-CSRF-Token: $csrfToken"]);
$pinData = json_decode($res['body'], true);
echo "[TEST 9] POST /api/pincode (400001) -> HTTP {$res['code']} serviceable: " . ($pinData['serviceable'] ? 'true' : 'false') . " " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 10. Newsletter Subscribe API
$res = http_request("$baseUrl/api/newsletter", 'POST', ['email' => 'connoisseur@example.com'], ["X-CSRF-Token: $csrfToken"]);
$newsData = json_decode($res['body'], true);
echo "[TEST 10] POST /api/newsletter -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 11. Contact Form API
$res = http_request("$baseUrl/api/contact", 'POST', [
    'name' => 'Lord Krishna Somani',
    'email' => 'krishna@example.com',
    'phone' => '+91 9876543210',
    'topic' => 'Help choosing a fragrance',
    'message' => 'I would like assistance selecting a signature oud fragrance for an evening black-tie celebration.'
], ["X-CSRF-Token: $csrfToken"]);
$contactData = json_decode($res['body'], true);
echo "[TEST 11] POST /api/contact -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 12. Checkout Order Placement
$res = http_request("$baseUrl/checkout", 'POST', http_build_query([
    '_csrf_token' => $csrfToken,
    'customer_name' => 'Maharani Radhika',
    'email' => 'radhika@example.com',
    'phone' => '9876543210',
    'address_line1' => 'Palace Heights, Suite 100',
    'address_line2' => 'Marine Drive',
    'city' => 'Mumbai',
    'state' => 'Maharashtra',
    'pincode' => '400020',
    'payment_method' => 'cod',
    'notes' => 'Please include gift packaging.'
]), ['Content-Type: application/x-www-form-urlencoded']);
echo "[TEST 12] POST /checkout -> HTTP {$res['code']} (Redirect to order success) " . ($res['code'] === 302 ? "PASS" : "FAIL") . "\n";

// 13. Admin Login
$res = http_request("$baseUrl/admin/login", 'POST', http_build_query([
    '_csrf_token' => $csrfToken,
    'email' => 'admin@luxuryclub.com',
    'password' => 'AdminLuxury2026!'
]), ['Content-Type: application/x-www-form-urlencoded']);
echo "[TEST 13] POST /admin/login -> HTTP {$res['code']} (Redirect to /admin) " . ($res['code'] === 302 ? "PASS" : "FAIL") . "\n";

// 14. Admin Dashboard Access (Authenticated)
$res = http_request("$baseUrl/admin");
echo "[TEST 14] GET /admin -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 15. Admin Products List
$res = http_request("$baseUrl/admin/products");
echo "[TEST 15] GET /admin/products -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 16. Admin Orders List
$res = http_request("$baseUrl/admin/orders");
echo "[TEST 16] GET /admin/orders -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 17. Admin Settings Page
$res = http_request("$baseUrl/admin/settings");
echo "[TEST 17] GET /admin/settings -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 18. Sitemap XML
$res = http_request("$baseUrl/sitemap.xml");
echo "[TEST 18] GET /sitemap.xml -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

// 19. Robots TXT
$res = http_request("$baseUrl/robots.txt");
echo "[TEST 19] GET /robots.txt -> HTTP {$res['code']} " . ($res['code'] === 200 ? "PASS" : "FAIL") . "\n";

if (file_exists($cookieFile)) unlink($cookieFile);
echo "\n=== QA TEST SUITE COMPLETED ===\n";
