<?php
// app/views/pages/product.php
use App\Core\View;
use App\Models\Cart;

/** @var array $product */
/** @var array $category */
/** @var array $siblings */
/** @var array $relatedProducts */

$isInWishlist = Cart::isInWishlist((int)$product['id']);
$isOutOfStock = ((int)$product['stock_qty']) <= 0;
$tags = array_filter(array_map('trim', explode(',', $product['tags'] ?? '')));
$currentUrl = url('/product/' . $product['slug']);
$shareText = rawurlencode("Discover {$product['name']} at Luxury Club: " . $currentUrl);
?>

<section class="section" style="padding-top: 36px;">
  <div class="container">
    
    <!-- Breadcrumb -->
    <?= View::partial('partials/breadcrumb', ['items' => [
      ['label' => 'Home', 'url' => '/'],
      ['label' => $category['name'], 'url' => '/shop/' . $category['slug']],
      ['label' => $product['name']]
    ]]) ?>

    <div class="pdp-grid">
      
      <!-- Left Column: Sticky Media & Swatches -->
      <div class="pdp-media-sticky">
        
        <!-- Main Product Media Panel on Tint with Multiply Blend -->
        <div class="pdp-main-image-wrap" style="background-color: <?= e($product['tint']) ?>;">
          <?php if (!empty($product['badge'])): ?>
            <div class="product-badge" style="top: 20px; left: 20px;"><?= e($product['badge']) ?></div>
          <?php endif; ?>

          <img src="<?= asset('/assets/img/products/' . $product['image']) ?>" 
               alt="<?= e($product['name']) ?>" 
               class="pdp-main-img" 
               fetchpriority="high"
               width="540" 
               height="540">

          <div class="pdp-zoom-hint">Hover to zoom 1.9×</div>
        </div>

        <!-- Sibling Products Swatches in Same Category -->
        <?php if (!empty($siblings) && count($siblings) > 1): ?>
          <div style="margin-top: 20px;">
            <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">
              More in <?= e($category['name']) ?>:
            </div>
            <div class="pdp-swatches">
              <?php foreach ($siblings as $sib): ?>
                <a href="<?= url('/product/' . $sib['slug']) ?>" 
                   class="pdp-swatch-item <?= $sib['id'] == $product['id'] ? 'active' : '' ?>" 
                   style="background-color: <?= e($sib['tint']) ?>;" 
                   title="<?= e($sib['name']) ?>">
                  <img src="<?= asset('/assets/img/products/' . $sib['image']) ?>" alt="<?= e($sib['name']) ?>" loading="lazy">
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>

      <!-- Right Column: Details, CTAs, Fragrance Pyramid, Accordions -->
      <div class="pdp-info">
        
        <span class="eyebrow"><?= e($category['name']) ?> · LUXURY CLUB</span>
        <h1 class="pdp-title"><?= e($product['name']) ?></h1>

        <div class="pdp-price-row">
          <span class="pdp-price-large"><?= formatInr($product['price']) ?></span>
          <?php if (!empty($product['compare_at_price'])): ?>
            <span class="product-card-compare" style="font-size: 18px;"><?= formatInr($product['compare_at_price']) ?></span>
          <?php endif; ?>
        </div>
        <div class="pdp-tax-note">
          <?= e($product['size_label']) ?> · Inclusive of all taxes & complimentary gift wrapping
        </div>

        <!-- Short Description -->
        <p style="font-size: 16px; margin-bottom: 20px; line-height: 1.65;">
          <?= e($product['short_description'] ?: $product['description']) ?>
        </p>

        <!-- Tag Chips -->
        <?php if (!empty($tags)): ?>
          <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px;">
            <?php foreach ($tags as $tag): ?>
              <span class="feature-chip" style="background: var(--sand); border-color: var(--line); color: var(--ink); font-size: 11px; padding: 4px 12px;">
                #<?= e($tag) ?>
              </span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- Fragrance Pyramid (Top, Heart, Base notes) -->
        <?php if (!empty($product['notes_top']) || !empty($product['notes_heart']) || !empty($product['notes_base'])): ?>
          <div class="fragrance-pyramid">
            <div class="pyramid-card">
              <div class="pyramid-level">Top Notes</div>
              <div class="pyramid-notes"><?= e($product['notes_top'] ?? '–') ?></div>
            </div>
            <div class="pyramid-card">
              <div class="pyramid-level">Heart Notes</div>
              <div class="pyramid-notes"><?= e($product['notes_heart'] ?? '–') ?></div>
            </div>
            <div class="pyramid-card">
              <div class="pyramid-level">Base Notes</div>
              <div class="pyramid-notes"><?= e($product['notes_base'] ?? '–') ?></div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Stock Status & Primary CTAs -->
        <?php if ($isOutOfStock): ?>
          <div style="background: rgba(163, 38, 28, 0.08); border: 1px solid var(--error); border-radius: 14px; padding: 20px; margin: 20px 0;">
            <div style="color: var(--error); font-weight: 700; font-size: 14px; text-transform: uppercase;">Currently Out of Stock</div>
            <p style="font-size: 13px; margin: 8px 0 14px;">Leave your email to be notified when our master perfumers craft the next batch.</p>
            <form action="<?= url('/api/newsletter') ?>" method="POST" style="display: flex; gap: 8px;">
              <?= csrf_field() ?>
              <input type="email" name="email" class="form-input" placeholder="Your email address" required style="padding: 10px 16px; font-size: 13px;">
              <button type="submit" class="btn btn-dark btn-sm">Notify Me</button>
            </form>
          </div>
        <?php else: ?>
          <!-- Quantity Stepper & Add to Bag -->
          <div class="pdp-actions-row">
            <div class="pdp-qty-stepper">
              <button type="button" class="pdp-qty-minus" aria-label="Decrease quantity">−</button>
              <span class="pdp-qty-val" style="font-weight: 700; min-width: 20px; text-align: center;">1</span>
              <button type="button" class="pdp-qty-plus" aria-label="Increase quantity">+</button>
            </div>

            <button type="button" 
                    class="btn btn-dark pdp-btn-add" 
                    data-id="<?= $product['id'] ?>" 
                    data-name="<?= e($product['name']) ?>" 
                    data-price="<?= $product['price'] ?>"
                    data-stock="<?= $product['stock_qty'] ?>">
              ADD TO BAG · <?= formatInr($product['price']) ?>
            </button>

            <button type="button" class="btn-wishlist <?= $isInWishlist ? 'active' : '' ?>" data-wishlist-toggle="<?= $product['id'] ?>" aria-label="Add to wishlist" style="position: static; width: 52px; height: 52px; border: 1px solid var(--line-strong);">
              <?= icon('heart') ?>
            </button>
          </div>

          <!-- Buy Now Full-Width Button -->
          <button type="button" class="btn btn-primary btn-block pdp-btn-buynow" style="margin-bottom: 28px;">
            Buy It Now <?= icon('arrow-right') ?>
          </button>
        <?php endif; ?>

        <!-- Pincode Delivery Checker -->
        <div class="pincode-box">
          <div style="font-size: 12px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink);">
            Check Delivery & Availability
          </div>
          <form class="pincode-form">
            <input type="text" class="pincode-input" placeholder="Enter 6-digit PIN code" maxlength="6" pattern="^[1-9]\d{5}$" required>
            <button type="submit" class="btn btn-sm btn-dark">Check</button>
          </form>
          <div class="pincode-result"></div>
        </div>

        <!-- Accordions -->
        <div class="pdp-accordions">
          
          <div class="accordion-item active">
            <button type="button" class="accordion-header">
              <span>Description & Character</span>
              <div class="accordion-icon"><?= icon('chevron-down') ?></div>
            </button>
            <div class="accordion-body">
              <p><?= nl2br(e($product['description'] ?: $product['short_description'])) ?></p>
            </div>
          </div>

          <?php if (!empty($product['how_to_use'])): ?>
            <div class="accordion-item">
              <button type="button" class="accordion-header">
                <span>Ritual & How to Use</span>
                <div class="accordion-icon"><?= icon('chevron-down') ?></div>
              </button>
              <div class="accordion-body">
                <p><?= e($product['how_to_use']) ?></p>
              </div>
            </div>
          <?php endif; ?>

          <div class="accordion-item">
            <button type="button" class="accordion-header">
              <span>Complimentary Shipping & Returns</span>
              <div class="accordion-icon"><?= icon('chevron-down') ?></div>
            </button>
            <div class="accordion-body">
              <p>Dispatched within 24 hours from our atelier. Complimentary express shipping across India on orders over ₹999. In the rare event of transit imperfection or damage, contact our concierge within 7 days for priority replacement.</p>
            </div>
          </div>

        </div>

        <!-- Share Actions -->
        <div style="display: flex; gap: 16px; margin-top: 32px; align-items: center; font-size: 13px; color: var(--muted);">
          <span>Share:</span>
          <a href="https://wa.me/?text=<?= $shareText ?>" target="_blank" rel="noopener" style="display: flex; align-items: center; gap: 6px; color: #25D366; font-weight: 600;">
            <?= icon('whatsapp') ?> WhatsApp
          </a>
          <button type="button" data-copy-link style="display: flex; align-items: center; gap: 6px; color: var(--ink); font-weight: 600; cursor: pointer;">
            <?= icon('sparkle') ?> Copy Link
          </button>
        </div>

      </div>

    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
      <div style="margin-top: 96px;">
        <div style="text-align: center; margin-bottom: 40px;">
          <span class="eyebrow">CURATED COMPANIONS</span>
          <h2>You may also like</h2>
        </div>
        <div class="shop-grid" data-reveal-grid>
          <?php foreach ($relatedProducts as $rel): ?>
            <?= View::partial('partials/product-card', ['product' => $rel]) ?>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Recently Viewed (Populated via JS) -->
    <div class="recently-viewed-section" style="margin-top: 80px; display: none;">
      <div style="text-align: center; margin-bottom: 40px;">
        <span class="eyebrow">RECENTLY EXPLORED</span>
        <h2>Your Viewed Fragrances</h2>
      </div>
      <div class="shop-grid recently-viewed-grid"></div>
    </div>

  </div>
</section>

<!-- Sticky Mobile Add-to-Cart Bar -->
<div class="sticky-mobile-bar">
  <div>
    <div style="font-family: var(--font-serif); font-size: 16px; font-weight: 600;"><?= e($product['name']) ?></div>
    <div style="font-size: 14px; font-weight: 700; color: var(--gold-text);"><?= formatInr($product['price']) ?></div>
  </div>
  <button type="button" class="btn btn-dark btn-sm" data-add-to-bag="<?= $product['id'] ?>" data-name="<?= e($product['name']) ?>" data-price="<?= $product['price'] ?>">
    Add to Bag
  </button>
</div>
