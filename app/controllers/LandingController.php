<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Product;
use App\Models\Category;

class LandingController
{
    public function experience(): void
    {
        $featuredProducts = Product::findFeatured(6);
        $allProducts = Product::allActive();
        $categories = Category::allActive();

        View::setMeta([
            'title' => 'The Royal Collection Experience | Luxury Club Perfumes',
            'description' => 'Discover the magic of 100% alcohol-free pure perfume oils and crystal-cut Eau de Parfum. Handcrafted in small batches with 12+ hour longevity.',
            'og_image' => '/assets/img/products/royal-oud.png'
        ]);

        View::render('pages/landing-special', [
            'featuredProducts' => $featuredProducts,
            'allProducts' => $allProducts,
            'categories' => $categories
        ]);
    }
}
