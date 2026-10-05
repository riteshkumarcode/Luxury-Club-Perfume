<?php
// app/views/admin/slides/form.php
/** @var ?array $slide */
/** @var array $products */
/** @var array $errors */

$isEdit = !empty($slide['id']);
$actionUrl = $isEdit ? url('/admin/slides/edit/' . $slide['id']) : url('/admin/slides/create');
?>

<div style="margin-bottom: 24px;">
  <a href="<?= url('/admin/slides') ?>" style="font-size: 13px; color: var(--muted); text-decoration: underline;">
    &larr; Back to Hero Slides
  </a>
  <h3 style="font-size: 24px; margin-top: 4px;"><?= $isEdit ? 'Edit Hero Slide' : 'Add Hero Slide' ?></h3>
</div>

<div class="admin-card" style="max-width: 700px;">
  <form action="<?= $actionUrl ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group">
      <label class="form-label">Linked Product (Optional)</label>
      <select name="product_id" class="form-select">
        <option value="">None (Custom Promotion)</option>
        <?php foreach ($products as $p): ?>
          <option value="<?= $p['id'] ?>" <?= ($slide['product_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
            <?= e($p['name']) ?> (<?= e($p['size_label']) ?> · <?= formatInr($p['price']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label">Eyebrow (e.g. EAU DE PARFUM · 100 ML) *</label>
      <input type="text" name="eyebrow" class="form-input" value="<?= e($slide['eyebrow'] ?? '') ?>" required>
    </div>

    <div class="form-group">
      <label class="form-label">Headline Title (use &lt;br&gt; for line break) *</label>
      <input type="text" name="title" class="form-input" value="<?= e($slide['title'] ?? '') ?>" placeholder="Blue&lt;br&gt;Orchid" required>
    </div>

    <div class="form-group">
      <label class="form-label">Subline Description *</label>
      <textarea name="subline" class="form-textarea" rows="2" required><?= e($slide['subline'] ?? '') ?></textarea>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group">
        <label class="form-label">Primary CTA Label</label>
        <input type="text" name="cta_label" class="form-input" value="<?= e($slide['cta_label'] ?? 'Explore Now') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Primary CTA URL</label>
        <input type="text" name="cta_url" class="form-input" value="<?= e($slide['cta_url'] ?? '/shop') ?>">
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group">
        <label class="form-label">Secondary CTA Label (Optional)</label>
        <input type="text" name="cta2_label" class="form-input" value="<?= e($slide['cta2_label'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Secondary CTA URL</label>
        <input type="text" name="cta2_url" class="form-input" value="<?= e($slide['cta2_url'] ?? '') ?>">
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
      <div class="form-group">
        <label class="form-label">Image Filename *</label>
        <input type="text" name="image" class="form-input" value="<?= e($slide['image'] ?? 'blue-orchid.png') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Arch Tint (Hex)</label>
        <input type="text" name="tint" class="form-input" value="<?= e($slide['tint'] ?? '#E3E7F3') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Section Bg Color (Hex)</label>
        <input type="text" name="bg_color" class="form-input" value="<?= e($slide['bg_color'] ?? '#0E1633') ?>">
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group">
        <label class="form-label">Giant Outline Word *</label>
        <input type="text" name="outline_word" class="form-input" value="<?= e($slide['outline_word'] ?? 'Luxury') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-input" value="<?= e($slide['sort_order'] ?? 0) ?>">
      </div>
    </div>

    <div class="form-group">
      <label style="display: flex; align-items: center; gap: 10px; cursor:pointer;">
        <input type="checkbox" name="is_active" value="1" <?= (!isset($slide['is_active']) || $slide['is_active'] == 1) ? 'checked' : '' ?> style="width:18px; height:18px; accent-color: var(--gold);">
        <span style="font-size: 14px; font-weight: 600;">Active in Hero Slider</span>
      </label>
    </div>

    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 24px;">
      <?= $isEdit ? 'Update Slide' : 'Add Slide' ?>
    </button>
  </form>
</div>
