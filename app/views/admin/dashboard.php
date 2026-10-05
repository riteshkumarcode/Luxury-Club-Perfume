<?php
// app/views/admin/dashboard.php
/** @var array $stats */
/** @var array $recentOrders */
?>

<!-- Stat Cards Grid -->
<div class="admin-stats-grid">
  
  <div class="stat-card">
    <div class="stat-card-title">Today's Orders</div>
    <div class="stat-card-value"><?= $stats['today_orders'] ?></div>
  </div>

  <div class="stat-card">
    <div class="stat-card-title">Revenue (This Month)</div>
    <div class="stat-card-value"><?= formatInr($stats['month_revenue']) ?></div>
  </div>

  <div class="stat-card">
    <div class="stat-card-title">Unread Inquiries</div>
    <div class="stat-card-value" style="<?= $stats['unread_messages'] > 0 ? 'color: var(--gold-text);' : '' ?>">
      <?= $stats['unread_messages'] ?>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-card-title">Low Stock Fragrances (&le; 10)</div>
    <div class="stat-card-value" style="<?= $stats['low_stock_products'] > 0 ? 'color: var(--error);' : '' ?>">
      <?= $stats['low_stock_products'] ?>
    </div>
  </div>

</div>

<!-- Recent Orders Table -->
<div class="admin-card">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h3 style="font-size: 20px;">Recent Client Orders</h3>
    <a href="<?= url('/admin/orders') ?>" style="font-size: 13px; color: var(--gold-text); font-weight: 700;">
      View All Orders &rarr;
    </a>
  </div>

  <?php if (empty($recentOrders)): ?>
    <p style="text-align: center; padding: 40px; color: var(--muted);">No orders recorded yet.</p>
  <?php else: ?>
    <div style="overflow-x: auto;">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Order No</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Fulfillment</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentOrders as $order): ?>
            <tr>
              <td>
                <strong><a href="<?= url('/admin/orders/' . $order['id']) ?>"><?= e($order['order_no']) ?></a></strong>
              </td>
              <td><?= e($order['customer_name']) ?></td>
              <td><?= e($order['phone']) ?></td>
              <td><strong><?= formatInr($order['total']) ?></strong></td>
              <td>
                <span class="status-badge <?= e($order['payment_status']) ?>">
                  <?= ucfirst(e($order['payment_status'])) ?>
                </span>
              </td>
              <td>
                <span class="status-badge <?= e($order['status']) ?>">
                  <?= ucfirst(e($order['status'])) ?>
                </span>
              </td>
              <td style="font-size: 12px; color: var(--muted);"><?= date('d M, Y H:i', strtotime($order['created_at'])) ?></td>
              <td>
                <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-outline" style="padding: 4px 12px; font-size: 11px;">
                  Inspect
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
