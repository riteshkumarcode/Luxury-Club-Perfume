<?php
// app/views/pages/contact.php
use App\Core\View;

$phone = get_setting('phone', '+91 98765 43210');
$whatsapp = get_setting('whatsapp', '+91 98765 43210');
$email = get_setting('email', 'concierge@luxuryclub.com');
$address = get_setting('address', 'Luxury Club Atelier, Suite 402, Signature Towers, Mumbai, Maharashtra 400001');
$hours = get_setting('hours', 'Monday – Saturday: 10:00 AM – 8:00 PM IST');
$instagram = get_setting('instagram_url', 'https://instagram.com/luxuryclub');
$facebook = get_setting('facebook_url', 'https://facebook.com/luxuryclub');
$cleanWa = preg_replace('/[^\d]/', '', $whatsapp);
?>

<!-- Dark Hero -->
<section class="section section-dark" style="padding-top: 80px; padding-bottom: 70px;">
  <div class="container">
    <?= View::partial('partials/breadcrumb', ['items' => [
      ['label' => 'Home', 'url' => '/'],
      ['label' => 'Contact Us']
    ]]) ?>
    <span class="eyebrow eyebrow-light">CONTACT US</span>
    <h1 style="color: var(--cream); margin-bottom: 16px;">We'd love to hear from you</h1>
    <p style="color: var(--muted-dark); max-width: 640px; font-size: 17px;">
      Whether you need personal fragrance consultation, corporate gifting curations, order updates or custom event favours, our atelier concierge is at your service.
    </p>
  </div>
</section>

<!-- Contact Grid -->
<section class="section">
  <div class="container">
    <div class="contact-grid">
      
      <!-- Left Column: Contact Cards -->
      <div>
        <span class="eyebrow">CONCIERGE TOUCHPOINTS</span>
        <h2 style="font-size: 32px; margin-bottom: 28px;">Connect with our atelier</h2>

        <div style="display: flex; flex-direction: column; gap: 20px;">
          
          <div class="contact-card-box" style="padding: 24px;">
            <h4 style="font-size: 16px; margin-bottom: 6px; color: var(--ink);">Call & WhatsApp</h4>
            <p style="font-size: 14px; margin-bottom: 12px;"><?= e($hours) ?></p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
              <a href="https://wa.me/<?= $cleanWa ?>" target="_blank" rel="noopener" class="btn btn-sm btn-dark" style="background:#25D366; color:#ffffff;">
                <?= icon('whatsapp') ?> Chat on WhatsApp
              </a>
              <a href="tel:<?= e($phone) ?>" class="btn btn-sm btn-outline">
                Call Direct
              </a>
            </div>
          </div>

          <div class="contact-card-box" style="padding: 24px;">
            <h4 style="font-size: 16px; margin-bottom: 6px; color: var(--ink);">Email Concierge</h4>
            <p style="font-size: 14px; margin-bottom: 12px;">We respond to all inquiries within 12 business hours.</p>
            <a href="mailto:<?= e($email) ?>" style="font-weight: 700; color: var(--gold-text); font-size: 16px;">
              <?= e($email) ?> &rarr;
            </a>
          </div>

          <div class="contact-card-box" style="padding: 24px;">
            <h4 style="font-size: 16px; margin-bottom: 6px; color: var(--ink);">Atelier Location</h4>
            <p style="font-size: 14px; margin-bottom: 14px; line-height: 1.5;"><?= e($address) ?></p>
            <div style="font-size: 12px; font-weight: 700; color: var(--gold-text); text-transform: uppercase;">
              Visits by prior appointment
            </div>
          </div>

        </div>
      </div>

      <!-- Right Column: Interactive Form Card -->
      <div class="contact-card-box">
        
        <!-- Form State -->
        <form class="contact-form" action="<?= url('/api/contact') ?>" method="POST" novalidate>
          <?= csrf_field() ?>
          <!-- Honeypot -->
          <input type="text" name="website_hp" style="display:none;" tabindex="-1" autocomplete="off">

          <h3 style="font-size: 26px; margin-bottom: 24px;">Send a Message</h3>

          <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-input" placeholder="e.g. Maharani Gayatri Devi" required>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" class="form-input" placeholder="you@example.com" required>
            </div>
            <div class="form-group">
              <label class="form-label">Phone Number (Optional)</label>
              <input type="tel" name="phone" class="form-input" placeholder="+91 98765 43210">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Inquiry Topic *</label>
            <select name="topic" class="form-select" required>
              <option value="">Select a topic</option>
              <option value="Order support">Order support & tracking</option>
              <option value="Help choosing a fragrance">Help choosing a fragrance</option>
              <option value="Gifting & corporate orders">Gifting & corporate bulk orders</option>
              <option value="Wholesale">Wholesale & boutique stockist</option>
              <option value="Something else">Something else</option>
            </select>
          </div>

          <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: baseline;">
              <label class="form-label">Your Message *</label>
              <span class="char-counter" style="font-size: 11px; color: var(--muted);">0/1000</span>
            </div>
            <textarea name="message" class="form-textarea" rows="4" placeholder="How may our concierge assist you today?" required minlength="10" maxlength="1000"></textarea>
          </div>

          <button type="submit" class="btn btn-primary btn-block" data-magnetic>
            Send Message <?= icon('arrow-right') ?>
          </button>
        </form>

        <!-- Animated Success State (Hidden by default) -->
        <div class="contact-success-state" style="display: none; text-align: center; padding: 40px 20px;">
          <div style="width: 64px; height: 64px; border-radius: 50%; background-color: var(--gold); color: var(--ink); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
            ✓
          </div>
          <h3 style="font-size: 28px; margin-bottom: 12px;">Thank you, <span class="contact-success-name">Friend</span></h3>
          <p style="font-size: 15px; line-height: 1.6; margin-bottom: 24px; color: var(--muted);">
            Your message has been received by our atelier concierge. We have sent an acknowledgement to your email and will respond promptly.
          </p>
          <button type="button" class="btn btn-dark btn-sm" onclick="location.reload()">
            Send Another Message
          </button>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- Google Maps Embed -->
