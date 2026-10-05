<?php
// app/views/admin/categories/index.php
/** @var array $categories */
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <h3 style="font-size: 20px;">Product Categories (<?= count($categories) ?>)</h3>
  <a href="<?= url('/admin/categories/create') ?>" class="btn btn-primary btn-sm">
    + Create Category
  </a>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Cover Image</th>
        <th>Category Name</th>
        <th>Slug</th>
        <th>Products Count</th>
        <th>Sort Order</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($categories as $cat): ?>
        <tr>
          <td>
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #F3EDE2; display:flex; align-items:center; justify-content:center; overflow:hidden;">
              <img src="<?= asset('/assets/img/products/' . ($cat['cover_image'] ?: 'blue-orchid.png')) ?>" alt="<?= e($cat['name']) ?>" style="width: 80%; height: 80%; object-fit: contain; mix-blend-mode: multiply;">
            </div>
          </td>
          <td><strong><?= e($cat['name']) ?></strong></td>
          <td><code><?= e($cat['slug']) ?></code></td>
          <td><strong><?= (int)($cat['product_count'] ?? 0) ?></strong></td>
          <td><?= $cat['sort_order'] ?></td>
          <td>
            <span class="status-badge <?= (int)$cat['is_active'] === 1 ? 'active' : 'inactive' ?>">
              <?= (int)$cat['is_active'] === 1 ? 'Active' : 'Inactive' ?>
            </span>
          </td>
          <td>
            <div style="display: flex; gap: 8px;">
              <a href="<?= url('/admin/categories/edit/' . $cat['id']) ?>" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 11px;">
                Edit
              </a>
              <form action="<?= url('/admin/categories/delete/' . $cat['id']) ?>" method="POST" onsubmit="return confirm('Delete this category? Products within it will lose their category association.');" style="display:inline;">
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
