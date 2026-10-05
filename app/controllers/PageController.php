<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Category;
use App\Models\Product;

class PageController
{
    public function about(): void
    {
        View::setMeta([
            'title' => 'Our Story & Atelier Philosophy | Luxury Club',
            'description' => 'Discover the story behind Luxury Club: single-origin ingredients, 100% alcohol-free attars, and royal flacons crafted in India.'
        ]);
        View::render('pages/about');
    }

    public function faq(): void
    {
        View::setMeta([
            'title' => 'Frequently Asked Questions | Luxury Club',
            'description' => 'Answers to common questions regarding our alcohol-free attars, 100 ml Eau de Parfum, delivery timeline and returns.'
        ]);

        // Add FAQPage JSON-LD
        View::addJsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'What makes roll-on attars special?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Our attars are 100% alcohol-free pure perfume oils that last 12+ hours on pulse points.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Do you deliver across India?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes, we deliver across India with complimentary express shipping on orders above ₹999.'
                    ]
                ]
            ]
        ]);

        View::render('pages/faq');
    }

    public function policy(string $slug): void
    {
        $validPolicies = [
            'shipping' => 'Shipping & Delivery Policy',
            'returns' => 'Returns & Exchange Policy',
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms of Service',
        ];

        if (!array_key_exists($slug, $validPolicies)) {
            http_response_code(404);
            View::render('pages/404', ['path' => "/policies/$slug"]);
            return;
        }

        $title = $validPolicies[$slug];
        $contentBody = get_setting("policy_$slug", "TODO: Client to provide full text for $title.");

        View::setMeta([
            'title' => "$title | Luxury Club",
            'description' => "Official $title for Luxury Club customers and website users."
        ]);

        View::render('pages/policy', [
            'slug' => $slug,
            'title' => $title,
            'contentBody' => $contentBody,
        ]);
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        $categories = Category::allActive();
        $products = Product::allActive();

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

        echo $xml->asXML();
        exit;
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /api\n";
        echo "Disallow: /checkout\n";
        echo "Disallow: /order/success\n";
        echo "Sitemap: " . url('/sitemap.xml') . "\n";
        exit;
    }
}
