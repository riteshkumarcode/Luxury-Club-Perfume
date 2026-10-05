<?php
// app/views/admin/categories/form.php
/** @var ?array $category */
/** @var array $errors */

$isEdit = !empty($category['id']);
$actionUrl = $isEdit ? url('/admin/categories/edit/' . $category['id']) : url('/admin/categories/create');
?>

<div style="margin-bottom: 24px;">
  <a href="<?= url('/admin/categories') ?>" style="font-size: 13px; color: var(--muted); text-decoration: underline;">
    &larr; Back to Categories
  </a>
  <h3 style="font-size: 24px; margin-top: 4px;"><?= $isEdit ? 'Edit Category' : 'Create Category' ?></h3>
</div>

<div class="admin-card" style="max-width: 600px;">
  <form action="<?= $actionUrl ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group">
      <label class="form-label">Category Name *</label>
      <input type="text" name="name" id="cat-name" class="form-input" value="<?= e($category['name'] ?? '') ?>" placeholder="e.g. Eau de Parfum" required>
      <?php if (!empty($errors['name'])): ?><div class="form-error-msg"><?= e($errors['name'][0]) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
      <label class="form-label">Slug *</label>
      <input type="text" name="slug" id="cat-slug" class="form-input" value="<?= e($category['slug'] ?? '') ?>" placeholder="e.g. eau-de-parfum" required>
      <?php if (!empty($errors['slug'])): ?><div class="form-error-msg"><?= e($errors['slug'][0]) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
      <label class="form-label">Cover Image Filename (e.g. red-crystal.png)</label>
      <input type="text" name="cover_image" class="form-input" value="<?= e($category['cover_image'] ?? 'red-crystal.png') ?>">
    </div>

    <div class="form-group">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-textarea" rows="3" placeholder="Category introduction and characteristics..."><?= e($category['description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label class="form-label">Sort Order</label>
      <input type="number" name="sort_order" class="form-input" value="<?= e($category['sort_order'] ?? 0) ?>">
    </div>

    <div class="form-group">
      <label style="display: flex; align-items: center; gap: 10px; cursor:pointer;">
        <input type="checkbox" name="is_active" value="1" <?= (!isset($category['is_active']) || $category['is_active'] == 1) ? 'checked' : '' ?> style="width:18px; height:18px; accent-color: var(--gold);">
        <span style="font-size: 14px; font-weight: 600;">Active in Navigation & Boutique</span>
      </label>
    </div>

    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 24px;">
      <?= $isEdit ? 'Update Category' : 'Save Category' ?>
    </button>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const name = document.getElementById('cat-name');
  const slug = document.getElementById('cat-slug');
  if (name && slug && !slug.value) {
    name.addEventListener('input', () => {
      slug.value = name.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    });
  }
});
</script>
