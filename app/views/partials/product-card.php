<?php
// app/views/partials/product-card.php
use App\Models\Cart;

/** @var array $product */
$isInWishlist = Cart::isInWishlist((int)$product['id']);
$priceFormatted = formatInr($product['price']);
?>
<div class="product-card collection-filterable-item" data-category="<?= e($product['category_slug'] ?? '') ?>" data-id="<?= $product['id'] ?>" data-reveal>
  
  <!-- Media Frame on Tint with Multiply -->
  <div class="product-card-media" style="background-color: <?= e($product['tint']) ?>;">
    
    <!-- Badge Top Left -->
    <?php if (!empty($product['badge'])): ?>
      <div class="product-badge"><?= e($product['badge']) ?></div>
    <?php endif; ?>

    <!-- Wishlist Heart Button Top Right -->
    <button type="button" class="btn-wishlist <?= $isInWishlist ? 'active' : '' ?>" data-wishlist-toggle="<?= $product['id'] ?>" aria-label="Save <?= e($product['name']) ?> to wishlist">
      <?= icon('heart') ?>
    </button>

    <!-- Product Image with multiply blend mode -->
    <a href="<?= url('/product/' . $product['slug']) ?>" style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;">
      <img src="<?= asset('/assets/img/products/' . $product['image']) ?>" alt="<?= e($product['name']) ?>" class="product-card-img" width="360" height="390" loading="lazy">
    </a>

    <!-- Quick Add to Bag Slide-Up Button -->
    <div class="product-card-quickadd">
      <button type="button" class="btn-card-add" data-add-to-bag="<?= $product['id'] ?>" data-name="<?= e($product['name']) ?>" data-price="<?= $product['price'] ?>" aria-label="Add <?= e($product['name']) ?> to bag">
        <?= icon('bag') ?> ADD TO BAG · <?= $priceFormatted ?>
      </button>
    </div>

  </div>

  <!-- Content Below Media -->
  <div class="product-card-info">
    <div class="product-card-eyebrow"><?= e($product['category_name'] ?? 'Fragrance') ?></div>
    <h3 class="product-card-title">
      <a href="<?= url('/product/' . $product['slug']) ?>"><?= e($product['name']) ?></a>
    </h3>
    <div class="product-card-meta">
      <span class="product-card-price"><?= $priceFormatted ?></span>
      <span>· <?= e($product['size_label']) ?></span>
      <?php if (!empty($product['compare_at_price'])): ?>
        <span class="product-card-compare"><?= formatInr($product['compare_at_price']) ?></span>
      <?php endif; ?>
    </div>
  </div>

</div>
