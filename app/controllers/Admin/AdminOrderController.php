<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Models\Order;

class AdminOrderController
{
    public function index(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? null,
            'payment_status' => $_GET['pstatus'] ?? null,
            'q' => $_GET['q'] ?? null,
        ];

        $orders = Order::all($filters, 50, 0);

        View::render('admin/orders/index', [
            'orders' => $orders,
            'filters' => $filters,
            'pageTitle' => 'Client Orders & Shipments',
        ], 'layouts/admin');
    }

    public function show(string $id): void
    {
        $order = Order::findById((int)$id);
        if (!$order) {
            flash('error', 'Order not found.');
            redirect('/admin/orders');
        }

        View::render('admin/orders/show', [
            'order' => $order,
            'pageTitle' => "Order #{$order['order_no']}",
        ], 'layouts/admin');
    }

    public function updateStatus(string $id): void
    {
        $id = (int)$id;
        $status = $_POST['status'] ?? 'new';
        $trackingNumber = trim($_POST['tracking_number'] ?? '');
        $paymentStatus = $_POST['payment_status'] ?? null;

        Order::updateStatus($id, $status, $trackingNumber ?: null);

        if ($paymentStatus) {
            Order::updatePaymentStatus($id, $paymentStatus);
        }

        flash('success', "Order #$id status updated successfully.");
        redirect('/admin/orders/' . $id);
    }
}
