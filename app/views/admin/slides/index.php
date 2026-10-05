<?php
// app/views/admin/slides/index.php
/** @var array $slides */
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <h3 style="font-size: 20px;">Hero Slides Showcase (<?= count($slides) ?>)</h3>
  <a href="<?= url('/admin/slides/create') ?>" class="btn btn-primary btn-sm">
    + Add Hero Slide
  </a>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Visual Preview</th>
        <th>Eyebrow & Title</th>
        <th>Background</th>
        <th>Linked Product</th>
        <th>Outline Word</th>
        <th>Order</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($slides as $slide): ?>
        <tr>
          <td>
            <div style="width: 54px; height: 72px; border-radius: 999px 999px 8px 8px; background-color: <?= e($slide['tint']) ?>; border: 2px solid <?= e($slide['bg_color']) ?>; display:flex; align-items:center; justify-content:center; overflow:hidden;">
              <img src="<?= asset('/assets/img/products/' . $slide['image']) ?>" alt="" style="width: 80%; height: 80%; object-fit: contain; mix-blend-mode: multiply;">
            </div>
          </td>
          <td>
            <div style="font-size: 10px; color: var(--gold-text); font-weight: 700;"><?= e($slide['eyebrow']) ?></div>
            <strong><?= $slide['title'] ?></strong>
          </td>
          <td>
            <div style="display:flex; align-items:center; gap:6px;">
              <span style="display:inline-block; width:16px; height:16px; border-radius:50%; background:<?= e($slide['bg_color']) ?>; border:1px solid #ccc;"></span>
              <code><?= e($slide['bg_color']) ?></code>
            </div>
          </td>
          <td><?= e($slide['product_name'] ?? 'Custom link') ?></td>
          <td><code><?= e($slide['outline_word']) ?></code></td>
          <td><?= $slide['sort_order'] ?></td>
          <td>
            <span class="status-badge <?= (int)$slide['is_active'] === 1 ? 'active' : 'inactive' ?>">
              <?= (int)$slide['is_active'] === 1 ? 'Active' : 'Inactive' ?>
            </span>
          </td>
          <td>
            <div style="display: flex; gap: 8px;">
              <a href="<?= url('/admin/slides/edit/' . $slide['id']) ?>" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 11px;">
                Edit
              </a>
              <form action="<?= url('/admin/slides/delete/' . $slide['id']) ?>" method="POST" onsubmit="return confirm('Remove this hero slide?');" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm" style="padding: 4px 8px; color: var(--error);">
                  <?= icon('trash') ?>
                </button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
