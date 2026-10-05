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

<!-- NEW: The Private Vault / Haute Parfumerie Reserve -->
<section class="section section-dark private-reserve-section" aria-label="Private Reserve Collection">
  <div class="container">
    <div class="private-reserve-header">
      <div class="vault-badge">✦ PRIVATE ATELIER RESERVE ✦</div>
      <h2 style="color: var(--cream);">The Masterpiece Reserve</h2>
      <p style="max-width: 580px; margin: 12px auto 0; color: var(--muted-dark); font-size: 15px;">
        Extracted through micro-batch hydro-distillation. Two signature creations crafted for monumental occasions and supreme longevity.
      </p>
    </div>

    <div class="private-reserve-grid" data-reveal-grid>
      
      <!-- Vault Masterpiece 1: Royal Oud -->
      <div class="vault-card" style="background: radial-gradient(circle at 50% 30%, #1a1610 0%, #0d0b08 100%);">
        <div class="vault-card-badge">Private Reserve • 16h+ Longevity</div>
        <div class="vault-card-flacon" style="background-color: #ECE6DA;">
          <img src="<?= asset('/assets/img/products/royal-oud.png') ?>" alt="Royal Oud Extrait de Parfum" loading="lazy">
        </div>
        <div class="vault-card-body">
          <div class="vault-eyebrow">AGED CAMBODIAN AGARWOOD · 100 ML</div>
          <h3 class="vault-title">Royal Oud</h3>
          <p class="vault-desc">Deep smoky agarwood laced with saffron, warm amber resin, and velvety Taif rose. A commanding presence in solid black glass.</p>
          
          <div class="vault-accords">
            <span class="accord-pill">Smoky Oud</span>
            <span class="accord-pill">Warm Amber</span>
            <span class="accord-pill">Rare Saffron</span>
            <span class="accord-pill">Taif Rose</span>
          </div>

          <div class="vault-card-bottom">
            <div class="vault-price">₹1,699 <span class="tax-tag">Inclusive of all taxes</span></div>
            <div class="vault-actions">
              <button type="button" class="btn btn-primary" data-add-to-bag="3" data-name="Royal Oud" data-price="1699">
                Add to Bag
              </button>
              <a href="<?= url('/product/royal-oud') ?>" class="btn btn-outline-light">
                Explore Flacon
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Vault Masterpiece 2: Red Crystal -->
      <div class="vault-card" style="background: radial-gradient(circle at 50% 30%, #200d11 0%, #0d0b08 100%);">
        <div class="vault-card-badge">New Release • Sillage Master</div>
        <div class="vault-card-flacon" style="background-color: #F4E0E0;">
          <img src="<?= asset('/assets/img/products/red-crystal.png') ?>" alt="Red Crystal Eau de Parfum" loading="lazy">
        </div>
        <div class="vault-card-body">
          <div class="vault-eyebrow">CRIMSON DAMASK ROSE · 100 ML</div>
          <h3 class="vault-title">Red Crystal</h3>
          <p class="vault-desc">Radiant French Damask rose petals kissed with sparkling pomegranate nectar, spun sugar, and seductive white musk in crimson crystal.</p>
          
          <div class="vault-accords">
            <span class="accord-pill">French Rose</span>
            <span class="accord-pill">Pomegranate</span>
            <span class="accord-pill">Spun Sugar</span>
            <span class="accord-pill">White Musk</span>
          </div>

          <div class="vault-card-bottom">
            <div class="vault-price">₹1,499 <span class="tax-tag">Inclusive of all taxes</span></div>
            <div class="vault-actions">
              <button type="button" class="btn btn-primary" data-add-to-bag="2" data-name="Red Crystal" data-price="1499">
                Add to Bag
              </button>
              <a href="<?= url('/product/red-crystal') ?>" class="btn btn-outline-light">
                Explore Flacon
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- 7.4 "The Whole Collection, In Motion" Infinite Marquee -->
<section class="marquee-band" aria-label="Product Showcase Marquee">
  <div class="marquee-band-track">
    <?php for ($i = 0; $i < 2; $i++): ?>
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

