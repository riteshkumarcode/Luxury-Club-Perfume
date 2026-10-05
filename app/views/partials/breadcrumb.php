<?php
// app/views/partials/breadcrumb.php
/** @var array $items e.g. [['label' => 'Home', 'url' => '/'], ['label' => 'Shop', 'url' => '/shop'], ['label' => 'Product']] */
?>
<nav class="breadcrumb" aria-label="Breadcrumb">
  <?php foreach ($items as $index => $item): ?>
    <?php if ($index > 0): ?>
      <span class="breadcrumb-separator">/</span>
    <?php endif; ?>

    <?php if (!empty($item['url']) && $index < count($items) - 1): ?>
      <a href="<?= e(url($item['url'])) ?>"><?= e($item['label']) ?></a>
    <?php else: ?>
      <span><?= e($item['label']) ?></span>
    <?php endif; ?>
  <?php endforeach; ?>
</nav>
