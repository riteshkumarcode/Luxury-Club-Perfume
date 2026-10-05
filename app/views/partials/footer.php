<?php
// app/views/partials/footer.php
use App\Models\Category;
$categories = Category::allActive();

$phone = get_setting('phone', '+91 98765 43210');
$email = get_setting('email', 'concierge@luxuryclub.com');
$address = get_setting('address', 'Luxury Club Atelier, Mumbai, Maharashtra 400001');
$instagram = get_setting('instagram_url', 'https://instagram.com/luxuryclub');
$facebook = get_setting('facebook_url', 'https://facebook.com/luxuryclub');
$youtube = get_setting('youtube_url', 'https://youtube.com/luxuryclub');
?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      
      <!-- Brand Column -->
      <div class="footer-brand">
        <a href="<?= url('/') ?>" class="site-logo footer-logo" aria-label="Luxury Club Home">
          <img src="<?= asset('/assets/img/logo.png') ?>" alt="Luxury Club — The Magic of Luxury Fragrances" class="footer-logo-img">
        </a>
        <p><?= e($address) ?></p>
        <p style="margin-top: 8px;">
          <a href="mailto:<?= e($email) ?>" style="color: var(--gold);"><?= e($email) ?></a><br>
          <a href="tel:<?= e($phone) ?>"><?= e($phone) ?></a>
        </p>
      </div>

      <!-- Shop Column -->
      <div class="footer-col">
        <h4>Collection</h4>
        <ul class="footer-nav">
          <li><a href="<?= url('/shop') ?>">All Fragrances</a></li>
          <?php foreach ($categories as $cat): ?>
            <li><a href="<?= url('/shop/' . $cat['slug']) ?>"><?= e($cat['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Company Column -->
      <div class="footer-col">
        <h4>Experience</h4>
        <ul class="footer-nav">
          <li><a href="<?= url('/about') ?>">Our Story</a></li>
          <li><a href="<?= url('/experience') ?>">VIP Ad Collection</a></li>
          <li><a href="<?= url('/#bulk-orders') ?>">Bulk & Wedding Gifting</a></li>
          <li><a href="<?= url('/contact') ?>">Concierge & Support</a></li>
          <li><a href="<?= url('/faq') ?>">Fragrance FAQ</a></li>
        </ul>
      </div>

      <!-- Policies Column -->
      <div class="footer-col">
        <h4>Client Care</h4>
        <ul class="footer-nav">
          <li><a href="<?= url('/policies/shipping') ?>">Shipping & Delivery</a></li>
          <li><a href="<?= url('/policies/returns') ?>">Returns & Exchanges</a></li>
          <li><a href="<?= url('/policies/privacy') ?>">Privacy Policy</a></li>
          <li><a href="<?= url('/policies/terms') ?>">Terms of Service</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <div>
        © <?= date('Y') ?> Luxury Club Perfumes. All rights reserved. Crafted with artisanal passion.
      </div>
      <div class="footer-socials">
        <a href="<?= e($instagram) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('instagram') ?></a>
        <a href="<?= e($facebook) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a>
        <a href="<?= e($youtube) ?>" target="_blank" rel="noopener" aria-label="YouTube"><?= icon('youtube') ?></a>
      </div>
    </div>
  </div>
</footer>
