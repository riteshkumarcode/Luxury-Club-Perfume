<?php
// app/views/pages/checkout.php
use App\Core\View;

/** @var array $items */
/** @var array $totals */
/** @var array $errors */
/** @var array $formData */

$indianStates = [
    "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat",
    "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh",
    "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab",
    "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh",
    "Uttarakhand", "West Bengal", "Delhi", "Jammu & Kashmir", "Ladakh", "Puducherry", "Chandigarh"
];

$razorpayKey = config('razorpay.key_id');
?>

<section class="section" style="padding-top: 48px;">
  <div class="container">
    
    <?= View::partial('partials/breadcrumb', ['items' => [
      ['label' => 'Home', 'url' => '/'],
      ['label' => 'Shopping Bag', 'url' => '/cart'],
      ['label' => 'Checkout']
    ]]) ?>

    <h1 style="font-size: 38px; margin-bottom: 32px;">Atelier Checkout</h1>

    <?php if (empty($items)): ?>
      <div style="text-align: center; padding: 60px 20px; background: var(--sand); border-radius: 18px;">
        <h3>Your Bag is Empty</h3>
        <p style="margin: 12px 0 24px;">Please select fragrances before proceeding to checkout.</p>
        <a href="<?= url('/shop') ?>" class="btn btn-dark">Return to Boutique</a>
      </div>
    <?php else: ?>

      <div class="checkout-grid">
        
        <!-- Left Column: Checkout Shipping & Payment Form -->
        <div>
          
          <form id="checkout-form" action="<?= url('/checkout') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Contact Information -->
            <div style="background: #ffffff; border: 1px solid var(--line); border-radius: var(--radius-card); padding: 32px; margin-bottom: 28px;">
              <h3 style="font-size: 20px; margin-bottom: 20px;">1. Contact Details</h3>
              
              <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="customer_name" class="form-input" value="<?= e($formData['customer_name'] ?? '') ?>" placeholder="e.g. Vikramaditya Rao" required>
                <?php if (!empty($errors['customer_name'])): ?>
                  <div class="form-error-msg"><?= e($errors['customer_name'][0]) ?></div>
                <?php endif; ?>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                  <label class="form-label">Email Address *</label>
                  <input type="email" name="email" class="form-input" value="<?= e($formData['email'] ?? '') ?>" placeholder="you@example.com" required>
                  <?php if (!empty($errors['email'])): ?>
                    <div class="form-error-msg"><?= e($errors['email'][0]) ?></div>
                  <?php endif; ?>
                </div>

                <div class="form-group">
                  <label class="form-label">Phone (+91 Mobile) *</label>
                  <input type="tel" name="phone" class="form-input" value="<?= e($formData['phone'] ?? '') ?>" placeholder="9876543210" required>
                  <?php if (!empty($errors['phone'])): ?>
                    <div class="form-error-msg"><?= e($errors['phone'][0]) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Shipping Address -->
            <div style="background: #ffffff; border: 1px solid var(--line); border-radius: var(--radius-card); padding: 32px; margin-bottom: 28px;">
              <h3 style="font-size: 20px; margin-bottom: 20px;">2. Shipping Destination</h3>

              <div class="form-group">
                <label class="form-label">Address Line 1 *</label>
                <input type="text" name="address_line1" class="form-input" value="<?= e($formData['address_line1'] ?? '') ?>" placeholder="House / Flat / Building / Street" required>
                <?php if (!empty($errors['address_line1'])): ?>
                  <div class="form-error-msg"><?= e($errors['address_line1'][0]) ?></div>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label class="form-label">Address Line 2 (Apartment, Suite, Landmark)</label>
                <input type="text" name="address_line2" class="form-input" value="<?= e($formData['address_line2'] ?? '') ?>" placeholder="Landmark or Area (optional)">
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                  <label class="form-label">City *</label>
                  <input type="text" name="city" class="form-input" value="<?= e($formData['city'] ?? '') ?>" placeholder="e.g. Mumbai" required>
                  <?php if (!empty($errors['city'])): ?>
                    <div class="form-error-msg"><?= e($errors['city'][0]) ?></div>
                  <?php endif; ?>
                </div>

                <div class="form-group">
                  <label class="form-label">State *</label>
                  <select name="state" class="form-select" required>
                    <option value="">Select State</option>
                    <?php foreach ($indianStates as $st): ?>
                      <option value="<?= e($st) ?>" <?= ($formData['state'] ?? '') === $st ? 'selected' : '' ?>><?= e($st) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (!empty($errors['state'])): ?>
                    <div class="form-error-msg"><?= e($errors['state'][0]) ?></div>
                  <?php endif; ?>
                </div>

                <div class="form-group">
                  <label class="form-label">PIN Code *</label>
                  <input type="text" name="pincode" class="form-input" value="<?= e($formData['pincode'] ?? '') ?>" placeholder="6-digit PIN" maxlength="6" required>
                  <?php if (!empty($errors['pincode'])): ?>
                    <div class="form-error-msg"><?= e($errors['pincode'][0]) ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Special Delivery / Concierge Notes</label>
                <textarea name="notes" class="form-textarea" rows="2" placeholder="e.g. Call before delivery, gift message instructions"><?= e($formData['notes'] ?? '') ?></textarea>
              </div>
            </div>

            <!-- Payment Method Selection -->
            <div style="background: #ffffff; border: 1px solid var(--line); border-radius: var(--radius-card); padding: 32px; margin-bottom: 28px;">
              <h3 style="font-size: 20px; margin-bottom: 20px;">3. Payment Method</h3>

              <div style="display: flex; flex-direction: column; gap: 14px;">
                
                <label style="display: flex; align-items: center; gap: 14px; padding: 16px; border: 1px solid var(--gold); border-radius: 14px; background: rgba(216, 178, 92, 0.06); cursor: pointer;">
                  <input type="radio" name="payment_method" value="razorpay" checked style="accent-color: var(--gold-deep); width: 18px; height: 18px;">
                  <div>
                    <div style="font-weight: 700; font-size: 15px; color: var(--ink);">Online Payment (UPI, Cards, Netbanking)</div>
                    <div style="font-size: 12px; color: var(--muted);">Instant, secure 256-bit encrypted checkout via Razorpay.</div>
                  </div>
                </label>

                <?php if (get_setting('enable_cod', '1') === '1'): ?>
                  <label style="display: flex; align-items: center; gap: 14px; padding: 16px; border: 1px solid var(--line); border-radius: 14px; cursor: pointer;">
                    <input type="radio" name="payment_method" value="cod" style="accent-color: var(--gold-deep); width: 18px; height: 18px;">
                    <div>
                      <div style="font-weight: 700; font-size: 15px; color: var(--ink);">Cash on Delivery (COD)</div>
                      <div style="font-size: 12px; color: var(--muted);">Pay in cash or UPI upon delivery at your doorstep.</div>
                    </div>
                  </label>
                <?php endif; ?>

              </div>
            </div>

            <!-- Hidden Fields for Razorpay Integration -->
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">

            <button type="submit" id="btn-submit-order" class="btn btn-primary btn-block" style="padding: 18px 36px; font-size: 15px;" data-magnetic>
              Place Order · <?= formatInr($totals['total']) ?> <?= icon('arrow-right') ?>
            </button>

          </form>

        </div>

        <!-- Right Column: Sticky Order Summary Card -->
        <div class="order-summary-card">
          <h3 style="font-size: 22px; margin-bottom: 20px;">Order Breakdown</h3>

          <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px; max-height: 280px; overflow-y: auto;">
            <?php foreach ($items as $item): ?>
              <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 54px; height: 54px; border-radius: 12px; background: <?= e($item['tint']) ?>; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <img src="<?= asset('/assets/img/products/' . $item['image']) ?>" alt="<?= e($item['name']) ?>" style="width: 80%; height: 80%; object-fit: contain; mix-blend-mode: multiply;">
                </div>
                <div style="flex: 1; font-size: 13px;">
                  <div style="font-weight: 600; color: var(--ink);"><?= e($item['name']) ?></div>
                  <div style="color: var(--muted);"><?= e($item['size_label']) ?> × <?= $item['qty'] ?></div>
                </div>
                <div style="font-weight: 700; font-size: 14px; color: var(--ink);">
                  <?= formatInr($item['line_total']) ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="summary-row">
            <span>Subtotal</span>
            <span><?= formatInr($totals['subtotal']) ?></span>
          </div>

          <div class="summary-row">
            <span>Express Shipping</span>
            <span><?= $totals['shipping'] > 0 ? formatInr($totals['shipping']) : '<strong style="color:var(--success);">Complimentary</strong>' ?></span>
          </div>

          <div class="summary-total-row">
            <span>Grand Total</span>
            <span style="color: var(--gold-text);"><?= formatInr($totals['total']) ?></span>
          </div>

          <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--line); font-size: 12px; color: var(--muted); display:flex; flex-direction:column; gap:8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <span style="color: var(--gold);"><?= icon('shield') ?></span> 256-Bit SSL Encrypted Transaction
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <span style="color: var(--gold);"><?= icon('gift') ?></span> Complimentary Signature Gift Packaging
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <span style="color: var(--gold);"><?= icon('truck') ?></span> Dispatched within 24 business hours
            </div>
          </div>

        </div>

      </div>

    <?php endif; ?>

  </div>
