<?php
// app/views/pages/about.php
use App\Core\View;
?>

<!-- Dark Hero -->
<section class="section section-dark" style="position: relative; overflow: hidden; padding-top: 100px; padding-bottom: 100px;">
  
  <div class="hero-outline-word" style="opacity: 0.08;" aria-hidden="true">
    Our Story
  </div>

  <div class="container" style="position: relative; z-index: 2;">
    <div style="max-width: 760px;">
      <?= View::partial('partials/breadcrumb', ['items' => [
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'About Luxury Club']
      ]]) ?>
      <span class="eyebrow eyebrow-light">ABOUT LUXURY CLUB</span>
      <h1 style="color: var(--cream); margin-bottom: 24px;">
        The magic of <br><em style="font-style: italic; color: var(--gold);">luxury fragrances</em>
      </h1>
      <p style="font-size: 20px; line-height: 1.6; color: var(--cream); opacity: 0.9;">
        Born from a profound reverence for ancestral perfumery and modern botanical distillation, Luxury Club crafts olfactory masterworks that transcend the ordinary.
      </p>
    </div>
  </div>
</section>

<!-- Story Section with 2x2 Collage -->
<section class="section">
  <div class="container">
    <div class="attar-feature-grid">
      
      <!-- 2x2 Collage -->
      <div class="attar-collage" data-reveal>
        <div class="attar-collage-tile" style="background-color: #E3E7F3;">
          <img src="<?= asset('/assets/img/products/blue-orchid.png') ?>" alt="Blue Orchid Eau de Parfum" loading="lazy">
        </div>
        <div class="attar-collage-tile" style="background-color: #EEEADF;">
          <img src="<?= asset('/assets/img/products/royal-oudh.png') ?>" alt="Royal Oudh Attar" loading="lazy">
        </div>
        <div class="attar-collage-tile" style="background-color: #F5E1DD;">
          <img src="<?= asset('/assets/img/products/english-rose.png') ?>" alt="English Rose Candle" loading="lazy">
        </div>
        <div class="attar-collage-tile" style="background-color: #DFEDF2;">
          <img src="<?= asset('/assets/img/products/pod-blue.png') ?>" alt="Hanging Pod Blue" loading="lazy">
        </div>
      </div>

      <!-- Story Text -->
      <div data-reveal>
        <span class="eyebrow">OUR HERITAGE</span>
        <h2 style="margin-bottom: 20px;">Crafted to be noticed, made to be kept close</h2>
        <p style="margin-bottom: 16px;">
          <!-- TODO: Client to confirm brand story — founding year, founder name, city of origin, and expanded atelier mission. -->
          Every fragrance in the Luxury Club portfolio begins with single-origin natural botanicals, wild-harvested resins, and hand-selected floral absolutes. Our master blenders harmonise timeless Arabian agarwood traditions with French Haute Parfumerie standards.
        </p>
        <p style="margin-bottom: 24px;">
          From our weighty 100 ml crystal flacons adorned with engraved gold crests to our concentrated 100% alcohol-free roll-on attars, every touchpoint is designed to evoke a world of understated opulence.
        </p>
        <a href="<?= url('/shop') ?>" class="btn btn-dark" data-magnetic>
          Explore the Range <?= icon('arrow-right') ?>
        </a>
      </div>

    </div>
  </div>
</section>

<!-- Values Cards -->
<section class="section section-sand">
  <div class="container">
    <div style="text-align: center; max-width: 600px; margin: 0 auto 40px;">
      <span class="eyebrow">OUR PILLARS</span>
      <h2>The Luxury Club Philosophy</h2>
    </div>

    <div class="story-values-grid" data-reveal-grid>
      
      <div class="value-card">
        <div class="value-num">01</div>
        <h3 style="font-size: 22px; margin-bottom: 10px;">Crafted Presentation</h3>
        <p style="font-size: 14px; line-height: 1.6;">
          Crystal-cut caps, gold flacon crests, and jewel-box packaging. An extraordinary ritual of unboxing that honors the art within.
        </p>
      </div>

      <div class="value-card">
        <div class="value-num">02</div>
        <h3 style="font-size: 22px; margin-bottom: 10px;">Choice of Form</h3>
        <p style="font-size: 14px; line-height: 1.6;">
          Whether you prefer grand sillage from an Eau de Parfum or an intimate, alcohol-free oil that warms on your skin, our creations adapt to you.
        </p>
      </div>

      <div class="value-card">
        <div class="value-num">03</div>
        <h3 style="font-size: 22px; margin-bottom: 10px;">Artisanal Longevity</h3>
        <!-- TODO: Client to provide custom third brand value descriptor -->
        <p style="font-size: 14px; line-height: 1.6;">
          High concentration formulation ensuring your scent lingers from dawn meetings through midnight galas without fading away.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- CTA Band -->
<section class="section section-dark" style="text-align: center;">
  <div class="container" style="max-width: 700px;">
    <span class="eyebrow eyebrow-light">FIND YOUR SIGNATURE</span>
    <h2 style="color: var(--cream); margin-bottom: 16px;">Find the one that feels like you</h2>
    <p style="margin-bottom: 32px;">Speak with our fragrance concierge or explore our curated collections online.</p>
    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
      <a href="<?= url('/shop') ?>" class="btn btn-primary" data-magnetic>
        Shop the Collection <?= icon('arrow-right') ?>
      </a>
      <a href="<?= url('/contact') ?>" class="btn btn-outline-light" data-magnetic>
        Talk to Us
      </a>
    </div>
  </div>
</section>
