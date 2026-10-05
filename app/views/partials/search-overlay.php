<?php
// app/views/partials/search-overlay.php
use App\Models\Product;
$popularProducts = Product::findFeatured(4);
?>
<div class="search-overlay" role="dialog" aria-modal="true" aria-label="Search fragrances" data-lenis-prevent>
  
  <div class="search-overlay-header">
    <div class="site-logo">
      <div class="logo-title">Luxury Club</div>
    </div>
    <button type="button" class="search-overlay-close" aria-label="Close search overlay">
      <?= icon('close') ?>
    </button>
  </div>

  <div class="search-overlay-body">
    
    <div class="search-input-wrap">
      <input type="search" class="search-main-input" placeholder="Search fragrances, notes, attars..." aria-label="Search keyword" autocomplete="off">
      <div class="search-input-icon"><?= icon('search') ?></div>
    </div>

    <!-- Live Search Results (Populated via JS) -->
    <div class="search-results-grid"></div>

    <!-- Popular Right Now Section (shown when empty) -->
    <div class="search-popular-section">
      <div class="eyebrow" style="color: var(--gold); margin-bottom: 20px;">POPULAR RIGHT NOW</div>
      <div class="search-results-grid">
        <?php foreach ($popularProducts as $p): ?>
          <a href="<?= url('/product/' . $p['slug']) ?>" class="search-result-item">
            <div class="search-thumb" style="background-color: <?= e($p['tint']) ?>;">
              <img src="<?= asset('/assets/img/products/' . $p['image']) ?>" alt="<?= e($p['name']) ?>">
            </div>
            <div class="search-info">
              <div class="search-cat"><?= e($p['category_name']) ?></div>
              <h5><?= e($p['name']) ?></h5>
              <div class="search-price"><?= formatInr($p['price']) ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

</div>
