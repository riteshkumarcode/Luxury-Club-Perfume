<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Category;
use App\Models\Product;
use App\Models\HeroSlide;

class HomeController
{
    public function index(): void
    {
        $heroSlides = HeroSlide::allActive();
        $categories = Category::allActive();
        $featuredProducts = Product::findFeatured(12);
        $allProducts = Product::allActive();

        View::setMeta([
            'title' => 'Luxury Club — The Magic of Luxury Fragrances',
            'description' => 'Experience the magic of Luxury Club: 100 ml Eau de Parfum in crystal flacons, 100% alcohol-free roll-on attars, scented candles and luxury car hanging pods.'
        ]);

        View::render('pages/home', [
            'heroSlides' => $heroSlides,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'allProducts' => $allProducts,
        ]);
    }
}
