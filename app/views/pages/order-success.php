<?php
// app/views/pages/order-success.php
use App\Core\View;

/** @var array $order */
?>

<section class="section" style="padding-top: 60px; padding-bottom: 80px;">
  <div class="container" style="max-width: 800px;">
    
    <!-- Success Banner -->
    <div style="text-align: center; margin-bottom: 40px;">
      <div style="width: 72px; height: 72px; border-radius: 50%; background-color: var(--gold); color: var(--ink); display:flex; align-items:center; justify-content:center; margin: 0 auto 20px; font-size: 36px;">
        ✓
      </div>
      <span class="eyebrow">ORDER CONFIRMED</span>
      <h1 style="font-size: 40px; margin-bottom: 12px;">Thank you, <?= e($order['customer_name']) ?></h1>
      <p style="font-size: 16px; color: var(--muted);">
        Your artisanal fragrance order <strong>#<?= e($order['order_no']) ?></strong> has been received and is being prepared with extreme care at our atelier.
      </p>
    </div>

    <!-- Order Details Box -->
    <div style="background: #ffffff; border: 1px solid var(--line); border-radius: var(--radius-card); padding: 36px; box-shadow: var(--shadow-subtle); margin-bottom: 32px;">
      
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--line); padding-bottom: 20px; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
          <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted);">Order Reference</div>
          <div style="font-family: var(--font-serif); font-size: 20px; font-weight: 700; color: var(--ink);"><?= e($order['order_no']) ?></div>
        </div>
        <div>
          <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted);">Payment Status</div>
          <div class="status-badge <?= e($order['payment_status']) ?>"><?= ucfirst(e($order['payment_status'])) ?> (<?= strtoupper(e($order['payment_method'])) ?>)</div>
        </div>
        <button type="button" class="btn btn-sm btn-outline no-print" onclick="window.print()">
          Print Receipt
        </button>
      </div>

      <!-- Items List -->
      <h3 style="font-size: 18px; margin-bottom: 16px;">Fragrance Selections</h3>
      <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px;">
        <?php foreach ($order['items'] as $item): ?>
          <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--line); padding-bottom: 12px;">
            <div>
              <div style="font-weight: 600; color: var(--ink);"><?= e($item['name_snapshot']) ?></div>
              <div style="font-size: 12px; color: var(--muted);"><?= formatInr($item['price_snapshot']) ?> × <?= $item['qty'] ?></div>
            </div>
            <div style="font-weight: 700; color: var(--ink);">
              <?= formatInr($item['line_total']) ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Financials -->
      <div class="summary-row">
        <span>Subtotal</span>
        <span><?= formatInr($order['subtotal']) ?></span>
      </div>
      <div class="summary-row">
        <span>Express Shipping</span>
        <span><?= $order['shipping'] > 0 ? formatInr($order['shipping']) : 'Complimentary' ?></span>
      </div>
      <div class="summary-total-row">
        <span>Grand Total</span>
        <span style="color: var(--gold-text);"><?= formatInr($order['total']) ?></span>
      </div>

      <!-- Shipping Destination -->
      <div style="background: var(--sand); border-radius: 14px; padding: 20px; margin-top: 28px;">
        <h4 style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">Shipping Destination</h4>
        <p style="margin: 0; font-size: 14px; line-height: 1.5; color: var(--ink-2);">
          <strong><?= e($order['customer_name']) ?></strong><br>
          <?= e($order['address_line1']) ?><?= $order['address_line2'] ? ', ' . e($order['address_line2']) : '' ?><br>
          <?= e($order['city']) ?>, <?= e($order['state']) ?> - <?= e($order['pincode']) ?><br>
          Phone: <?= e($order['phone']) ?> · Email: <?= e($order['email']) ?>
        </p>
      </div>

    </div>

    <!-- Actions -->
    <div style="text-align: center;">
      <a href="<?= url('/shop') ?>" class="btn btn-dark" data-magnetic>
        Continue Exploring Fragrances <?= icon('arrow-right') ?>
      </a>
    </div>

  </div>
</section>
