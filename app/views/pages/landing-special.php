<?php
// app/views/pages/landing-special.php
use App\Core\View;
/** @var array $featuredProducts */
/** @var array $allProducts */
/** @var array $categories */
?>

<!-- Limited Batch Urgency Ribbon -->
<div class="ad-urgency-banner">
  <div class="container ad-urgency-inner">
    <span class="ad-pulse-dot"></span>
    <span><strong>LIMITED ATELIER BATCH NO. 04:</strong> Only 140 Gift Sets Remaining — Get 2 Complimentary 2ml Discovery Vials + Free Shipping</span>
  </div>
</div>

<!-- Ad Hero Section -->
<section class="ad-hero-section">
  <div class="container">
    <div class="ad-hero-grid">
      
      <!-- Left Copy Column -->
      <div class="ad-hero-copy">
        <div class="ad-rating-badge">
          <div class="stars">★★★★★</div>
          <span>4.9 / 5 Rating from 2,400+ Perfume Lovers</span>
        </div>

        <h1 class="ad-hero-title">The Pure Magic of <em>Royal Fragrances</em></h1>
        
        <p class="ad-hero-subline">
          Experience long-lasting Eau de Parfum and 100% alcohol-free roll-on attars crafted with pure essential oils, crystal-cut flacons, and rich woody-floral accords that linger for 12+ hours.
        </p>

        <!-- Value Bullets -->
        <div class="ad-hero-bullets">
          <div class="bullet-item">
            <?= icon('check') ?> <span><strong>100% Alcohol-Free</strong> Concentrated Oils</span>
          </div>
          <div class="bullet-item">
            <?= icon('check') ?> <span><strong>12+ Hours</strong> Regal Longevity</span>
          </div>
          <div class="bullet-item">
            <?= icon('check') ?> <span><strong>Complimentary</strong> Express Shipping across India</span>
          </div>
          <div class="bullet-item">
            <?= icon('check') ?> <span><strong>Cash on Delivery</strong> Available</span>
          </div>
        </div>

        <!-- Action CTAs -->
        <div class="ad-hero-actions">
          <a href="#special-bundles" class="btn btn-primary" data-magnetic>
            Shop Exclusive Bundles <?= icon('arrow-right') ?>
          </a>
          <a href="#fragrance-lineup" class="btn btn-outline-light" data-magnetic>
            View All Flacons
          </a>
        </div>

        <div class="ad-hero-guarantee">
          <?= icon('shield') ?> <span>100% Authentic Guarantee & Priority Replacement Policy</span>
        </div>
      </div>

      <!-- Right Visual Showcase -->
      <div class="ad-hero-visual">
        <div class="ad-hero-card-showcase">
          <div class="ad-flacon-main" style="background-color: #E3E7F3;">
            <img src="<?= asset('/assets/img/products/blue-orchid.png') ?>" alt="Blue Orchid Luxury Perfume" class="ad-bottle-img">
            <div class="ad-badge-ribbon">Bestseller</div>
          </div>
          
          <div class="ad-flacon-sub ad-flacon-sub-1" style="background-color: #ECE6DA;">
            <img src="<?= asset('/assets/img/products/royal-oud.png') ?>" alt="Royal Oud Perfume" class="ad-sub-bottle">
          </div>

          <div class="ad-flacon-sub ad-flacon-sub-2" style="background-color: #F4E0E0;">
            <img src="<?= asset('/assets/img/products/red-crystal.png') ?>" alt="Red Crystal Perfume" class="ad-sub-bottle">
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Trust Metrics Strip -->
<section class="ad-trust-strip">
  <div class="container">
    <div class="ad-trust-grid">
      <div class="trust-stat">
        <div class="stat-num">12+ Hrs</div>
        <div class="stat-label">Scent Longevity Guarantee</div>
      </div>
      <div class="trust-stat">
        <div class="stat-num">100%</div>
        <div class="stat-label">Alcohol-Free Attar Oils</div>
      </div>
      <div class="trust-stat">
        <div class="stat-num">25,000+</div>
        <div class="stat-label">Bottles Delivered Across India</div>
      </div>
      <div class="trust-stat">
        <div class="stat-num">4.9 ★</div>
        <div class="stat-label">Customer Satisfaction Score</div>
      </div>
    </div>
  </div>
</section>

