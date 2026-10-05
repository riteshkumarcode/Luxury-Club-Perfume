<?php
// app/views/admin/settings.php
/** @var array $settings */

$announcementMessages = json_decode($settings['announcement_messages'] ?? '[]', true) ?: [
    "COMPLIMENTARY SHIPPING ON ORDERS OVER ₹999",
    "ARTISANAL ALCOHOL-FREE ATTARS & PURE PARFUMS",
    "EXQUISITE GIFT BOX PACKAGING WITH EVERY ORDER",
    "DISPATCHED WITHIN 24 HOURS ACROSS INDIA"
];
$announcementRaw = implode("\n", $announcementMessages);
?>

<div style="margin-bottom: 24px;">
  <h3 style="font-size: 24px;">Store Settings & Configuration</h3>
</div>

<form action="<?= url('/admin/settings') ?>" method="POST">
  <?= csrf_field() ?>

  <div class="admin-form-grid">
    
    <div style="display: flex; flex-direction: column; gap: 24px;">
      
      <!-- Shipping & Commerce -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 16px;">Shipping & Commerce Settings</h4>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="form-group">
            <label class="form-label">Free Shipping Threshold (INR ₹) *</label>
            <input type="number" name="free_shipping_threshold" class="form-input" value="<?= e($settings['free_shipping_threshold'] ?? '999') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label">Standard Shipping Fee (INR ₹) *</label>
            <input type="number" name="shipping_fee" class="form-input" value="<?= e($settings['shipping_fee'] ?? '99') ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Estimated Delivery Promise Text</label>
          <input type="text" name="delivery_estimate_text" class="form-input" value="<?= e($settings['delivery_estimate_text'] ?? '2–4 business days across major metros; 4–6 days rest of India.') ?>">
        </div>

        <div class="form-group">
          <label style="display: flex; align-items: center; gap: 10px; cursor:pointer;">
            <input type="hidden" name="enable_cod" value="0">
            <input type="checkbox" name="enable_cod" value="1" <?= ($settings['enable_cod'] ?? '1') === '1' ? 'checked' : '' ?> style="width:18px; height:18px; accent-color: var(--gold);">
            <span style="font-size: 14px; font-weight: 600;">Enable Cash on Delivery (COD) Option</span>
          </label>
        </div>
      </div>

      <!-- Contact & Atelier Touchpoints -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 16px;">Atelier Concierge Contact</h4>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="form-group">
            <label class="form-label">Contact Phone</label>
            <input type="text" name="phone" class="form-input" value="<?= e($settings['phone'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">WhatsApp Number (+91 format)</label>
            <input type="text" name="whatsapp" class="form-input" value="<?= e($settings['whatsapp'] ?? '') ?>">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="form-group">
            <label class="form-label">Concierge Email</label>
            <input type="email" name="email" class="form-input" value="<?= e($settings['email'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Operating Hours</label>
            <input type="text" name="hours" class="form-input" value="<?= e($settings['hours'] ?? 'Monday – Saturday: 10:00 AM – 8:00 PM IST') ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Atelier Physical Address</label>
          <textarea name="address" class="form-textarea" rows="2"><?= e($settings['address'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Policy Texts -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 16px;">Client Policy Text</h4>

        <div class="form-group">
          <label class="form-label">Shipping Policy</label>
          <textarea name="policy_shipping" class="form-textarea" rows="3"><?= e($settings['policy_shipping'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Returns Policy</label>
          <textarea name="policy_returns" class="form-textarea" rows="3"><?= e($settings['policy_returns'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Privacy Policy</label>
          <textarea name="policy_privacy" class="form-textarea" rows="3"><?= e($settings['policy_privacy'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Terms of Service</label>
          <textarea name="policy_terms" class="form-textarea" rows="3"><?= e($settings['policy_terms'] ?? '') ?></textarea>
        </div>
      </div>

    </div>

    <!-- Right Column: Announcements, Razorpay, Socials -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
      
      <!-- Announcement Bar Strip Messages -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 16px;">Announcement Strip Messages</h4>
        <p style="font-size: 12px; color: var(--muted); margin-bottom: 10px;">Enter one message per line. They will cycle infinitely in the top marquee strip.</p>
        <div class="form-group">
          <textarea name="announcement_messages_raw" class="form-textarea" rows="5"><?= e($announcementRaw) ?></textarea>
        </div>
      </div>

      <!-- Payment Gateway Keys -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 16px;">Payment Credentials</h4>
        
        <div class="form-group">
          <label class="form-label">Razorpay Key ID</label>
          <input type="text" name="razorpay_key_id" class="form-input" value="<?= e($settings['razorpay_key_id'] ?? '') ?>" placeholder="rzp_live_...">
          <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">Note: The Key Secret is stored securely in <code>.env</code>.</div>
        </div>
      </div>

      <!-- Social Media URLs -->
      <div class="admin-card">
        <h4 style="font-size: 16px; margin-bottom: 16px;">Social Channels</h4>

        <div class="form-group">
          <label class="form-label">Instagram URL</label>
          <input type="url" name="instagram_url" class="form-input" value="<?= e($settings['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/luxuryclub">
        </div>

        <div class="form-group">
          <label class="form-label">Facebook URL</label>
          <input type="url" name="facebook_url" class="form-input" value="<?= e($settings['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/luxuryclub">
        </div>

        <div class="form-group">
          <label class="form-label">YouTube URL</label>
          <input type="url" name="youtube_url" class="form-input" value="<?= e($settings['youtube_url'] ?? '') ?>" placeholder="https://youtube.com/luxuryclub">
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 20px;">
          Save All Settings
        </button>
      </div>

    </div>

  </div>
</form>
