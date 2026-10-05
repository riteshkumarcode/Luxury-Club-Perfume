<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Category;
use App\Models\Product;

class ShopController
{
    public function index(?string $categorySlug = null): void
    {
        $categories = Category::allActive();
        $currentCategory = null;

        if ($categorySlug !== null) {
            $currentCategory = Category::findBySlug($categorySlug);
            if (!$currentCategory) {
                // Not found
                http_response_code(404);
                View::render('pages/404', ['path' => "/shop/$categorySlug"]);
                return;
            }
        }

        $filters = [
            'category' => $currentCategory['id'] ?? null,
            'q' => $_GET['q'] ?? null,
            'min_price' => $_GET['min'] ?? null,
            'max_price' => $_GET['max'] ?? null,
            'size' => $_GET['size'] ?? null,
            'bestseller' => !empty($_GET['bestseller']),
            'sort' => $_GET['sort'] ?? 'featured',
        ];

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $totalCount = Product::countFiltered($filters);
        $products = Product::filter($filters, $limit, $offset);
        $totalPages = (int)ceil($totalCount / $limit);

        $title = $currentCategory 
            ? "{$currentCategory['name']} | Luxury Club" 
            : "Shop Luxury Fragrances | Luxury Club";
        
        $desc = $currentCategory['description'] ?? 'Explore our complete collection of luxury fragrances, alcohol-free attars and scented candles.';

        View::setMeta([
            'title' => $title,
            'description' => $desc,
        ]);

        View::render('pages/shop', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'filters' => $filters,
            'totalCount' => $totalCount,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
