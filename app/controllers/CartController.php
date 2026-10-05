<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Cart;

class CartController
{
    public function show(): void
    {
        $items = Cart::getItemsWithDetails();
        $totals = Cart::getTotals();

        View::setMeta([
            'title' => 'Shopping Bag | Luxury Club',
            'description' => 'Review your selected luxury fragrances, attars and candles before checkout.'
        ]);

        View::render('pages/cart', [
            'items' => $items,
            'totals' => $totals,
        ]);
    }

    public function getCartApi(): void
    {
        $items = Cart::getItemsWithDetails();
        $totals = Cart::getTotals();
        json_response([
            'success' => true,
            'items' => $items,
            'totals' => $totals,
        ]);
    }

    public function addApi(): void
    {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?: $_POST;

        $productId = (int)($input['product_id'] ?? 0);
        $qty = max(1, (int)($input['qty'] ?? 1));

        if ($productId <= 0) {
            json_response(['success' => false, 'message' => 'Invalid product identifier.'], 400);
        }

        Cart::add($productId, $qty);

        $items = Cart::getItemsWithDetails();
        $totals = Cart::getTotals();

        json_response([
            'success' => true,
            'message' => 'Item added to bag.',
            'items' => $items,
            'totals' => $totals,
        ]);
    }

    public function updateApi(): void
    {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?: $_POST;

        $productId = (int)($input['product_id'] ?? 0);
        $qty = (int)($input['qty'] ?? 1);

        if ($productId <= 0) {
            json_response(['success' => false, 'message' => 'Invalid product identifier.'], 400);
        }

        Cart::update($productId, $qty);

        $items = Cart::getItemsWithDetails();
        $totals = Cart::getTotals();

        json_response([
            'success' => true,
            'message' => 'Bag updated.',
            'items' => $items,
            'totals' => $totals,
        ]);
    }

    public function removeApi(): void
    {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?: $_POST;

        $productId = (int)($input['product_id'] ?? 0);

        if ($productId <= 0) {
            json_response(['success' => false, 'message' => 'Invalid product identifier.'], 400);
        }

        Cart::remove($productId);

        $items = Cart::getItemsWithDetails();
        $totals = Cart::getTotals();

        json_response([
            'success' => true,
            'message' => 'Item removed from bag.',
            'items' => $items,
            'totals' => $totals,
        ]);
    }
}
