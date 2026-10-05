<?php
// app/views/partials/newsletter.php
?>
<section class="newsletter-band" aria-label="Newsletter Subscription">
  <div class="container newsletter-inner">
    <div class="eyebrow" style="color: var(--ink);">THE CONCIERGE CIRCLE</div>
    <h2>Join the Club</h2>
    <p>Receive private invitations to limited olfactory editions, private masterclasses, and complimentary express shipping privileges.</p>

    <form class="newsletter-form" action="<?= url('/api/newsletter') ?>" method="POST">
      <?= csrf_field() ?>
      <!-- Honeypot for bot detection -->
      <input type="text" name="website_hp" style="display:none;" tabindex="-1" autocomplete="off">
      <input type="email" name="email" class="newsletter-input" placeholder="Enter your email address" required aria-label="Your email address">
      <button type="submit" class="btn btn-dark">Subscribe</button>
    </form>

    <div class="newsletter-success-msg" role="alert">
      Welcome to the Club — check your inbox.
    </div>
  </div>
</section>