<!-- NEW: Interactive Scent Finder Quiz Section -->
<section class="section" style="background-color: var(--ivory);" id="scent-finder">
  <div class="container">
    <div class="scent-quiz-box" data-reveal>
      
      <div class="scent-quiz-header">
        <span class="eyebrow">OLFACTORY CONCIERGE</span>
        <h2>Discover Your Signature Scent</h2>
        <p>Answer 3 quick questions to unlock the fragrance formulation tailored to your aura.</p>
      </div>

      <!-- Quiz Step Container -->
      <div class="quiz-container" id="quiz-app">
        
        <!-- Step 1 -->
        <div class="quiz-step active" data-step="1">
          <div class="quiz-step-label">QUESTION 1 OF 3 • YOUR PREFERRED VIBE</div>
          <h3 class="quiz-question">How do you want to feel when you wear your scent?</h3>
          <div class="quiz-options-grid">
            <button type="button" class="quiz-opt-btn" data-choice="royal">
              <div class="opt-icon">👑</div>
              <div class="opt-title">Regal & Unforgettable</div>
              <div class="opt-desc">Deep, smoky woods, amber resin and heavy presence</div>
            </button>
            <button type="button" class="quiz-opt-btn" data-choice="fresh">
              <div class="opt-icon">🌊</div>
              <div class="opt-title">Fresh & Invigorating</div>
              <div class="opt-desc">Aquatic breezes, crisp bergamot and radiant florals</div>
            </button>
            <button type="button" class="quiz-opt-btn" data-choice="romantic">
              <div class="opt-icon">🌹</div>
              <div class="opt-title">Romantic & Seductive</div>
              <div class="opt-desc">Damask rose, spun sugar and warm vanilla musk</div>
            </button>
            <button type="button" class="quiz-opt-btn" data-choice="calm">
              <div class="opt-icon">🕯️</div>
              <div class="opt-title">Serene & Comforting</div>
              <div class="opt-desc">Blooming jasmine, soft sandalwood and clean air</div>
            </button>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="quiz-step" data-step="2" style="display: none;">
          <div class="quiz-step-label">QUESTION 2 OF 3 • SCENT INTENSITY</div>
          <h3 class="quiz-question">What format best suits your daily ritual?</h3>
          <div class="quiz-options-grid">
            <button type="button" class="quiz-opt-btn" data-choice="edp">
              <div class="opt-icon">✨</div>
              <div class="opt-title">Eau de Parfum (100ml)</div>
              <div class="opt-desc">Full flacon spray with radiant sillage and crystal cap</div>
            </button>
            <button type="button" class="quiz-opt-btn" data-choice="attar">
              <div class="opt-icon">💎</div>
              <div class="opt-title">Roll-on Attar (10ml)</div>
              <div class="opt-desc">100% alcohol-free pure oil for intimate, 14h+ lasting</div>
            </button>
            <button type="button" class="quiz-opt-btn" data-choice="lifestyle">
              <div class="opt-icon">🌿</div>
              <div class="opt-title">Ambiance & Travel</div>
              <div class="opt-desc">Soy candles for the room or car hanging diffuser pods</div>
            </button>
          </div>
        </div>

        <!-- Step 3: Result Match -->
        <div class="quiz-step quiz-result-step" data-step="3" style="display: none;">
          <div class="quiz-result-badge">YOUR PERFECT MATCH</div>
          <div class="quiz-result-card" id="quiz-result-content">
            <!-- Dynamically populated via JS -->
          </div>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- 7.6 Spotlights (Candles & Car Pods) -->
<section class="section" aria-label="Lifestyle Spotlights" style="background-color: var(--sand);">
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