<section style="background: var(--sand); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); padding: 0;">
  <div style="width: 100%; height: 360px; overflow: hidden; filter: grayscale(80%) contrast(1.1);">
    <iframe 
      title="Luxury Club Atelier Map"
      width="100%" 
      height="100%" 
      frameborder="0" 
      scrolling="no" 
      marginheight="0" 
      marginwidth="0" 
      src="https://maps.google.com/maps?q=Mumbai,Maharashtra,India&t=&z=13&ie=UTF8&iwloc=&output=embed"
      loading="lazy">
    </iframe>
  </div>
</section>

<!-- FAQ Accordion -->
<section class="section">
  <div class="container" style="max-width: 800px;">
    <div style="text-align: center; margin-bottom: 40px;">
      <span class="eyebrow">COMMON QUERIES</span>
      <h2>Frequently Asked Questions</h2>
    </div>

    <div class="pdp-accordions">
      
      <div class="accordion-item active">
        <button type="button" class="accordion-header">
          <span>What is a roll-on attar?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>An attar is a 100% alcohol-free concentrated perfume oil derived from natural botanicals, flowers, and precious resins like agarwood. Because it contains zero alcohol, it doesn't evaporate quickly—instead, it gently warms on your skin and releases an intimate scent trail for 12+ hours.</p>
        </div>
      </div>

      <div class="accordion-item">
        <button type="button" class="accordion-header">
          <span>How is Eau de Parfum different from Attar?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>Our Eau de Parfum (100 ml) features a spray mist flacon offering greater sillage (projection around you). Attars are concentrated perfume oils applied via roll-on directly to pulse points for a subtle, long-lasting personal scent bubble.</p>
        </div>
      </div>

      <div class="accordion-item">
        <button type="button" class="accordion-header">
          <span>How do I use the car hanging pod?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>Unscrew the wooden cap and remove the plastic stopper. Screw the beechwood cap back on tightly, tip the bottle upside down for 2–3 seconds so the wood absorbs the fragrance oil, and hang it from your rear-view mirror with the braided gold cord.</p>
        </div>
      </div>

      <div class="accordion-item">
        <button type="button" class="accordion-header">
          <span>Do you deliver across India?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>Yes, we deliver to over 19,000+ PIN codes across India via premium courier partners. Orders above ₹999 receive complimentary express shipping.</p>
        </div>
      </div>

      <div class="accordion-item">
        <button type="button" class="accordion-header">
          <span>Can I order in bulk for gifting or corporate events?</span>
          <div class="accordion-icon"><?= icon('chevron-down') ?></div>
        </button>
        <div class="accordion-body">
          <p>Absolutely. We offer customized luxury gift boxes, bespoke wax seals, and curated discovery sets for weddings, corporate gifting, and private events. Please select "Gifting & corporate orders" in the contact form above.</p>
        </div>
      </div>

    </div>
  </div>
</section>
