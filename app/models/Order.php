<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Order
{
    public static function createOrder(array $data, array $items, array $totals, string $paymentMethod = 'razorpay'): array
    {
        $pdo = Database::getInstance();
        $pdo->beginTransaction();

        try {
            // Generate unique order number: LC-YYYY-XXXXXX
            $year = date('Y');
            $random = strtoupper(bin2hex(random_bytes(3)));
            $orderNo = "LC-{$year}-{$random}";

            $orderId = Database::insert('orders', [
                'order_no' => $orderNo,
                'customer_name' => $data['customer_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address_line1' => $data['address_line1'],
                'address_line2' => $data['address_line2'] ?? null,
                'city' => $data['city'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
                'subtotal' => (int)$totals['subtotal'],
                'shipping' => (int)$totals['shipping'],
                'total' => (int)$totals['total'],
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'pending',
                'razorpay_order_id' => $data['razorpay_order_id'] ?? null,
                'status' => 'new',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                Database::insert('order_items', [
                    'order_id' => $orderId,
                    'product_id' => $item['id'],
                    'name_snapshot' => $item['name'] . ' (' . $item['size_label'] . ')',
                    'price_snapshot' => (int)$item['price'],
                    'qty' => (int)$item['qty'],
                    'line_total' => (int)$item['line_total'],
                ]);

                // Reduce inventory stock
                Product::reduceStock((int)$item['id'], (int)$item['qty']);
            }

            $pdo->commit();

            return self::findById($orderId);
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function findById(int $id): ?array
    {
        $order = Database::fetch("SELECT * FROM orders WHERE id = :id", ['id' => $id]);
        if ($order) {
            $order['items'] = self::getItems($order['id']);
        }
        return $order;
    }

    public static function findByOrderNo(string $orderNo): ?array
    {
        $order = Database::fetch("SELECT * FROM orders WHERE order_no = :order_no", ['order_no' => $orderNo]);
        if ($order) {
            $order['items'] = self::getItems($order['id']);
        }
        return $order;
    }

    public static function getItems(int $orderId): array
    {
        return Database::fetchAll("
            SELECT oi.*, p.image, p.tint, p.slug 
            FROM order_items oi
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :order_id
        ", ['order_id' => $orderId]);
    }

    public static function updatePaymentStatus(int|string $idOrNo, string $paymentStatus, ?string $razorpayOrderId = null, ?string $razorpayPaymentId = null): void
    {
        $where = is_numeric($idOrNo) ? "id = :id" : "order_no = :order_no";
        $param = is_numeric($idOrNo) ? ['id' => (int)$idOrNo] : ['order_no' => $idOrNo];

        $data = ['payment_status' => $paymentStatus];
        if ($razorpayOrderId) $data['razorpay_order_id'] = $razorpayOrderId;
        if ($razorpayPaymentId) $data['razorpay_payment_id'] = $razorpayPaymentId;

        Database::update('orders', $data, $where, $param);
    }

    public static function updateStatus(int $id, string $status, ?string $trackingNumber = null): void
    {
        $data = ['status' => $status];
        if ($trackingNumber !== null) {
            $data['tracking_number'] = $trackingNumber;
        }
        Database::update('orders', $data, 'id = :id', ['id' => $id]);
    }

    public static function all(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT * FROM orders WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['payment_status'])) {
            $sql .= " AND payment_status = :pstatus";
            $params['pstatus'] = $filters['payment_status'];
        }
        if (!empty($filters['q'])) {
            $q = '%' . trim($filters['q']) . '%';
            $sql .= " AND (order_no LIKE :q1 OR customer_name LIKE :q2 OR phone LIKE :q3 OR email LIKE :q4)";
            $params['q1'] = $q;
            $params['q2'] = $q;
            $params['q3'] = $q;
            $params['q4'] = $q;
        }

        $sql .= " ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}";

        return Database::fetchAll($sql, $params);
    }

    public static function countAll(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) AS cnt FROM orders WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['payment_status'])) {
            $sql .= " AND payment_status = :pstatus";
            $params['pstatus'] = $filters['payment_status'];
        }
        if (!empty($filters['q'])) {
            $q = '%' . trim($filters['q']) . '%';
            $sql .= " AND (order_no LIKE :q1 OR customer_name LIKE :q2 OR phone LIKE :q3 OR email LIKE :q4)";
            $params['q1'] = $q;
            $params['q2'] = $q;
            $params['q3'] = $q;
            $params['q4'] = $q;
        }

        $row = Database::fetch($sql, $params);
        return (int)($row['cnt'] ?? 0);
    }

    public static function getStats(): array
    {
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01 00:00:00');

        $driver = Database::getInstance()->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $todayOrders = Database::fetch("SELECT COUNT(*) AS cnt FROM orders WHERE date(created_at) = date('now')")['cnt'] ?? 0;
            $monthRev = Database::fetch("SELECT SUM(total) AS sum_total FROM orders WHERE payment_status = 'paid' AND created_at >= date('now', 'start of month')")['sum_total'] ?? 0;
        } else {
            $todayOrders = Database::fetch("SELECT COUNT(*) AS cnt FROM orders WHERE DATE(created_at) = CURDATE()")['cnt'] ?? 0;
            $monthRev = Database::fetch("SELECT SUM(total) AS sum_total FROM orders WHERE payment_status = 'paid' AND created_at >= :mstart", ['mstart' => $monthStart])['sum_total'] ?? 0;
        }

        $totalOrders = Database::fetch("SELECT COUNT(*) AS cnt FROM orders")['cnt'] ?? 0;
        $unreadMessages = Database::fetch("SELECT COUNT(*) AS cnt FROM messages WHERE is_read = 0")['cnt'] ?? 0;
        $lowStock = Database::fetch("SELECT COUNT(*) AS cnt FROM products WHERE stock_qty <= 10 AND is_active = 1")['cnt'] ?? 0;

        return [
            'today_orders' => (int)$todayOrders,
            'month_revenue' => (int)$monthRev,
            'total_orders' => (int)$totalOrders,
            'unread_messages' => (int)$unreadMessages,
            'low_stock_products' => (int)$lowStock,
        ];
    }
}
