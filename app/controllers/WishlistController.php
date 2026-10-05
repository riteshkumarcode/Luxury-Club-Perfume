<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Cart;

class WishlistController
{
    public function show(): void
    {
        $items = Cart::getWishlistItemsWithDetails();

        View::setMeta([
            'title' => 'Your Saved Fragrances | Luxury Club',
            'description' => 'Browse and move your saved perfumes and roll-on attars to your shopping bag.'
        ]);

        View::render('pages/wishlist', [
            'items' => $items,
        ]);
    }

    public function toggleApi(): void
    {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?: $_POST;

        $productId = (int)($input['product_id'] ?? 0);
        if ($productId <= 0) {
            json_response(['success' => false, 'message' => 'Invalid product identifier.'], 400);
        }

        $isAdded = Cart::toggleWishlist($productId);
        $wishlist = Cart::getWishlist();

        json_response([
            'success' => true,
            'is_added' => $isAdded,
            'count' => count($wishlist),
            'message' => $isAdded ? 'Saved to wishlist.' : 'Removed from wishlist.',
        ]);
    }
}
