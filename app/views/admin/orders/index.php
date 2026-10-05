<?php
// app/views/admin/orders/index.php
/** @var array $orders */
/** @var array $filters */
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
  <h3 style="font-size: 20px;">Orders (<?= count($orders) ?>)</h3>

  <!-- Status Filter Pills -->
  <div style="display: flex; gap: 8px; flex-wrap: wrap;">
    <a href="<?= url('/admin/orders') ?>" class="chip-btn <?= empty($filters['status']) ? 'active' : '' ?>">All</a>
    <a href="<?= url('/admin/orders?status=new') ?>" class="chip-btn <?= ($filters['status'] ?? '') === 'new' ? 'active' : '' ?>">New</a>
    <a href="<?= url('/admin/orders?status=processing') ?>" class="chip-btn <?= ($filters['status'] ?? '') === 'processing' ? 'active' : '' ?>">Processing</a>
    <a href="<?= url('/admin/orders?status=shipped') ?>" class="chip-btn <?= ($filters['status'] ?? '') === 'shipped' ? 'active' : '' ?>">Shipped</a>
    <a href="<?= url('/admin/orders?status=delivered') ?>" class="chip-btn <?= ($filters['status'] ?? '') === 'delivered' ? 'active' : '' ?>">Delivered</a>
    <a href="<?= url('/admin/orders?status=cancelled') ?>" class="chip-btn <?= ($filters['status'] ?? '') === 'cancelled' ? 'active' : '' ?>">Cancelled</a>
  </div>
</div>

<div class="admin-card">
  <?php if (empty($orders)): ?>
    <p style="text-align: center; padding: 40px; color: var(--muted);">No matching orders found.</p>
  <?php else: ?>
    <div style="overflow-x: auto;">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Order Reference</th>
            <th>Customer</th>
            <th>City / State</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Fulfillment</th>
            <th>Placed Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td>
                <strong><a href="<?= url('/admin/orders/' . $order['id']) ?>"><?= e($order['order_no']) ?></a></strong>
              </td>
              <td>
                <div><?= e($order['customer_name']) ?></div>
                <div style="font-size: 11px; color: var(--muted);"><?= e($order['phone']) ?></div>
              </td>
              <td><?= e($order['city']) ?>, <?= e($order['state']) ?></td>
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
              <td style="font-size: 12px; color: var(--muted);"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
              <td>
                <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 11px;">
                  Manage
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