</section>

<!-- Razorpay Checkout Script (Stub/Production Ready) -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('checkout-form');
  const btnSubmit = document.getElementById('btn-submit-order');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    const paymentMethod = form.querySelector('input[name="payment_method"]:checked')?.value || 'razorpay';
    
    // If COD, submit form directly to create order
    if (paymentMethod === 'cod') {
      return true;
    }

    // If online payment (Razorpay)
    const razorpayPaymentId = document.getElementById('razorpay_payment_id').value;
    if (razorpayPaymentId) {
      // Already paid via modal callback, let submission proceed
      return true;
    }

    e.preventDefault();

    // Check basic form validity
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    btnSubmit.disabled = true;
    btnSubmit.textContent = 'Processing Secure Checkout...';

    // If Razorpay Key is placeholder, simulate seamless checkout or open Razorpay
    const keyId = '<?= e($razorpayKey) ?>';

    if (!keyId || keyId.startsWith('rzp_test_')) {
      // Create order via standard submission with simulated gateway confirmation
      document.getElementById('razorpay_payment_id').value = 'pay_simulated_' + Math.random().toString(36).substring(2, 10);
      document.getElementById('razorpay_signature').value = 'sig_simulated_' + Math.random().toString(36).substring(2, 10);
      form.submit();
      return;
    }

    const options = {
      key: keyId,
      amount: <?= $totals['total'] * 100 ?>, // In paise
      currency: "INR",
      name: "Luxury Club",
      description: "Artisanal Fragrance Purchase",
      image: "<?= url('/assets/img/logo.png') ?>",
      handler: function (response) {
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature || '';
        form.submit();
      },
      prefill: {
        name: form.customer_name.value,
        email: form.email.value,
        contact: form.phone.value
      },
      theme: {
        color: "#0D0B08"
      },
      modal: {
        ondismiss: function() {
          btnSubmit.disabled = false;
          btnSubmit.textContent = 'Place Order · <?= formatInr($totals['total']) ?>';
        }
      }
    };

    const rzp = new Razorpay(options);
    rzp.open();
  });
});
</script>
