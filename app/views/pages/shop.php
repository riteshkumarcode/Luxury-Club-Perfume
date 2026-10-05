<?php
// app/views/pages/shop.php
use App\Core\View;

/** @var array $products */
/** @var array $categories */
/** @var ?array $currentCategory */
/** @var array $filters */
/** @var int $totalCount */
/** @var int $page */
/** @var int $totalPages */

$heading = $currentCategory ? $currentCategory['name'] : 'All Fragrances';
$description = $currentCategory ? $currentCategory['description'] : 'Explore our complete olfactory universe: 100 ml Eau de Parfum in crystal flacons, concentrated roll-on attars, scented candles and car pods.';
?>

<!-- Shop Dark Hero -->
<section class="shop-hero">
  <div class="container">
    <?= View::partial('partials/breadcrumb', ['items' => [
      ['label' => 'Home', 'url' => '/'],
      ['label' => 'Shop', 'url' => '/shop'],
      ['label' => $heading]
    ]]) ?>

    <h1 style="color: var(--cream); margin-bottom: 12px;"><?= e($heading) ?></h1>
    <p style="color: var(--muted-dark); max-width: 680px; font-size: 16px;"><?= e($description) ?></p>
  </div>
</section>

<!-- Sticky Shop Toolbar -->
<div class="shop-toolbar">
  <div class="container shop-toolbar-inner">
    
    <!-- Category Chips -->
    <div class="category-chips">
      <a href="<?= url('/shop') ?>" class="chip-btn <?= empty($currentCategory) ? 'active' : '' ?>">
        All (<?= $totalCount ?>)
      </a>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= url('/shop/' . $cat['slug']) ?>" class="chip-btn <?= ($currentCategory && $currentCategory['id'] == $cat['id']) ? 'active' : '' ?>">
          <?= e($cat['name']) ?> (<?= $cat['product_count'] ?>)
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Controls Right: Sort & Grid Density -->
    <div class="shop-controls-right">
      
      <!-- Sort Select -->
      <select id="shop-sort-select" class="form-select" style="padding: 8px 14px; font-size: 13px; border-radius: 999px; width: auto;" aria-label="Sort products">
        <option value="featured" <?= ($filters['sort'] ?? '') === 'featured' ? 'selected' : '' ?>>Sort: Featured</option>
        <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
        <option value="name_asc" <?= ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Name: A to Z</option>
        <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
      </select>

      <!-- Grid Density Buttons (Desktop only) -->
      <div style="display: flex; gap: 4px;">
        <button type="button" class="header-action-btn" data-density="3" aria-label="3 Columns Grid" style="padding: 6px; color: var(--ink);">
          <?= icon('grid-3') ?>
        </button>
        <button type="button" class="header-action-btn active" data-density="4" aria-label="4 Columns Grid" style="padding: 6px; color: var(--ink);">
          <?= icon('grid-4') ?>
        </button>
      </div>

    </div>

  </div>
</div>

<!-- Shop Main Content & Grid -->
<section class="section" style="padding-top: 48px;">
  <div class="container">
    
    <!-- Active Filters Summary -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; font-size: 14px; color: var(--muted);">
      <div>
        Showing <strong><?= count($products) ?></strong> of <strong><?= $totalCount ?></strong> creations
      </div>
      <?php if (!empty($filters['q']) || !empty($filters['min_price']) || !empty($filters['max_price']) || !empty($filters['sort'])): ?>
        <a href="<?= url('/shop' . ($currentCategory ? '/' . $currentCategory['slug'] : '')) ?>" style="color: var(--gold-text); font-weight: 600; text-decoration: underline;">
          Clear All Filters
        </a>
      <?php endif; ?>
    </div>

    <!-- Product Grid -->
    <?php if (empty($products)): ?>
      <div style="text-align: center; padding: 80px 20px;">
        <h3 style="font-size: 26px; margin-bottom: 12px;">No Fragrances Found</h3>
        <p style="margin-bottom: 24px;">No creations match your selected filters. Please adjust your criteria.</p>
        <a href="<?= url('/shop') ?>" class="btn btn-dark">View Entire Boutique</a>
      </div>
    <?php else: ?>
      <div class="shop-grid" data-reveal-grid>
        <?php foreach ($products as $p): ?>
          <?= View::partial('partials/product-card', ['product' => $p]) ?>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 8px; margin-top: 64px;">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?><?= !empty($filters['sort']) ? '&sort=' . e($filters['sort']) : '' ?>" 
               class="btn btn-sm <?= $i === $page ? 'btn-dark' : 'btn-outline' ?>" 
               style="min-width: 44px;">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>

  </div>
</section>
