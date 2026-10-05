<?php
// app/views/pages/home.php
use App\Core\View;

/** @var array $heroSlides */
/** @var array $categories */
/** @var array $featuredProducts */
/** @var array $allProducts */
?>

<!-- 7.1 Hero Slider Section -->
<section class="hero-slider-section" aria-label="Hero Showcase">
  
  <!-- Giant Background Outline Word (Animate on slide change) -->
  <div class="hero-outline-word" aria-hidden="true">
    <?= e($heroSlides[0]['outline_word'] ?? 'Luxury') ?>
  </div>

  <div class="swiper hero-swiper">
    <div class="swiper-wrapper">
      <?php foreach ($heroSlides as $index => $slide): ?>
        <div class="swiper-slide hero-slide" 
             data-bg-color="<?= e($slide['bg_color']) ?>" 
             data-outline="<?= e($slide['outline_word']) ?>">
          
          <div class="container">
            <div class="hero-slide-grid">
              
              <!-- Left Content Column -->
              <div class="hero-text-content">
                <span class="eyebrow eyebrow-light"><?= e($slide['eyebrow']) ?></span>
                <h1 class="hero-title"><?= $slide['title'] ?></h1>
                <p class="hero-subline"><?= e($slide['subline']) ?></p>
                
                <div class="hero-ctas">
                  <a href="<?= url($slide['cta_url']) ?>" class="btn btn-primary" data-magnetic>
                    <?= e($slide['cta_label']) ?> <?= icon('arrow-right') ?>
                  </a>
                  <?php if (!empty($slide['cta2_label'])): ?>
                    <a href="<?= url($slide['cta2_url'] ?? '/shop') ?>" class="btn btn-outline-light" data-magnetic>
                      <?= e($slide['cta2_label']) ?>
                    </a>
                  <?php endif; ?>
                </div>

                <?php if (!empty($slide['product_price'])): ?>
                  <div class="hero-meta">
                    <?= e($slide['product_size'] ?? '100 ML') ?> · <?= formatInr($slide['product_price']) ?>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Right Media (Arch Panel & Concentric Rings) -->
              <div class="hero-media-wrap">
                <div class="hero-concentric-ring-dashed" aria-hidden="true"></div>
                <div class="hero-concentric-ring-solid" aria-hidden="true"></div>

                <div class="hero-arch-panel" style="background-color: <?= e($slide['tint']) ?>;">
                  <img src="<?= asset('/assets/img/products/' . $slide['image']) ?>" 
                       alt="<?= e(strip_tags($slide['title'])) ?>" 
                       class="hero-product-img"
                       <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                </div>

                <!-- Floating Info Card -->
                <?php if (!empty($slide['product_name'])): ?>
                  <div class="hero-floating-card">
                    <div class="fc-cat"><?= e($slide['eyebrow']) ?></div>
                    <div class="fc-name"><?= e($slide['product_name']) ?></div>
                    <div class="fc-price"><?= formatInr($slide['product_price']) ?></div>
                  </div>
                <?php endif; ?>

              </div>

            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Hero Bottom Controls & Progress Tabs -->
  <div class="hero-controls">
    <div class="container hero-controls-inner">
      
      <!-- Slide Tabs with Filling Progress Bars -->
      <div class="hero-tabs" role="tablist">
        <?php foreach ($heroSlides as $idx => $slide): ?>
          <button type="button" class="hero-tab-btn <?= $idx === 0 ? 'active' : '' ?>" role="tab" aria-selected="<?= $idx === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $idx + 1 ?>: <?= e(strip_tags($slide['title'])) ?>">
            <span>0<?= $idx + 1 ?> · <?= e(strtoupper(str_replace('<br>', ' ', $slide['title']))) ?></span>
            <div class="hero-tab-bar">
              <div class="hero-tab-fill"></div>
            </div>
          </button>
        <?php endforeach; ?>
      </div>

      <!-- Slide Counter & Arrows -->
      <div style="display: flex; align-items: center; gap: 20px;">
        <div style="font-family: var(--font-serif); font-size: 14px; letter-spacing: 0.12em; color: var(--cream);">
          <span class="hero-counter-current">01</span> / 0<?= count($heroSlides) ?>
        </div>
        <div style="display: flex; gap: 8px;">
          <button type="button" class="btn-wishlist hero-btn-prev" aria-label="Previous Slide" style="background: rgba(255,255,255,0.1); color: var(--cream); border: 1px solid rgba(255,255,255,0.2);">
            <?= icon('chevron-left') ?>
          </button>
          <button type="button" class="btn-wishlist hero-btn-next" aria-label="Next Slide" style="background: rgba(255,255,255,0.1); color: var(--cream); border: 1px solid rgba(255,255,255,0.2);">
            <?= icon('chevron-right') ?>
          </button>
        </div>
      </div>

    </div>
  </div>

