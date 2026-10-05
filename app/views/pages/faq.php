<?php
// app/views/pages/faq.php
use App\Core\View;
?>

<section class="section section-dark" style="padding-top: 80px; padding-bottom: 70px;">
  <div class="container">
    <?= View::partial('partials/breadcrumb', ['items' => [
      ['label' => 'Home', 'url' => '/'],
      ['label' => 'FAQ']
    ]]) ?>
    <span class="eyebrow eyebrow-light">HELP & GUIDANCE</span>
    <h1 style="color: var(--cream); margin-bottom: 16px;">Frequently Asked Questions</h1>
    <p style="color: var(--muted-dark); max-width: 600px; font-size: 16px;">
      Everything you need to know about our formulations, roll-on attars, shipping promises and gifting options.
    </p>
  </div>
</section>

<section class="section">
  <div class="container" style="max-width: 860px;">
    
    <div style="margin-bottom: 56px;">
      <h2 style="font-size: 28px; margin-bottom: 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">
        1. Products & Formulations
      </h2>
      <div class="pdp-accordions">
        
        <div class="accordion-item active">
          <button type="button" class="accordion-header">
            <span>What makes roll-on attars special?</span>
            <div class="accordion-icon"><?= icon('chevron-down') ?></div>
          </button>
          <div class="accordion-body">
            <p>Our attars are 100% alcohol-free pure perfume oils. Because there is no alcohol to dry out your skin or evaporate away, a tiny touch on pulse points lasts 12+ hours while respecting sensitive skin.</p>
          </div>
        </div>

        <div class="accordion-item">
          <button type="button" class="accordion-header">
            <span>How do I apply Eau de Parfum vs. Attar?</span>
            <div class="accordion-icon"><?= icon('chevron-down') ?></div>
          </button>
          <div class="accordion-body">
            <p>Spray Eau de Parfum from 15 cm onto collarbones, wrists and inner elbows without rubbing. For attars, roll the faceted ball onto your wrists, neck, and behind ears and allow body warmth to diffuse the fragrance.</p>
          </div>
        </div>

        <div class="accordion-item">
          <button type="button" class="accordion-header">
            <span>How long do the scented jar candles burn?</span>
            <div class="accordion-icon"><?= icon('chevron-down') ?></div>
          </button>
          <div class="accordion-body">
            <p>Our 100 g luxury jar candles burn cleanly for 25–30 hours. Always trim the wick to 5 mm before lighting and let the entire surface melt evenly on first burn.</p>
          </div>
        </div>

      </div>
    </div>

    <div style="margin-bottom: 56px;">
      <h2 style="font-size: 28px; margin-bottom: 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">
        2. Orders & Express Shipping
      </h2>
      <div class="pdp-accordions">
        
        <div class="accordion-item">
          <button type="button" class="accordion-header">
            <span>What is the free shipping threshold?</span>
            <div class="accordion-icon"><?= icon('chevron-down') ?></div>
          </button>
          <div class="accordion-body">
            <p>All orders with a subtotal of ₹999 or more qualify for 100% complimentary express shipping across India.</p>
          </div>
        </div>

        <div class="accordion-item">
          <button type="button" class="accordion-header">
            <span>How long does delivery take?</span>
            <div class="accordion-icon"><?= icon('chevron-down') ?></div>
          </button>
          <div class="accordion-body">
            <p>Metro destinations receive delivery within 2–4 business days. Regional destinations across India take 4–6 business days.</p>
          </div>
        </div>

      </div>
    </div>

    <div>
      <h2 style="font-size: 28px; margin-bottom: 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">
        3. Returns & Concierge Support
      </h2>
      <div class="pdp-accordions">
        
        <div class="accordion-item">
          <button type="button" class="accordion-header">
            <span>What is your return policy?</span>
            <div class="accordion-icon"><?= icon('chevron-down') ?></div>
          </button>
          <div class="accordion-body">
            <p>Unopened and sealed flacons in original packaging may be returned within 7 days. If your order arrives damaged in transit, our concierge will issue an immediate replacement.</p>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>
