<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Models\Order;
use App\Models\Product;

class AdminDashboardController
{
    public function index(): void
    {
        $stats = Order::getStats();
        $recentOrders = Order::all([], 10, 0);

        View::render('admin/dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'pageTitle' => 'Atelier Overview',
        ], 'layouts/admin');
    }
}
