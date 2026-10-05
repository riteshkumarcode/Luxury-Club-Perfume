<?php
// app/views/admin/products/form.php
/** @var ?array $product */
/** @var array $categories */
/** @var array $errors */

$isEdit = !empty($product['id']);
$actionUrl = $isEdit ? url('/admin/products/edit/' . $product['id']) : url('/admin/products/create');
$currentTint = $product['tint'] ?? '#F3EDE2';
$currentImage = $product['image'] ?? 'blue-orchid.png';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <a href="<?= url('/admin/products') ?>" style="font-size: 13px; color: var(--muted); text-decoration: underline;">
      &larr; Back to Products
    </a>
    <h3 style="font-size: 24px; margin-top: 4px;"><?= $isEdit ? 'Edit Fragrance Flacon' : 'Create New Fragrance' ?></h3>
  </div>
</div>

<form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="admin-form-grid">
    
    <!-- Left Column: Core Fields -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
      
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 20px;">Primary Details</h4>

        <div class="form-group">
          <label class="form-label">Category *</label>
          <select name="category_id" class="form-select" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                <?= e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (!empty($errors['category_id'])): ?><div class="form-error-msg"><?= e($errors['category_id'][0]) ?></div><?php endif; ?>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="form-group">
            <label class="form-label">Fragrance Name *</label>
            <input type="text" name="name" id="prod-name-input" class="form-input" value="<?= e($product['name'] ?? '') ?>" placeholder="e.g. Royal Oudh" required>
            <?php if (!empty($errors['name'])): ?><div class="form-error-msg"><?= e($errors['name'][0]) ?></div><?php endif; ?>
          </div>

          <div class="form-group">
            <label class="form-label">URL Slug *</label>
            <input type="text" name="slug" id="prod-slug-input" class="form-input" value="<?= e($product['slug'] ?? '') ?>" placeholder="e.g. royal-oudh" required>
            <?php if (!empty($errors['slug'])): ?><div class="form-error-msg"><?= e($errors['slug'][0]) ?></div><?php endif; ?>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
          <div class="form-group">
            <label class="form-label">Size Label *</label>
            <input type="text" name="size_label" class="form-input" value="<?= e($product['size_label'] ?? '100 ml') ?>" placeholder="e.g. 100 ml / 10 ml" required>
          </div>

          <div class="form-group">
            <label class="form-label">Price (INR ₹) *</label>
            <input type="number" name="price" class="form-input" value="<?= e($product['price'] ?? '') ?>" placeholder="1499" required>
          </div>

          <div class="form-group">
            <label class="form-label">Compare-at Price (INR ₹)</label>
            <input type="number" name="compare_at_price" class="form-input" value="<?= e($product['compare_at_price'] ?? '') ?>" placeholder="1899">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Badge Chip</label>
          <select name="badge" class="form-select">
            <option value="">None</option>
            <option value="Bestseller" <?= ($product['badge'] ?? '') === 'Bestseller' ? 'selected' : '' ?>>Bestseller</option>
            <option value="New" <?= ($product['badge'] ?? '') === 'New' ? 'selected' : '' ?>>New</option>
            <option value="Gift pick" <?= ($product['badge'] ?? '') === 'Gift pick' ? 'selected' : '' ?>>Gift pick</option>
            <option value="Limited" <?= ($product['badge'] ?? '') === 'Limited' ? 'selected' : '' ?>>Limited</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Short Description</label>
          <textarea name="short_description" class="form-textarea" rows="2" placeholder="Brief 1-2 sentence overview for cards and PDP header..."><?= e($product['short_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Full Olfactory Description</label>
          <textarea name="description" class="form-textarea" rows="4" placeholder="Detailed character, mood, story and artisanal notes..."><?= e($product['description'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Fragrance Pyramid Notes & Ritual -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 20px;">Fragrance Pyramid & Ritual</h4>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
          <div class="form-group">
            <label class="form-label">Top Notes</label>
            <input type="text" name="notes_top" class="form-input" value="<?= e($product['notes_top'] ?? '') ?>" placeholder="e.g. Bergamot, Dewy Greens">
          </div>

          <div class="form-group">
            <label class="form-label">Heart Notes</label>
            <input type="text" name="notes_heart" class="form-input" value="<?= e($product['notes_heart'] ?? '') ?>" placeholder="e.g. Midnight Orchid, Jasmine">
          </div>

          <div class="form-group">
            <label class="form-label">Base Notes</label>
            <input type="text" name="notes_base" class="form-input" value="<?= e($product['notes_base'] ?? '') ?>" placeholder="e.g. Amber, Sandalwood">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">How to Use (Ritual Instructions)</label>
          <textarea name="how_to_use" class="form-textarea" rows="2" placeholder="e.g. Roll onto wrists, neck and behind the ears..."><?= e($product['how_to_use'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Tags (comma separated)</label>
          <input type="text" name="tags" class="form-input" value="<?= e($product['tags'] ?? '') ?>" placeholder="floral, woody, evening, bestseller">
        </div>
      </div>

      <!-- SEO Meta Fields -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 20px;">Search Engine Optimization (SEO)</h4>

        <div class="form-group">
          <label class="form-label">Custom Meta Title</label>
          <input type="text" name="meta_title" class="form-input" value="<?= e($product['meta_title'] ?? '') ?>" placeholder="Leave blank to use default template">
        </div>

        <div class="form-group">
          <label class="form-label">Custom Meta Description</label>
          <textarea name="meta_description" class="form-textarea" rows="2" placeholder="Leave blank to use short description"><?= e($product['meta_description'] ?? '') ?></textarea>
        </div>
      </div>

    </div>

    <!-- Right Column: Media, Tints, Inventory, Publishing -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
      
      <!-- Media & Tint Frame -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 16px;">Product Flacon Image</h4>
        
        <input type="hidden" name="existing_image" value="<?= e($currentImage) ?>">

        <div class="form-group">
          <label class="form-label">Upload New Photo (JPG/PNG/WebP, max 3MB)</label>
          <input type="file" name="image_file" class="form-input" accept="image/*" id="prod-img-upload">
        </div>

        <div class="form-group">
          <label class="form-label">Panel Soft Tint (Hex)</label>
          <div style="display: flex; gap: 10px; align-items: center;">
            <input type="color" id="tint-color-picker" value="<?= e($currentTint) ?>" style="width: 44px; height: 44px; border:none; border-radius: 8px; cursor:pointer;">
            <input type="text" name="tint" id="tint-hex-input" class="form-input" value="<?= e($currentTint) ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Hero Section Background (Optional Hex)</label>
          <input type="text" name="hero_bg" class="form-input" value="<?= e($product['hero_bg'] ?? '') ?>" placeholder="e.g. #0E1633">
        </div>

        <!-- Live Tinted Preview -->
        <label class="form-label" style="margin-top: 12px;">Live Blend Preview</label>
        <div class="img-preview-panel" id="tint-preview-box" style="background-color: <?= e($currentTint) ?>;">
          <img src="<?= asset('/assets/img/products/' . $currentImage) ?>" id="tint-preview-img" alt="Preview">
        </div>
      </div>

      <!-- Inventory & Visibility -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 16px;">Inventory & Status</h4>

        <div class="form-group">
          <label class="form-label">Stock Quantity *</label>
          <input type="number" name="stock_qty" class="form-input" value="<?= e($product['stock_qty'] ?? 50) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Sort Display Order</label>
          <input type="number" name="sort_order" class="form-input" value="<?= e($product['sort_order'] ?? 0) ?>">
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 16px;">
          <label style="display: flex; align-items: center; gap: 10px; cursor:pointer;">
            <input type="checkbox" name="is_featured" value="1" <?= (!empty($product['is_featured'])) ? 'checked' : '' ?> style="width:18px; height:18px; accent-color: var(--gold);">
            <span style="font-size: 14px; font-weight: 600;">Featured on Home Collection</span>
          </label>

          <label style="display: flex; align-items: center; gap: 10px; cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" <?= (!isset($product['is_active']) || $product['is_active'] == 1) ? 'checked' : '' ?> style="width:18px; height:18px; accent-color: var(--gold);">
            <span style="font-size: 14px; font-weight: 600;">Active & Available in Store</span>
          </label>
        </div>

        <div style="margin-top: 28px;">
          <button type="submit" class="btn btn-primary btn-block">
            <?= $isEdit ? 'Update Fragrance' : 'Publish Fragrance' ?>
          </button>
        </div>
      </div>

    </div>

  </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const nameInput = document.getElementById('prod-name-input');
  const slugInput = document.getElementById('prod-slug-input');
  const tintPicker = document.getElementById('tint-color-picker');
  const tintHex = document.getElementById('tint-hex-input');
  const previewBox = document.getElementById('tint-preview-box');
  const fileUpload = document.getElementById('prod-img-upload');
  const previewImg = document.getElementById('tint-preview-img');

  // Auto slugify if slug empty
  if (nameInput && slugInput && !slugInput.value) {
    nameInput.addEventListener('input', () => {
      slugInput.value = nameInput.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    });
  }

  // Live tint sync
  if (tintPicker && tintHex && previewBox) {
    tintPicker.addEventListener('input', () => {
      tintHex.value = tintPicker.value.toUpperCase();
      previewBox.style.backgroundColor = tintPicker.value;
    });
    tintHex.addEventListener('input', () => {
      tintPicker.value = tintHex.value;
      previewBox.style.backgroundColor = tintHex.value;
    });
  }

  // Image upload preview
  if (fileUpload && previewImg) {
    fileUpload.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (re) => {
          previewImg.src = re.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }
});
</script>