<!-- NEW: Bulk, Corporate & Wedding Gifting Concierge Section -->
<section class="section bulk-gifting-section" aria-labelledby="gifting-heading" id="bulk-orders">
  <div class="container">
    <div class="bulk-gifting-grid">
      
      <!-- Left Info Column -->
      <div class="bulk-info" data-reveal>
        <span class="eyebrow" style="color: var(--gold-deep);">BESPOKE ATELIER SERVICES</span>
        <h2 id="gifting-heading" style="margin-bottom: 20px;">Corporate Gifting & Grand Wedding Favours</h2>
        <p style="font-size: 16px; line-height: 1.7; color: var(--muted); margin-bottom: 28px;">
          Make an everlasting impression. Whether curating executive hampers for board members, festive corporate celebrations, or bespoke 10ml attar flacons for luxury wedding guests, our concierge delivers bespoke excellence.
        </p>

        <div class="gifting-perks-list">
          <div class="gifting-perk-item">
            <div class="gifting-perk-icon">🎁</div>
            <div>
              <strong>Custom Velvet & Gold Crest Packaging</strong>
              <p>Personalized ribbons, custom crest tags, and calligraphed gift cards.</p>
            </div>
          </div>

          <div class="gifting-perk-item">
            <div class="gifting-perk-icon">💎</div>
            <div>
              <strong>Tiered Volume Privileges</strong>
              <p>Exclusive atelier rates on bulk orders starting from 25+ units.</p>
            </div>
          </div>

          <div class="gifting-perk-item">
            <div class="gifting-perk-icon">⚡</div>
            <div>
              <strong>Dedicated Concierge & Pan-India Dispatch</strong>
              <p>Sample boxes dispatched in 48 hours with insured multi-destination delivery.</p>
            </div>
          </div>
        </div>

        <div class="bulk-actions" style="margin-top: 32px; display: flex; gap: 16px; flex-wrap: wrap;">
          <a href="https://wa.me/919876543210?text=Hello%20Luxury%20Club%20Concierge,%20I%20would%20like%20to%20inquire%20about%20Bulk%20and%20Corporate%20Gifting." target="_blank" rel="noopener" class="btn btn-primary" data-magnetic>
            <?= icon('whatsapp') ?> Chat with Gifting Concierge
          </a>
          <a href="<?= url('/contact') ?>" class="btn btn-outline" data-magnetic>
            Send Formal RFP
          </a>
        </div>
      </div>

      <!-- Right Gifting Visual Card -->
      <div class="bulk-visual" data-reveal>
        <div class="bulk-card-box">
          <div class="bulk-card-badge">Bespoke Curation</div>
          <div class="bulk-flacons-preview">
            <div class="bf-item bf-1" style="background-color: #ECE6DA;">
              <img src="<?= asset('/assets/img/products/royal-oud.png') ?>" alt="Royal Oud Gift Flacon">
            </div>
            <div class="bf-item bf-2" style="background-color: #F7EED6;">
              <img src="<?= asset('/assets/img/products/morning-jasmine.png') ?>" alt="Morning Jasmine Candle">
            </div>
            <div class="bf-item bf-3" style="background-color: #F6E1E3;">
              <img src="<?= asset('/assets/img/products/ruby-red.png') ?>" alt="Ruby Red Attar">
            </div>
          </div>
          
          <div class="bulk-card-footer">
            <h4>The Royal Wedding & Executive Hamper</h4>
            <p>100ml Extrait Flacon + 100g Scented Candle + 10ml Pure Attar in velvet lined presentation box.</p>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; font-size: 13px;">
              <span style="color: var(--gold-text); font-weight: 700;">Custom Branding Available</span>
              <span style="color: var(--muted);">Min. 25 Sets</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- NEW: Connoisseur Testimonials & Press Accolades -->
<section class="section section-sand" aria-label="Customer Reviews">
  <div class="container">
    <div class="section-header-center">
      <span class="eyebrow">VOICES OF DISTINCTION</span>
      <h2>What Connoisseurs Say</h2>
      <p class="section-desc">Over 10,000 discerning patrons wearing the magic across the country.</p>
    </div>

    <div class="testimonials-home-grid" data-reveal-grid>
      
      <div class="home-testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Blue Orchid is an olfactory triumph. The crystal cap and weight of the bottle feel as premium as top Parisian houses, but the alcohol-free formulation is vastly superior for Indian weather."</p>
        <div class="testi-user">
          <div class="testi-avatar">VK</div>
          <div>
            <strong>Vikramaditya K.</strong> <span class="verified-pill">✓ Verified</span>
            <div class="testi-product">Blue Orchid EDP (100ml)</div>
          </div>
        </div>
      </div>

      <div class="home-testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Ordered 150 customized roll-on attars for our sister's destination wedding in Udaipur. The guests were raving about the scent longevity and gold crest bottles. Unbeatable service!"</p>
        <div class="testi-user">
          <div class="testi-avatar">PG</div>
          <div>
            <strong>Pooja & Gaurav</strong> <span class="verified-pill">✓ Wedding Client</span>
            <div class="testi-product">Custom Royal Oudh Attar Sets</div>
          </div>
        </div>
      </div>

      <div class="home-testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Red Crystal has become my signature scent. It projection is remarkable without being overwhelming. The complimentary sample vials that came with the package were a lovely touch."</p>
        <div class="testi-user">
          <div class="testi-avatar">NR</div>
          <div>
            <strong>Nandini R.</strong> <span class="verified-pill">✓ Verified</span>
            <div class="testi-product">Red Crystal Eau de Parfum</div>
          </div>
        </div>
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

