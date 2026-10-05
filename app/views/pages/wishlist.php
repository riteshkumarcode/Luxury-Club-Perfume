<?php
// app/views/pages/wishlist.php
use App\Core\View;

/** @var array $items */
?>

<section class="section" style="padding-top: 48px;">
  <div class="container">
    
    <?= View::partial('partials/breadcrumb', ['items' => [
      ['label' => 'Home', 'url' => '/'],
      ['label' => 'Wishlist']
    ]]) ?>

    <h1 style="font-size: 38px; margin-bottom: 32px;">Saved Fragrances (<?= count($items) ?>)</h1>

    <?php if (empty($items)): ?>
      <div style="text-align: center; padding: 80px 20px; background: var(--sand); border-radius: var(--radius-card);">
        <div style="font-size: 48px; color: var(--gold); margin-bottom: 16px;"><?= icon('heart') ?></div>
        <h2 style="font-size: 26px; margin-bottom: 12px;">Your Wishlist is Empty</h2>
        <p style="max-width: 480px; margin: 0 auto 28px;">Click the heart icon on any perfume or attar flacon to save your favourite creations.</p>
        <a href="<?= url('/shop') ?>" class="btn btn-dark" data-magnetic>
          Explore Collection <?= icon('arrow-right') ?>
        </a>
      </div>
    <?php else: ?>
      
      <div class="shop-grid" data-reveal-grid>
        <?php foreach ($items as $product): ?>
          <?= View::partial('partials/product-card', ['product' => $product]) ?>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>

  </div>
</section>
