<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;

class SearchController
{
    public function search(): void
    {
        $query = trim($_GET['q'] ?? '');
        if (empty($query)) {
            json_response(['success' => true, 'results' => []]);
        }

        $results = Product::search($query, 8);
        json_response([
            'success' => true,
            'query' => $query,
            'count' => count($results),
            'results' => $results,
        ]);
    }
}
