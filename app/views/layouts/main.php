<?php
// app/views/layouts/main.php
use App\Core\View;
$meta = View::getMeta();
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($meta['title']) ?></title>
  <meta name="description" content="<?= e($meta['description']) ?>">
  <link rel="canonical" href="<?= e($meta['canonical']) ?>">
  <meta name="csrf-token" content="<?= e($csrf) ?>">

  <!-- Open Graph / Social -->
  <meta property="og:type" content="<?= e($meta['og_type']) ?>">
  <meta property="og:title" content="<?= e($meta['title']) ?>">
  <meta property="og:description" content="<?= e($meta['description']) ?>">
  <meta property="og:url" content="<?= e($meta['canonical']) ?>">
  <meta property="og:image" content="<?= e($meta['og_image']) ?>">
  <meta name="twitter:card" content="summary_large_image">

  <!-- Google Fonts Preconnect -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- External Library Styles (Swiper 11) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

  <!-- Hand-written Design System CSS -->
  <link rel="stylesheet" href="<?= asset('/assets/css/app.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/components.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/pages.css') ?>">

  <!-- JSON-LD Structured Data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Luxury Club",
    "url": "<?= e(url()) ?>",
    "logo": "<?= e(url('/assets/img/logo.png')) ?>",
    "description": "Artisanal luxury perfumery crafting Eau de Parfum, alcohol-free roll-on attars, scented candles and car pods in India.",
    "sameAs": [
      "<?= e(get_setting('instagram_url', 'https://instagram.com/luxuryclub')) ?>",
      "<?= e(get_setting('facebook_url', 'https://facebook.com/luxuryclub')) ?>"
    ]
  }
  </script>
  <?php if (!empty($meta['json_ld'])): ?>
    <?php foreach ($meta['json_ld'] as $schema): ?>
      <script type="application/ld+json">
        <?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
      </script>
    <?php endforeach; ?>
  <?php endif; ?>
</head>
<body>

  <!-- Announcement Strip -->
  <?= View::partial('partials/announcement') ?>

  <!-- Sticky Luxury Header -->
  <?= View::partial('partials/header') ?>

  <!-- Main View Content -->
  <main id="main-content">
    <?= $content ?>
  </main>

  <!-- Newsletter Band -->
  <?= View::partial('partials/newsletter') ?>

  <!-- Luxury Footer -->
  <?= View::partial('partials/footer') ?>

  <!-- Cart & Wishlist Drawer -->
  <?= View::partial('partials/cart-drawer') ?>

  <!-- Live Search Overlay -->
  <?= View::partial('partials/search-overlay') ?>

  <!-- Toast Notification Pill -->
  <?= View::partial('partials/toast') ?>

  <!-- Libraries: GSAP 3, ScrollTrigger, Lenis, Swiper 11 -->
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.9/dist/lenis.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

  <!-- Main ES6 Application Script -->
  <script type="module" src="<?= asset('/assets/js/app.js') ?>"></script>
</body>
</html>
