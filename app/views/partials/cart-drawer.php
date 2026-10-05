<?php
// app/views/partials/cart-drawer.php
use App\Models\Cart;

$cartItems = Cart::getItemsWithDetails();
$totals = Cart::getTotals();
$wishlistItems = Cart::getWishlistItemsWithDetails();
?>
<!-- Drawer Backdrop -->
<div class="drawer-backdrop" aria-hidden="true"></div>

<!-- Slide-in Cart Drawer -->
<div class="cart-drawer" role="dialog" aria-modal="true" aria-label="Shopping Bag and Wishlist" data-lenis-prevent>
  
  <!-- Drawer Header with Tabs -->
  <div class="drawer-header">
    <div class="drawer-tabs">
      <button type="button" class="drawer-tab-btn active" data-tab="bag">
        Bag (<span class="cart-badge-count"><?= $totals['count'] ?></span>)
      </button>
      <button type="button" class="drawer-tab-btn" data-tab="wishlist">
        Wishlist (<span class="wishlist-badge-count"><?= count($wishlistItems) ?></span>)
      </button>
    </div>
    <button type="button" class="drawer-close" aria-label="Close drawer">
      <?= icon('close') ?>
    </button>
  </div>

  <!-- Free Shipping Progress Bar (Bag Tab) -->
  <div class="shipping-bar-wrap">
    <div class="shipping-bar-text">
      <?php if ($totals['free_shipping_unlocked']): ?>
        ✦ You've unlocked <strong>Complimentary Express Shipping</strong>!
      <?php else: ?>
        Add <strong>₹<?= number_format($totals['amount_needed']) ?></strong> more for free shipping
      <?php endif; ?>
    </div>
    <div class="shipping-progress-track">
      <div class="shipping-progress-fill" style="width: <?= $totals['progress_percent'] ?>%;"></div>
    </div>
  </div>

  <!-- Bag Content Tab -->
  <div class="drawer-bag-content" style="display: flex; flex-direction: column; flex: 1; min-height: 0;">
    <div class="drawer-body">
      
      <!-- Items Container -->
      <div class="drawer-items-list">
        <?php foreach ($cartItems as $item): ?>
          <div class="cart-item-row" data-id="<?= $item['id'] ?>">
            <div class="cart-item-thumb" style="background-color: <?= e($item['tint']) ?>;">
              <img src="<?= asset('/assets/img/products/' . $item['image']) ?>" alt="<?= e($item['name']) ?>">
            </div>
            <div class="cart-item-info">
              <h4><?= e($item['name']) ?></h4>
              <div class="cart-cat-size"><?= e($item['category_name']) ?> · <?= e($item['size_label']) ?></div>
              <div class="cart-qty-stepper">
                <button type="button" class="cart-qty-btn" onclick="window.LuxuryCart.updateQty(<?= $item['id'] ?>, <?= $item['qty'] - 1 ?>)" aria-label="Decrease quantity">−</button>
                <span class="cart-qty-num"><?= $item['qty'] ?></span>
                <button type="button" class="cart-qty-btn" onclick="window.LuxuryCart.updateQty(<?= $item['id'] ?>, <?= $item['qty'] + 1 ?>)" aria-label="Increase quantity">+</button>
              </div>
            </div>
            <div class="cart-item-actions">
              <div class="cart-item-price"><?= formatInr($item['line_total']) ?></div>
              <button type="button" class="cart-item-remove" onclick="window.LuxuryCart.removeItem(<?= $item['id'] ?>)">Remove</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Empty State -->
      <div class="drawer-empty-state" style="<?= empty($cartItems) ? '' : 'display:none;' ?>; text-align: center; padding: 60px 20px;">
        <div style="font-size: 40px; color: var(--gold); margin-bottom: 16px;"><?= icon('bag') ?></div>
        <h3 style="font-size: 20px; margin-bottom: 8px;">Your Bag is Empty</h3>
        <p style="font-size: 14px; margin-bottom: 24px;">Explore our signature flacons and pure attars to begin your fragrance journey.</p>
        <a href="<?= url('/shop') ?>" class="btn btn-dark" onclick="window.LuxuryCart.close()">Discover Fragrances</a>
      </div>

    </div>

    <!-- Drawer Footer -->
    <div class="drawer-footer" style="<?= empty($cartItems) ? 'display:none;' : '' ?>">
      <div class="drawer-subtotal-row">
        <span>Subtotal</span>
        <span class="drawer-subtotal-val"><?= formatInr($totals['subtotal']) ?></span>
      </div>
      <div class="drawer-total-row">
        <span>Total</span>
        <span class="drawer-total-val"><?= formatInr($totals['total']) ?></span>
      </div>
      <a href="<?= url('/checkout') ?>" class="btn btn-primary btn-block">
        Proceed to Checkout <?= icon('arrow-right') ?>
      </a>
      <div style="text-align: center; margin-top: 12px;">
        <a href="<?= url('/cart') ?>" style="font-size: 12px; color: var(--muted); text-decoration: underline;" onclick="window.LuxuryCart.close()">View Full Bag</a>
      </div>
    </div>
  </div>

  <!-- Wishlist Content Tab -->
  <div class="drawer-wishlist-content" style="display: none; flex-direction: column; flex: 1; min-height: 0;">
    <div class="drawer-body">
      <?php if (empty($wishlistItems)): ?>
        <div style="text-align: center; padding: 60px 20px;">
          <div style="font-size: 40px; color: var(--gold); margin-bottom: 16px;"><?= icon('heart') ?></div>
          <h3 style="font-size: 20px; margin-bottom: 8px;">No Saved Fragrances</h3>
          <p style="font-size: 14px; margin-bottom: 24px;">Click the heart icon on any flacon to keep track of scents you adore.</p>
          <a href="<?= url('/shop') ?>" class="btn btn-dark" onclick="window.LuxuryCart.close()">Browse Collection</a>
        </div>
      <?php else: ?>
        <div class="drawer-wishlist-list">
          <?php foreach ($wishlistItems as $item): ?>
            <div class="cart-item-row" data-id="<?= $item['id'] ?>">
              <div class="cart-item-thumb" style="background-color: <?= e($item['tint']) ?>;">
                <img src="<?= asset('/assets/img/products/' . $item['image']) ?>" alt="<?= e($item['name']) ?>">
              </div>
              <div class="cart-item-info">
                <h4><?= e($item['name']) ?></h4>
                <div class="cart-cat-size"><?= e($item['size_label']) ?></div>
                <div class="cart-item-price" style="margin-top: 4px;"><?= formatInr($item['price']) ?></div>
              </div>
              <div class="cart-item-actions">
                <button type="button" class="btn btn-sm btn-dark" data-add-to-bag="<?= $item['id'] ?>" data-name="<?= e($item['name']) ?>" data-price="<?= $item['price'] ?>" style="font-size: 11px; padding: 6px 14px;">
                  Move to Bag
                </button>
                <button type="button" class="cart-item-remove" data-wishlist-toggle="<?= $item['id'] ?>">Remove</button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

</div>