</section>

<!-- 7.2 Shop by Category Section -->
<section class="section" aria-labelledby="cat-heading">
  <div class="container">
    
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 48px; flex-wrap: wrap; gap: 20px;">
      <div>
        <span class="eyebrow">SHOP BY CATEGORY</span>
        <h2 id="cat-heading">Four ways to wear the magic</h2>
      </div>
      <a href="<?= url('/shop') ?>" class="btn btn-outline">
        View All Fragrances <?= icon('arrow-right') ?>
      </a>
    </div>

    <div class="category-grid" data-reveal-grid>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= url('/shop/' . $cat['slug']) ?>" class="category-card">
          <div class="category-card-media" style="background-color: #F3EDE2;">
            <img src="<?= asset('/assets/img/products/' . ($cat['cover_image'] ?: 'blue-orchid.png')) ?>" alt="<?= e($cat['name']) ?>" loading="lazy">
          </div>
          <div class="category-card-footer">
            <div>
              <div class="category-card-name"><?= e($cat['name']) ?></div>
              <div class="category-card-count"><?= (int)($cat['product_count'] ?? 0) ?> Products</div>
            </div>
            <div class="category-arrow-btn">
              <?= icon('arrow-right') ?>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- 7.3 Bestsellers & Signatures (The Collection) -->
<section class="section section-sand" aria-labelledby="collection-heading">
  <div class="container">
    
    <div style="text-align: center; max-width: 640px; margin: 0 auto 36px;">
      <span class="eyebrow">THE COLLECTION</span>
      <h2 id="collection-heading">Bestsellers & Signatures</h2>
      <p style="margin-top: 12px;">Each formulation is balanced with precision, harmonizing natural floral absolutes, aged woods and sparkling citruses.</p>
    </div>

    <!-- Filter Chips -->
    <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 48px; flex-wrap: wrap;">
      <button type="button" class="chip-btn category-chip-btn active" data-filter-category="all">All Fragrances</button>
      <button type="button" class="chip-btn category-chip-btn" data-filter-category="eau-de-parfum">Eau de Parfum</button>
      <button type="button" class="chip-btn category-chip-btn" data-filter-category="attars">Roll-on Attars</button>
      <button type="button" class="chip-btn category-chip-btn" data-filter-category="car-pods">Car Hanging Pods</button>
      <button type="button" class="chip-btn category-chip-btn" data-filter-category="candles">Scented Candles</button>
    </div>

    <!-- Product Grid -->
    <div class="shop-grid" data-reveal-grid>
      <?php foreach ($featuredProducts as $product): ?>
        <?= View::partial('partials/product-card', ['product' => $product]) ?>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 56px;">
      <a href="<?= url('/shop') ?>" class="btn btn-dark" data-magnetic>
        Explore Entire Boutique <?= icon('arrow-right') ?>
      </a>
    </div>

  </div>
</section>

<!-- 7.4 "The Whole Collection, In Motion" Infinite Marquee -->
<section class="marquee-band" aria-label="Product Showcase Marquee">
  <div class="marquee-band-track">
    <?php for ($i = 0; $i < 2; $i++): // Duplicate for continuous flow ?>
      <?php foreach ($allProducts as $p): ?>
        <a href="<?= url('/product/' . $p['slug']) ?>" class="marquee-product-tile">
          <div class="marquee-tile-media" style="background-color: <?= e($p['tint']) ?>;">
            <img src="<?= asset('/assets/img/products/' . $p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
          </div>
          <div class="marquee-tile-name"><?= e($p['name']) ?></div>
          <div class="marquee-tile-price"><?= formatInr($p['price']) ?></div>
        </a>
      <?php endforeach; ?>
    <?php endfor; ?>
  </div>
</section>

