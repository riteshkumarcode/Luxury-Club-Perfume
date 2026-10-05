<?php
// app/views/admin/orders/show.php
/** @var array $order */
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <a href="<?= url('/admin/orders') ?>" style="font-size: 13px; color: var(--muted); text-decoration: underline;" class="no-print">
      &larr; Back to Orders
    </a>
    <h3 style="font-size: 26px; margin-top: 4px;">Order #<?= e($order['order_no']) ?></h3>
  </div>
  <button type="button" class="btn btn-outline btn-sm no-print" onclick="window.print()">
    <?= icon('download') ?> Print Tax Invoice
  </button>
</div>

<div class="admin-form-grid">
  
  <!-- Left: Items & Breakdown -->
  <div style="display: flex; flex-direction: column; gap: 24px;">
    
    <div class="admin-card">
      <h4 style="font-size: 16px; margin-bottom: 16px;">Ordered Fragrance Items</h4>

      <table class="admin-table">
        <thead>
          <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Line Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order['items'] as $item): ?>
            <tr>
              <td>
                <div style="font-weight: 600; color: var(--ink);"><?= e($item['name_snapshot']) ?></div>
              </td>
              <td><?= formatInr($item['price_snapshot']) ?></td>
              <td><?= $item['qty'] ?></td>
              <td><strong><?= formatInr($item['line_total']) ?></strong></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align: right; padding-top: 16px;">Subtotal:</td>
            <td style="padding-top: 16px;"><strong><?= formatInr($order['subtotal']) ?></strong></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right;">Shipping:</td>
            <td><?= $order['shipping'] > 0 ? formatInr($order['shipping']) : 'Complimentary' ?></td>
          </tr>
          <tr style="font-size: 16px;">
            <td colspan="3" style="text-align: right; border-top: 2px solid var(--ink); font-weight: bold;">Total Amount:</td>
            <td style="border-top: 2px solid var(--ink); font-weight: bold; color: var(--gold-text);"><?= formatInr($order['total']) ?></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Customer & Shipping Destination -->
    <div class="admin-card">
      <h4 style="font-size: 16px; margin-bottom: 14px;">Customer & Delivery Address</h4>
      <p style="font-size: 14px; line-height: 1.6; color: var(--ink);">
        <strong>Customer:</strong> <?= e($order['customer_name']) ?><br>
        <strong>Email:</strong> <a href="mailto:<?= e($order['email']) ?>" style="color: var(--gold-text);"><?= e($order['email']) ?></a><br>
        <strong>Phone:</strong> <a href="tel:<?= e($order['phone']) ?>"><?= e($order['phone']) ?></a><br>
        <br>
        <strong>Address:</strong><br>
        <?= e($order['address_line1']) ?><?= $order['address_line2'] ? ', ' . e($order['address_line2']) : '' ?><br>
        <?= e($order['city']) ?>, <?= e($order['state']) ?> - <strong><?= e($order['pincode']) ?></strong>
      </p>

      <?php if (!empty($order['notes'])): ?>
        <div style="margin-top: 16px; padding: 12px; background: var(--sand); border-radius: 10px; font-size: 13px;">
          <strong>Client Note:</strong> <?= e($order['notes']) ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Right: Status Management -->
  <div style="display: flex; flex-direction: column; gap: 24px;" class="no-print">
    
    <div class="admin-card">
      <h4 style="font-size: 16px; margin-bottom: 16px;">Fulfillment & Status</h4>

      <form action="<?= url('/admin/orders/' . $order['id'] . '/status') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
          <label class="form-label">Order Status</label>
          <select name="status" class="form-select">
            <option value="new" <?= $order['status'] === 'new' ? 'selected' : '' ?>>New</option>
            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing in Atelier</option>
            <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped / In Transit</option>
            <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Courier Tracking Number (AWB)</label>
          <input type="text" name="tracking_number" class="form-input" value="<?= e($order['tracking_number'] ?? '') ?>" placeholder="e.g. DELHIVERY_12345678">
        </div>

        <div class="form-group">
          <label class="form-label">Payment Status</label>
          <select name="payment_status" class="form-select">
            <option value="pending" <?= $order['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="paid" <?= $order['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
            <option value="failed" <?= $order['payment_status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
            <option value="refunded" <?= $order['payment_status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
          </select>
        </div>

        <div style="background: var(--sand); padding: 12px; border-radius: 10px; font-size: 12px; margin-bottom: 20px;">
          <div><strong>Payment Method:</strong> <?= strtoupper(e($order['payment_method'])) ?></div>
          <?php if (!empty($order['razorpay_payment_id'])): ?>
            <div><strong>Razorpay Payment ID:</strong> <code><?= e($order['razorpay_payment_id']) ?></code></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
          Update Order State
        </button>
      </form>
    </div>

  </div>

</div>