<!-- Exclusive Ad Bundle Sets -->
<section class="section" id="special-bundles" style="background-color: var(--sand);">
  <div class="container">
    <div class="section-header-center">
      <span class="eyebrow">EXCLUSIVE CAMPAIGN PRIVILEGES</span>
      <h2>Limited Edition Curated Sets</h2>
      <p class="section-desc">Hand-paired by our master distillers with complimentary discovery vials and velvet gift packaging.</p>
    </div>

    <div class="ad-bundles-grid">
      
      <!-- Bundle 1: The Royal Extrait Duo -->
      <div class="ad-bundle-card featured-bundle">
        <div class="bundle-badge">Most Popular • Save ₹500</div>
        <div class="bundle-visual" style="background-color: #F7F5F0;">
          <div class="bundle-img-duo">
            <img src="<?= asset('/assets/img/products/blue-orchid.png') ?>" alt="Blue Orchid">
            <img src="<?= asset('/assets/img/products/royal-oud.png') ?>" alt="Royal Oud">
          </div>
        </div>
        <div class="bundle-details">
          <div class="bundle-tag">THE MAJESTIC EXTRACT DUO</div>
          <h3>Blue Orchid + Royal Oud (100ml × 2)</h3>
          <p>The pinnacle of royal luxury. Crisp sapphire florals paired with dark, smoky Cambodian agarwood.</p>
          <ul class="bundle-perks">
            <li>✓ 2 × 100ml Full Flacons</li>
            <li>✓ 2 Free 2ml Discovery Vials Included</li>
            <li>✓ Complimentary Gold-Embossed Gift Box</li>
            <li>✓ Free Express Courier Delivery</li>
          </ul>
          <div class="bundle-pricing">
            <div class="price-stack">
              <span class="bundle-price">₹2,699</span>
              <span class="bundle-compare">₹3,198</span>
            </div>
            <button type="button" class="btn btn-primary" data-add-to-bag="1" data-name="The Majestic Extract Duo" data-price="2699">
              Claim Bundle ₹2,699
            </button>
          </div>
        </div>
      </div>

      <!-- Bundle 2: Attar Connoisseur Discovery Set -->
      <div class="ad-bundle-card">
        <div class="bundle-badge">Best Value • Save 25%</div>
        <div class="bundle-visual" style="background-color: #F2ECE1;">
          <div class="bundle-img-duo">
            <img src="<?= asset('/assets/img/products/royal-oudh.png') ?>" alt="Royal Oudh Attar">
            <img src="<?= asset('/assets/img/products/aqua-delight.png') ?>" alt="Aqua Delight Attar">
            <img src="<?= asset('/assets/img/products/ruby-red.png') ?>" alt="Ruby Red Attar">
          </div>
        </div>
        <div class="bundle-details">
          <div class="bundle-tag">100% ALCOHOL-FREE TRIO</div>
          <h3>The Royal Attar Discovery Trio (10ml × 3)</h3>
          <p>Three concentrated perfume oils with faceted gold caps. From aquatic freshness to deep smoky oudh.</p>
          <ul class="bundle-perks">
            <li>✓ Royal Oudh + Aqua Delight + Ruby Red</li>
            <li>✓ 100% Pure Alcohol-Free Roll-on Oils</li>
            <li>✓ Pocket Luxury for All-Day Wear</li>
            <li>✓ Satin Travel Pouches Included</li>
          </ul>
          <div class="bundle-pricing">
            <div class="price-stack">
              <span class="bundle-price">₹1,199</span>
              <span class="bundle-compare">₹1,547</span>
            </div>
            <button type="button" class="btn btn-dark" data-add-to-bag="6" data-name="Royal Attar Discovery Trio" data-price="1199">
              Claim Trio ₹1,199
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Comparison: Commercial vs. Luxury Club -->
<section class="section section-dark">
  <div class="container">
    <div class="section-header-center">
      <span class="eyebrow eyebrow-light">THE ATELIER DIFFERENCE</span>
      <h2 style="color: var(--cream);">Why Discerning Perfume Lovers Choose Us</h2>
      <p class="section-desc" style="color: var(--muted-dark);">Pure distillation, zero chemical fillers, and unparalleled sillage.</p>
    </div>

    <div class="ad-comparison-table-wrap">
      <table class="ad-comparison-table">
        <thead>
          <tr>
            <th>Fragrance Aspect</th>
            <th class="col-luxury">Luxury Club Atelier</th>
            <th class="col-standard">Commercial Mall Brands</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Alcohol Content</strong></td>
            <td class="col-luxury"><strong>100% Alcohol-Free Options</strong> (Gentle on skin)</td>
            <td class="col-standard">75% - 85% Harsh Ethyl Alcohol</td>
          </tr>
          <tr>
            <td><strong>Longevity on Pulse Points</strong></td>
            <td class="col-luxury"><strong>12 to 16+ Hours</strong> (Pure essential oils)</td>
            <td class="col-standard">2 to 4 Hours (Fades rapidly)</td>
          </tr>
          <tr>
            <td><strong>Flacon Craftsmanship</strong></td>
            <td class="col-luxury"><strong>Heavy Crystal-Cut Caps</strong> & 24K Crest</td>
            <td class="col-standard">Generic Lightweight Plastic Caps</td>
          </tr>
          <tr>
            <td><strong>Batch Quality</strong></td>
            <td class="col-luxury"><strong>Micro-Batch Distillation</strong> (Peak potency)</td>
            <td class="col-standard">Mass Manufactured Industrial Vats</td>
          </tr>
          <tr>
            <td><strong>Complimentary Perks</strong></td>
            <td class="col-luxury"><strong>2 Free 2ml Vials</strong> + Velvet Pouch</td>
            <td class="col-standard">None Included</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Full Fragrance Lineup Section -->
