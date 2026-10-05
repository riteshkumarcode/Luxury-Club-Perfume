<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Session;
use App\Core\Database;

class Cart
{
    private const SESSION_CART_KEY = 'luxury_cart';
    private const SESSION_WISHLIST_KEY = 'luxury_wishlist';

    public static function init(): void
    {
        Session::start();
        if (!Session::has(self::SESSION_CART_KEY)) {
            Session::set(self::SESSION_CART_KEY, []);
        }
        if (!Session::has(self::SESSION_WISHLIST_KEY)) {
            Session::set(self::SESSION_WISHLIST_KEY, []);
        }
    }

    public static function getCart(): array
    {
        self::init();
        return Session::get(self::SESSION_CART_KEY, []);
    }

    public static function add(int $productId, int $qty = 1): void
    {
        self::init();
        $cart = self::getCart();
        $qty = max(1, $qty);
        
        // Verify product exists and is active
        $product = Product::findById($productId);
        if (!$product || $product['is_active'] != 1) {
            return;
        }

        if (isset($cart[$productId])) {
            $cart[$productId] += $qty;
        } else {
            $cart[$productId] = $qty;
        }

        // Cap to available stock
        $stock = (int)$product['stock_qty'];
        if ($stock > 0 && $cart[$productId] > $stock) {
            $cart[$productId] = $stock;
        }

        Session::set(self::SESSION_CART_KEY, $cart);
    }

    public static function update(int $productId, int $qty): void
    {
        self::init();
        $cart = self::getCart();
        
        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::findById($productId);
            if ($product && $product['is_active'] == 1) {
                $stock = (int)$product['stock_qty'];
                $cart[$productId] = ($stock > 0 && $qty > $stock) ? $stock : $qty;
            } else {
                unset($cart[$productId]);
            }
        }

        Session::set(self::SESSION_CART_KEY, $cart);
    }

    public static function remove(int $productId): void
    {
        self::init();
        $cart = self::getCart();
        unset($cart[$productId]);
        Session::set(self::SESSION_CART_KEY, $cart);
    }

    public static function clear(): void
    {
        self::init();
        Session::set(self::SESSION_CART_KEY, []);
    }

    public static function getItemsWithDetails(): array
    {
        $cart = self::getCart();
        if (empty($cart)) {
            return [];
        }

        $items = [];
        $ids = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $products = Database::fetchAll("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.id IN ($placeholders)
        ", $ids);

        $productsById = [];
        foreach ($products as $p) {
            $productsById[$p['id']] = $p;
        }

        foreach ($cart as $productId => $qty) {
            if (isset($productsById[$productId])) {
                $p = $productsById[$productId];
                $price = (int)$p['price'];
                $lineTotal = $price * $qty;
                $items[] = [
                    'id' => (int)$p['id'],
                    'name' => $p['name'],
                    'slug' => $p['slug'],
                    'category_name' => $p['category_name'],
                    'size_label' => $p['size_label'],
                    'price' => $price,
                    'qty' => (int)$qty,
                    'line_total' => $lineTotal,
                    'image' => $p['image'],
                    'tint' => $p['tint'],
                    'stock_qty' => (int)$p['stock_qty'],
                ];
            }
        }

        return $items;
    }

    public static function getTotals(): array
    {
        $items = self::getItemsWithDetails();
        $subtotal = 0;
        $count = 0;

        foreach ($items as $item) {
            $subtotal += $item['line_total'];
            $count += $item['qty'];
        }

        $threshold = (int)get_setting('free_shipping_threshold', '999');
        $shippingFee = (int)get_setting('shipping_fee', '99');

        if ($subtotal === 0) {
            $shipping = 0;
            $freeShipping = false;
            $amountNeeded = $threshold;
        } elseif ($subtotal >= $threshold) {
            $shipping = 0;
            $freeShipping = true;
            $amountNeeded = 0;
        } else {
            $shipping = $shippingFee;
            $freeShipping = false;
            $amountNeeded = $threshold - $subtotal;
        }

        $total = $subtotal + $shipping;

        return [
            'count' => $count,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'threshold' => $threshold,
            'free_shipping_unlocked' => $freeShipping,
            'amount_needed' => $amountNeeded,
            'progress_percent' => $threshold > 0 ? min(100, round(($subtotal / $threshold) * 100)) : 100,
            'total' => $total,
        ];
    }

    public static function getWishlist(): array
    {
        self::init();
        return Session::get(self::SESSION_WISHLIST_KEY, []);
    }

    public static function toggleWishlist(int $productId): bool
    {
        self::init();
        $wishlist = self::getWishlist();
        $isAdded = false;

        if (in_array($productId, $wishlist, true)) {
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            $isAdded = false;
        } else {
            $product = Product::findById($productId);
            if ($product && $product['is_active'] == 1) {
                $wishlist[] = $productId;
                $isAdded = true;
            }
        }

        Session::set(self::SESSION_WISHLIST_KEY, array_values(array_unique($wishlist)));
        return $isAdded;
    }

    public static function isInWishlist(int $productId): bool
    {
        return in_array($productId, self::getWishlist(), true);
    }

    public static function getWishlistItemsWithDetails(): array
    {
        $wishlist = self::getWishlist();
        if (empty($wishlist)) {
            return [];
        }
        return Product::getByIds($wishlist);
    }
}