<!-- 7.5 Attar Feature Band (Dark Luxury) -->
<section class="section section-dark" aria-labelledby="attar-heading">
  <div class="container">
    <div class="attar-feature-grid">
      
      <!-- Left 2x2 Collage -->
      <div class="attar-collage" data-reveal>
        <div class="attar-collage-tile" style="background-color: #E8E6E2;">
          <img src="<?= asset('/assets/img/products/sparkle-oud.png') ?>" alt="A Sparkle Oud Attar" loading="lazy">
        </div>
        <div class="attar-collage-tile" style="background-color: #DCEFF2;">
          <img src="<?= asset('/assets/img/products/aqua-delight.png') ?>" alt="Aqua Delight Attar" loading="lazy">
        </div>
        <div class="attar-collage-tile" style="background-color: #F6E1E3;">
          <img src="<?= asset('/assets/img/products/ruby-red.png') ?>" alt="Ruby Red Attar" loading="lazy">
        </div>
        <div class="attar-collage-tile" style="background-color: #E2E7F6;">
          <img src="<?= asset('/assets/img/products/magnetic-edge.png') ?>" alt="Magnetic Edge Attar" loading="lazy">
        </div>
      </div>

      <!-- Right Copy Column -->
      <div data-reveal>
        <span class="eyebrow eyebrow-light">ROLL-ON ATTARS · 10 ML</span>
        <h2 id="attar-heading" style="margin-bottom: 20px;">Pure perfume oil. Free from alcohol.</h2>
        <p style="font-size: 17px; margin-bottom: 24px;">
          Crafted with pure concentrated botanicals and rare agarwood oils. Designed to roll effortlessly onto pulse points, releasing a deeply intimate scent trail that stays vibrant for up to 14 hours.
        </p>

        <div class="chips-row">
          <div class="feature-chip">✦ 100% Alcohol-Free</div>
          <div class="feature-chip">✦ Smooth Roll-on Applicator</div>
          <div class="feature-chip">✦ Pocket-Sized 10 ml Flacon</div>
          <div class="feature-chip">✦ Faceted Gold Cap</div>
        </div>

        <a href="<?= url('/shop/attars') ?>" class="btn btn-primary" data-magnetic>
          Shop All Attars <?= icon('arrow-right') ?>
        </a>
      </div>

    </div>
  </div>
</section>

<!-- 7.6 Spotlights (Candles & Car Pods) -->
<section class="section" aria-label="Lifestyle Spotlights">
  <div class="container">
    <div class="spotlights-grid" data-reveal-grid>
      
      <!-- Candle Spotlight -->
      <div class="spotlight-panel" style="background-color: #F7EED6;">
        <div style="z-index: 2; max-width: 320px;">
          <span class="eyebrow" style="color: var(--gold-text);">SCENTED CANDLES · 100 G</span>
          <h3 style="font-size: 32px; margin-bottom: 12px;">Light up the room</h3>
          <p style="color: var(--muted); margin-bottom: 24px;">Hand-poured natural soy wax in luxury jars topped with gold lids. 25+ hours of comforting elegance.</p>
          <a href="<?= url('/product/morning-jasmine') ?>" class="btn btn-dark btn-sm">
            Discover Morning Jasmine
          </a>
        </div>
        <img src="<?= asset('/assets/img/products/morning-jasmine.png') ?>" alt="Morning Jasmine Candle" class="spotlight-img" loading="lazy">
      </div>

      <!-- Car Pod Spotlight -->
      <div class="spotlight-panel" style="background-color: #F7E4E4;">
        <div style="z-index: 2; max-width: 320px;">
          <span class="eyebrow" style="color: var(--gold-text);">CAR HANGING PODS</span>
          <h3 style="font-size: 32px; margin-bottom: 12px;">Luxury, on the road</h3>
          <p style="color: var(--muted); margin-bottom: 24px;">Porous beechwood cap with braided gold suspension cord. Continuous artisanal scent diffusion for your vehicle.</p>
          <a href="<?= url('/product/hanging-pod-red') ?>" class="btn btn-dark btn-sm">
            Discover Hanging Pods
          </a>
        </div>
        <img src="<?= asset('/assets/img/products/pod-red.png') ?>" alt="Hanging Pod Red" class="spotlight-img" loading="lazy">
      </div>

    </div>
  </div>
</section>

<!-- 7.7 Promise Strip -->
<div class="promise-strip" aria-label="Our Commitments">
  <div class="container">
    <div class="promise-grid">
      
      <div class="promise-cell">
        <div class="promise-icon"><?= icon('sparkle') ?></div>
        <div class="promise-title">Alcohol-Free Attars</div>
        <div class="promise-text">100% pure concentrated perfume oils that gently nourish the skin.</div>
      </div>

      <div class="promise-cell">
        <div class="promise-icon"><?= icon('gift') ?></div>
        <div class="promise-title">Gift-Ready Finish</div>
        <div class="promise-text">Complimentary signature packaging and gold foil embossed boxes.</div>
      </div>

      <div class="promise-cell">
        <div class="promise-icon"><?= icon('shield') ?></div>
        <div class="promise-title">Secure Checkout</div>
        <div class="promise-text">Encrypted UPI, Cards, Netbanking & Cash on Delivery available.</div>
      </div>

      <div class="promise-cell">
        <div class="promise-icon"><?= icon('truck') ?></div>
        <div class="promise-title">Express India Delivery</div>
        <div class="promise-text">Dispatched in 24 hours. Complimentary express on orders over ₹999.</div>
      </div>

    </div>
  </div>
</div>
