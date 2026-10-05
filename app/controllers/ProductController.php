<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Category;
use App\Models\Product;

class ProductController
{
    public function show(string $slug): void
    {
        $product = Product::findBySlug($slug);
        if (!$product) {
            http_response_code(404);
            View::setMeta([
                'title' => 'Fragrance Not Found | Luxury Club',
                'description' => 'The luxury fragrance you requested could not be found.'
            ]);
            View::render('pages/404', ['path' => "/product/$slug"]);
            return;
        }

        $category = Category::findById((int)$product['category_id']);
        $siblings = Product::getCategorySiblings((int)$product['category_id'], (int)$product['id']);
        $relatedProducts = Product::getRelated((int)$product['id'], (int)$product['category_id'], 4);

        $metaTitle = $product['meta_title'] ?: "{$product['name']} ({$product['size_label']}) | Luxury Club";
        $metaDesc = $product['meta_description'] ?: $product['short_description'];

        View::setMeta([
            'title' => $metaTitle,
            'description' => $metaDesc,
            'og_image' => url('/assets/img/products/' . $product['image']),
            'og_type' => 'product',
        ]);

        // Add Product JSON-LD Schema
        View::addJsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product['name'],
            'image' => [url('/assets/img/products/' . $product['image'])],
            'description' => $product['short_description'] ?: $product['description'],
            'brand' => [
                '@type' => 'Brand',
                'name' => 'Luxury Club'
            ],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'INR',
                'price' => (string)$product['price'],
                'availability' => (int)$product['stock_qty'] > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => url('/product/' . $product['slug'])
            ]
        ]);

        // Add BreadcrumbList JSON-LD Schema
        View::addJsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => url('/')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $category['name'] ?? 'Shop',
                    'item' => url('/shop/' . ($category['slug'] ?? ''))
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $product['name'],
                    'item' => url('/product/' . $product['slug'])
                ]
            ]
        ]);

        View::render('pages/product', [
            'product' => $product,
            'category' => $category,
            'siblings' => $siblings,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * API for recently viewed products query: /api/products?ids=1,2,3
     */
    public function getByIdsApi(): void
    {
        $rawIds = $_GET['ids'] ?? '';
        $ids = array_filter(array_map('intval', explode(',', $rawIds)));
        $products = Product::getByIds($ids);
        json_response(['success' => true, 'products' => $products]);
    }
}
