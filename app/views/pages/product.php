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

        <!-- Scent Performance & Character Gauges -->
        <div class="pdp-performance-box">
          <div class="pdp-perf-title">Olfactory Performance & Projection</div>
          <div class="perf-gauge-row">
            <div class="perf-gauge-item">
              <div class="perf-gauge-header">
                <span>Longevity</span>
                <strong>14+ Hours (All Day)</strong>
              </div>
              <div class="perf-bar-track">
                <div class="perf-bar-fill" style="width: 95%;"></div>
              </div>
            </div>
            <div class="perf-gauge-item">
              <div class="perf-gauge-header">
                <span>Sillage / Trail</span>
                <strong>Enveloping & Radiant</strong>
              </div>
              <div class="perf-bar-track">
                <div class="perf-bar-fill" style="width: 88%;"></div>
              </div>
            </div>
            <div class="perf-gauge-item">
              <div class="perf-gauge-header">
                <span>Oil Concentration</span>
                <strong>30% Extrait De Parfum</strong>
              </div>
              <div class="perf-bar-track">
                <div class="perf-bar-fill" style="width: 92%;"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Complimentary Atelier Perks -->
        <div class="atelier-perks-box">
          <div class="atelier-perk-item">
            <span class="atelier-perk-icon">🎁</span>
            <div>
              <strong>2 Complimentary 2ml Discovery Vials</strong>
              <p>Test the samples first. If unopened, full bottle can be exchanged risk-free.</p>
            </div>
          </div>
          <div class="atelier-perk-item">
            <span class="atelier-perk-icon">✨</span>
            <div>
              <strong>Signature Velvet Keepsake Case</strong>
              <p>Housed in our magnetic 24K gold foil embossed collectors coffret.</p>
            </div>
          </div>
          <div class="atelier-perk-item">
            <span class="atelier-perk-icon">✈️</span>
            <div>
              <strong>Insured Express Pan-India Courier</strong>
              <p>Dispatched within 24 hours in tamper-evident discreet luxury packing.</p>
            </div>
          </div>
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

    <!-- Flacon Anatomy & Craftsmanship Details Section -->
    <div class="flacon-craft-section">
      <div class="section-head text-center">
        <span class="eyebrow">HAUTE ARTISANSHIP</span>
        <h2>The Architecture of the Flacon</h2>
        <p class="section-subtitle">Crafted with the utmost reverence for French glassmaking traditions.</p>
      </div>

      <div class="flacon-features-grid">
        <div class="flacon-feat-card">
          <div class="flacon-feat-icon">💎</div>
          <h3>Weighted Optical Crystal</h3>
          <p>Heavy bottomed 450g high-clarity flacon engineered to preserve raw botanical essence from UV degradation.</p>
        </div>
        <div class="flacon-feat-card">
          <div class="flacon-feat-icon">👑</div>
          <h3>24K Gold Stamped Crest</h3>
          <p>Individually hand-polished metal alloy cap with magnetic click sound tuned for tactile satisfaction.</p>
        </div>
        <div class="flacon-feat-card">
          <div class="flacon-feat-icon">🌫️</div>
          <h3>Micro-Mist Dispersion</h3>
          <p>Precision French atomizer distributing an ultra-fine 0.07ml aerosol cloud for uniform sillage without skin irritation.</p>
        </div>
        <div class="flacon-feat-card">
          <div class="flacon-feat-icon">🌿</div>
          <h3>Macerated 90 Days</h3>
          <p>Aged in temperature-regulated dark casks allowing raw resins and floral absolutes to mature into seamless perfection.</p>
        </div>
      </div>
    </div>

    <!-- Scent Layering Duo Section -->
    <div class="layering-duo-section">
      <div class="layering-duo-card">
        <div class="layering-text">
          <span class="eyebrow" style="color: var(--gold-light);">THE MASTER'S SECRET</span>
          <h2 style="color: #fff; margin-bottom: 12px;">Scent Layering Ritual</h2>
          <p style="color: rgba(255,255,255,0.75); font-size: 15px; line-height: 1.6; margin-bottom: 20px;">
            Elevate <strong><?= e($product['name']) ?></strong> into a bespoke couture aura. Spray this base formula on pulse points, followed by a light mist of our woody amber accents to create a personalized signature trail that lasts through the night.
          </p>
          <div class="layering-perk-badge">
            ✨ Layering Duo Offer: Bundle with any 2nd Flacon & Get ₹1,000 Off automatically at checkout.
          </div>
          <div style="margin-top: 24px;">
            <a href="<?= url('/shop') ?>" class="btn btn-gold">Explore Harmonious Pairings <?= icon('arrow-right') ?></a>
          </div>
        </div>
        <div class="layering-visual">
          <div class="layering-bottle-stack">
            <div class="bottle-glow" style="background-color: <?= e($product['tint']) ?>;">
              <img src="<?= asset('/assets/img/products/' . $product['image']) ?>" alt="<?= e($product['name']) ?>">
            </div>
            <div class="layering-plus">+</div>
            <div class="bottle-glow" style="background-color: rgba(212, 163, 115, 0.2);">
              <img src="<?= asset('/assets/img/products/product-1.png') ?>" alt="Layering Pairing">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Verified Connoisseur Reviews Breakdown -->
    <div class="pdp-reviews-section">
      <div class="reviews-summary-header">
        <div>
          <span class="eyebrow">VERIFIED REVIEWS</span>
          <h2>Connoisseur Impressions</h2>
          <div class="rating-aggregate-box">
            <div class="rating-big-score">4.9</div>
            <div>
              <div class="rating-stars" style="color: var(--gold); font-size: 20px;">★★★★★</div>
              <div style="font-size: 13px; color: var(--muted);">Based on 148 verified luxury purchases</div>
            </div>
          </div>
        </div>
        <div>
          <a href="#write-review" class="btn btn-outline" onclick="alert('Thank you for your interest! Review submissions are verified against your order number in your post-delivery email.'); return false;">
            Write a Review
          </a>
        </div>
      </div>

      <div class="reviews-grid">
        <div class="review-item-card">
          <div class="review-card-head">
            <div class="reviewer-avatar">RK</div>
            <div>
              <div class="reviewer-name">Rohan Kapoor <span class="verified-tag">✓ Verified Connoisseur</span></div>
              <div class="review-date">Mumbai · Purchased <?= e($product['name']) ?></div>
            </div>
            <div class="review-rating">★★★★★</div>
          </div>
          <p class="review-body">
            "The sillage on this is extraordinary. I sprayed it at 8 AM before a board meeting, and colleagues were still complimenting the drydown at 9 PM dinner. The presentation box alone feels like a ₹25,000 niche masterpiece."
          </p>
        </div>

        <div class="review-item-card">
          <div class="review-card-head">
            <div class="reviewer-avatar">AS</div>
            <div>
              <div class="reviewer-name">Ananya Sengupta <span class="verified-tag">✓ Verified Connoisseur</span></div>
              <div class="review-date">Delhi NCR · Purchased <?= e($product['name']) ?></div>
            </div>
            <div class="review-rating">★★★★★</div>
          </div>
          <p class="review-body">
            "Received the package in under 24 hours. The velvet case and included sample vials made unboxing feel like a true ritual. Rich, intoxicating, and distinctly royalty-grade."
          </p>
        </div>

        <div class="review-item-card">
          <div class="review-card-head">
            <div class="reviewer-avatar">VM</div>
            <div>
              <div class="reviewer-name">Vikramaditya M. <span class="verified-tag">✓ Verified Connoisseur</span></div>
              <div class="review-date">Bengaluru · Purchased <?= e($product['name']) ?></div>
            </div>
            <div class="review-rating">★★★★★</div>
          </div>
          <p class="review-body">
            "Better than most European luxury houses costing 4 times as much. The oil concentration is palpable—leaves a sheen on skin and projects with majestic subtlety rather than synthetic sharpness."
          </p>
        </div>
      </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
      <div style="margin-top: 80px;">
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