<section class="section" id="fragrance-lineup">
  <div class="container">
    <div class="section-header-center">
      <span class="eyebrow">THE FULL COLLECTION</span>
      <h2>Individual Signature Flacons</h2>
      <p class="section-desc">Each flacon comes in our gold-crested luxury box with express insured shipping.</p>
    </div>

    <div class="shop-grid" data-reveal-grid>
      <?php foreach (array_slice($allProducts, 0, 8) as $prod): ?>
        <?= View::partial('partials/product-card', ['product' => $prod]) ?>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 48px;">
      <a href="<?= url('/shop') ?>" class="btn btn-outline" style="min-width: 220px;">
        View Complete Catalog <?= icon('arrow-right') ?>
      </a>
    </div>
  </div>
</section>

<!-- Real Customer Testimonials -->
<section class="section" style="background-color: var(--sand);">
  <div class="container">
    <div class="section-header-center">
      <span class="eyebrow">TESTIMONIALS & REVIEWS</span>
      <h2>What Connoisseurs Say</h2>
      <p class="section-desc">Unfiltered thoughts from our verified patrons across India.</p>
    </div>

    <div class="ad-testimonials-grid">
      
      <div class="ad-testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">"Blue Orchid is an absolute masterpiece. I sprayed it on at 8 AM before a wedding and could still distinctly smell the floral vanilla base notes well past midnight. Truly unbelievable sillage."</p>
        <div class="testi-author">
          <div class="author-avatar">AK</div>
          <div>
            <strong>Aditya K.</strong> <span class="verified-pill">✓ Verified Buyer</span>
            <div class="author-loc">Mumbai • Reviewed Blue Orchid EDP</div>
          </div>
        </div>
      </div>

      <div class="ad-testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">"The Royal Oudh attar is 100% authentic pure oil. No chemical headache, no alcoholic sting. Just pure, regal oud that turns heads everywhere I go. Ordered the discovery trio as gifts!"</p>
        <div class="testi-author">
          <div class="author-avatar">SM</div>
          <div>
            <strong>Sameer M.</strong> <span class="verified-pill">✓ Verified Buyer</span>
            <div class="author-loc">New Delhi • Reviewed Royal Oudh Attar</div>
          </div>
        </div>
      </div>

      <div class="ad-testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">"The unboxing experience is top tier. The gold cap has a real heft to it, and the free sample vials helped me discover Morning Jasmine candle, which is now my evening relaxation staple."</p>
        <div class="testi-author">
          <div class="author-avatar">RS</div>
          <div>
            <strong>Rhea S.</strong> <span class="verified-pill">✓ Verified Buyer</span>
            <div class="author-loc">Bengaluru • Reviewed Red Crystal + Jasmine</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Ad FAQs -->
<section class="section">
  <div class="container" style="max-width: 860px;">
    <div class="section-header-center">
      <span class="eyebrow">FREQUENTLY ASKED QUESTIONS</span>
      <h2>Got Questions? We Have Answers.</h2>
    </div>

    <div class="pdp-accordions">
      
      <div class="accordion-item active">
        <button type="button" class="accordion-header">
          <span>Are the roll-on attars really 100% alcohol-free?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>Yes. Our roll-on attars are concentrated pure perfume oils created without a single drop of ethyl alcohol. They are completely skin-safe, non-drying, and ideal for sensitive skin.</p>
        </div>
      </div>

      <div class="accordion-item">
        <button type="button" class="accordion-header">
          <span>How long will my order take to arrive?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>All orders are dispatched from our atelier within 24 hours. We partner with Bluedart and Delhivery express couriers with delivery in 2 to 4 business days across all Indian metro and tier-2 cities.</p>
        </div>
      </div>

      <div class="accordion-item">
        <button type="button" class="accordion-header">
          <span>Is Cash on Delivery (COD) available?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>Yes! We offer Cash on Delivery across 26,000+ pin codes in India alongside instant prepaid options via UPI, Cards, and Netbanking via Razorpay.</p>
        </div>
      </div>

      <div class="accordion-item">
        <button type="button" class="accordion-header">
          <span>What if a bottle arrives damaged or leaks in transit?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>We pack every flacon in custom shock-absorbing molded foam. However, if anything ever arrives imperfect, contact our concierge on WhatsApp within 7 days for an immediate no-questions-asked replacement.</p>
        </div>
      </div>

    </div>

    <div style="text-align: center; margin-top: 48px;">
      <a href="#special-bundles" class="btn btn-primary" style="min-width: 260px;">
        Claim Your Limited Set Today <?= icon('arrow-right') ?>
      </a>
    </div>
  </div>
</section>
