<?php
// app/views/admin/products/index.php
/** @var array $products */
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h3 style="font-size: 20px;">All Fragrance Creations (<?= count($products) ?>)</h3>
  </div>
  <a href="<?= url('/admin/products/create') ?>" class="btn btn-primary btn-sm">
    + Create New Fragrance
  </a>
</div>

<div class="admin-card">
  <div style="overflow-x: auto;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Bottle</th>
          <th>Name</th>
          <th>Category</th>
          <th>Size</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Badge</th>
          <th>Featured</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td>
              <div style="width: 48px; height: 48px; border-radius: 10px; background-color: <?= e($p['tint']) ?>; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                <img src="<?= asset('/assets/img/products/' . $p['image']) ?>" alt="<?= e($p['name']) ?>" style="width: 80%; height: 80%; object-fit: contain; mix-blend-mode: multiply;">
              </div>
            </td>
            <td>
              <strong><a href="<?= url('/admin/products/edit/' . $p['id']) ?>"><?= e($p['name']) ?></a></strong>
              <div style="font-size: 11px; color: var(--muted);"><?= e($p['slug']) ?></div>
            </td>
            <td><?= e($p['category_name']) ?></td>
            <td><?= e($p['size_label']) ?></td>
            <td>
              <strong><?= formatInr($p['price']) ?></strong>
              <?php if (!empty($p['compare_at_price'])): ?>
                <div style="font-size: 11px; text-decoration: line-through; opacity: 0.6;"><?= formatInr($p['compare_at_price']) ?></div>
              <?php endif; ?>
            </td>
            <td>
              <span style="<?= (int)$p['stock_qty'] <= 10 ? 'color: var(--error); font-weight: bold;' : '' ?>">
                <?= $p['stock_qty'] ?>
              </span>
            </td>
            <td>
              <?php if (!empty($p['badge'])): ?>
                <span class="status-badge new"><?= e($p['badge']) ?></span>
              <?php else: ?>
                <span style="color: var(--muted); font-size: 12px;">–</span>
              <?php endif; ?>
            </td>
            <td>
              <?= (int)$p['is_featured'] === 1 ? '<span style="color:var(--gold-text); font-weight:bold;">★ Yes</span>' : 'No' ?>
            </td>
            <td>
              <span class="status-badge <?= (int)$p['is_active'] === 1 ? 'active' : 'inactive' ?>">
                <?= (int)$p['is_active'] === 1 ? 'Active' : 'Inactive' ?>
              </span>
            </td>
            <td>
              <div style="display: flex; gap: 8px;">
                <a href="<?= url('/admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 11px;">
                  Edit
                </a>
                <a href="<?= url('/product/' . $p['slug']) ?>" target="_blank" class="btn btn-sm btn-outline" style="padding: 4px 8px; font-size: 11px;" title="View on Live Site">
                  <?= icon('eye') ?>
                </a>
                <form action="<?= url('/admin/products/delete/' . $p['id']) ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this product?');" style="display:inline;">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm" style="padding: 4px 8px; color: var(--error);" title="Delete Product">
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
</div>
