<?php
// app/views/pages/cart.php
use App\Core\View;

/** @var array $items */
/** @var array $totals */
?>

<section class="section" style="padding-top: 48px;">
  <div class="container">
    
    <?= View::partial('partials/breadcrumb', ['items' => [
      ['label' => 'Home', 'url' => '/'],
      ['label' => 'Shopping Bag']
    ]]) ?>

    <h1 style="font-size: 38px; margin-bottom: 32px;">Your Shopping Bag (<?= $totals['count'] ?>)</h1>

    <?php if (empty($items)): ?>
      <div style="text-align: center; padding: 80px 20px; background: var(--sand); border-radius: var(--radius-card);">
        <div style="font-size: 48px; color: var(--gold); margin-bottom: 16px;"><?= icon('bag') ?></div>
        <h2 style="font-size: 26px; margin-bottom: 12px;">Your Bag is Currently Empty</h2>
        <p style="max-width: 480px; margin: 0 auto 28px;">Discover our artisanal collection of Eau de Parfum, alcohol-free roll-on attars and luxury scented candles.</p>
        <a href="<?= url('/shop') ?>" class="btn btn-dark" data-magnetic>
          Explore Boutique <?= icon('arrow-right') ?>
        </a>
      </div>
    <?php else: ?>
      
      <div class="checkout-grid">
        
        <!-- Cart Items Table -->
        <div>
          <!-- Free Shipping Notification Bar -->
          <div style="background: var(--sand); border: 1px solid var(--line); border-radius: 16px; padding: 18px 24px; margin-bottom: 24px;">
            <div style="font-size: 13px; font-weight: 600; margin-bottom: 8px;">
              <?php if ($totals['free_shipping_unlocked']): ?>
                ✦ You've unlocked <strong>Complimentary Express Shipping</strong>!
              <?php else: ?>
                Add <strong>₹<?= number_format($totals['amount_needed']) ?></strong> more for free express shipping.
              <?php endif; ?>
            </div>
            <div class="shipping-progress-track">
              <div class="shipping-progress-fill" style="width: <?= $totals['progress_percent'] ?>%;"></div>
            </div>
          </div>

          <div style="border: 1px solid var(--line); border-radius: var(--radius-card); background: #ffffff; padding: 24px;">
            <?php foreach ($items as $item): ?>
              <div class="cart-item-row" data-id="<?= $item['id'] ?>">
                <div class="cart-item-thumb" style="background-color: <?= e($item['tint']) ?>;">
                  <img src="<?= asset('/assets/img/products/' . $item['image']) ?>" alt="<?= e($item['name']) ?>">
                </div>
                <div class="cart-item-info">
                  <h3 style="font-size: 18px; margin-bottom: 2px;"><a href="<?= url('/product/' . $item['slug']) ?>"><?= e($item['name']) ?></a></h3>
                  <div class="cart-cat-size"><?= e($item['category_name']) ?> · <?= e($item['size_label']) ?></div>
                  <div class="cart-qty-stepper" style="margin-top: 8px;">
                    <button type="button" class="cart-qty-btn" onclick="window.LuxuryCart.updateQty(<?= $item['id'] ?>, <?= $item['qty'] - 1 ?>)">−</button>
                    <span class="cart-qty-num"><?= $item['qty'] ?></span>
                    <button type="button" class="cart-qty-btn" onclick="window.LuxuryCart.updateQty(<?= $item['id'] ?>, <?= $item['qty'] + 1 ?>)">+</button>
                  </div>
                </div>
                <div class="cart-item-actions">
                  <div class="cart-item-price" style="font-size: 18px;"><?= formatInr($item['line_total']) ?></div>
                  <button type="button" class="cart-item-remove" onclick="window.LuxuryCart.removeItem(<?= $item['id'] ?>)">Remove</button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

        </div>

        <!-- Order Summary Card -->
        <div class="order-summary-card">
          <h3 style="font-size: 22px; margin-bottom: 24px;">Order Summary</h3>

          <div class="summary-row">
            <span>Subtotal</span>
            <span><?= formatInr($totals['subtotal']) ?></span>
          </div>

          <div class="summary-row">
            <span>Express Shipping</span>
            <span><?= $totals['shipping'] > 0 ? formatInr($totals['shipping']) : '<strong style="color:var(--success);">Complimentary</strong>' ?></span>
          </div>

          <!-- Coupon Code Input (TODO: client coupon code system) -->
          <div style="margin: 20px 0; padding: 16px 0; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line);">
            <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">Promotional Privilege Code</div>
            <div style="display: flex; gap: 8px;">
              <input type="text" class="form-input" placeholder="e.g. LUXURYCLUB" style="padding: 10px 14px; font-size: 13px; text-transform: uppercase;">
              <button type="button" class="btn btn-sm btn-dark" onclick="alert('TODO: Promotional coupons will be enabled in next release.')">Apply</button>
            </div>
          </div>

          <div class="summary-total-row">
            <span>Grand Total</span>
            <span style="color: var(--gold-text);"><?= formatInr($totals['total']) ?></span>
          </div>
          <div style="font-size: 11px; color: var(--muted); margin-bottom: 24px;">Inclusive of all taxes & insurance</div>

          <a href="<?= url('/checkout') ?>" class="btn btn-primary btn-block" data-magnetic>
            Proceed to Checkout <?= icon('arrow-right') ?>
          </a>

          <div style="text-align: center; margin-top: 16px;">
            <a href="<?= url('/shop') ?>" style="font-size: 13px; color: var(--muted); text-decoration: underline;">
              Continue Shopping
            </a>
          </div>
        </div>

      </div>

    <?php endif; ?>

  </div>
</section>
