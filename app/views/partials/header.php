<?php
// app/views/partials/header.php
use App\Models\Category;
use App\Models\Cart;

$categories = Category::allActive();
$totals = Cart::getTotals();
$wishlist = Cart::getWishlist();
$cartCount = $totals['count'];
$wishlistCount = count($wishlist);

$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
?>
<header class="site-header">
  <div class="container header-inner">
    
    <!-- Left Navigation -->
    <nav class="header-left" aria-label="Main Navigation">
      <ul class="nav-menu">
        <li>
          <a href="<?= url('/') ?>" class="nav-link <?= $currentUri === '/' ? 'active' : '' ?>">Home</a>
        </li>
        <li class="nav-item-has-mega">
          <a href="<?= url('/shop') ?>" class="nav-link <?= str_starts_with($currentUri, '/shop') ? 'active' : '' ?>">
            Shop <?= icon('chevron-down', 'nav-chevron') ?>
          </a>
          <!-- Mega Menu Dropdown -->
          <div class="mega-menu" role="region" aria-label="Shop categories">
            <div class="mega-grid">
              <?php foreach ($categories as $cat): ?>
                <a href="<?= url('/shop/' . $cat['slug']) ?>" class="mega-category-card">
                  <div class="mega-card-img-wrap" style="background-color: #F3EDE2;">
                    <img src="<?= asset('/assets/img/products/' . ($cat['cover_image'] ?: 'blue-orchid.png')) ?>" alt="<?= e($cat['name']) ?>" loading="lazy">
                  </div>
                  <div class="mega-category-title"><?= e($cat['name']) ?></div>
                  <div class="mega-category-count"><?= (int)($cat['product_count'] ?? 0) ?> Fragrances</div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </li>
        <li>
          <a href="<?= url('/about') ?>" class="nav-link <?= $currentUri === '/about' ? 'active' : '' ?>">About</a>
        </li>
        <li>
          <a href="<?= url('/contact') ?>" class="nav-link <?= $currentUri === '/contact' ? 'active' : '' ?>">Contact</a>
        </li>
      </ul>

      <!-- Mobile Hamburger Button -->
      <button type="button" class="mobile-nav-toggle" aria-label="Open Navigation Menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </nav>

    <!-- Center Brand Logo -->
    <div class="header-center">
      <a href="<?= url('/') ?>" class="site-logo">
        <div class="logo-crown"><?= icon('crown') ?></div>
        <div class="logo-title">Luxury Club</div>
        <div class="logo-tagline">The Magic of Luxury Fragrances</div>
      </a>
    </div>

    <!-- Right Actions (Search, Wishlist, Bag) -->
    <div class="header-right">
      <!-- Live Search Button -->
      <button type="button" class="header-action-btn" data-search-open aria-label="Search Fragrances">
        <?= icon('search') ?>
      </button>

      <!-- Wishlist Drawer Trigger -->
      <button type="button" class="header-action-btn" data-drawer-open="wishlist" aria-label="View Wishlist">
        <?= icon('heart') ?>
        <span class="badge-count wishlist-badge-count" style="<?= $wishlistCount > 0 ? '' : 'display:none;' ?>"><?= $wishlistCount ?></span>
      </button>

      <!-- Shopping Bag Pill -->
      <button type="button" class="bag-pill-btn" data-drawer-open="bag" aria-label="Shopping Bag">
        <?= icon('bag') ?>
        <span class="bag-pill-text">BAG · <?= $cartCount ?></span>
      </button>
    </div>

  </div>

  <!-- Fullscreen Mobile Navigation Drawer -->
  <div class="mobile-nav-drawer" data-lenis-prevent>
    <div class="mobile-nav-header">
      <div class="site-logo">
        <div class="logo-crown"><?= icon('crown') ?></div>
        <div class="logo-title">Luxury Club</div>
        <div class="logo-tagline">The Magic of Luxury Fragrances</div>
      </div>
      <button type="button" class="mobile-nav-close" aria-label="Close menu"><?= icon('close') ?></button>
    </div>
    <ul class="mobile-nav-list">
      <li><a href="<?= url('/') ?>" class="<?= $currentUri === '/' ? 'active' : '' ?>">Home</a></li>
      <li><a href="<?= url('/shop') ?>" class="<?= $currentUri === '/shop' ? 'active' : '' ?>">Shop All Fragrances</a></li>
      <?php foreach ($categories as $cat): ?>
        <li><a href="<?= url('/shop/' . $cat['slug']) ?>" class="mobile-nav-category-link <?= $currentUri === '/shop/' . $cat['slug'] ? 'active' : '' ?>">↳ <?= e($cat['name']) ?></a></li>
      <?php endforeach; ?>
      <li><a href="<?= url('/about') ?>" class="<?= $currentUri === '/about' ? 'active' : '' ?>">Our Story</a></li>
      <li><a href="<?= url('/contact') ?>" class="<?= $currentUri === '/contact' ? 'active' : '' ?>">Concierge & Contact</a></li>
      <li><a href="<?= url('/faq') ?>" class="<?= $currentUri === '/faq' ? 'active' : '' ?>">FAQ</a></li>
    </ul>
    <div class="mobile-nav-footer">
      <a href="<?= url('/shop') ?>" class="btn btn-primary btn-block">Explore Collection</a>
      <div style="display: flex; justify-content: center; gap: 20px; color: var(--gold); font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 8px;">
        <span>Free Shipping > ₹999</span>
        <span>•</span>
        <span>100% Authentic</span>
      </div>
    </div>
  </div>
</header>
