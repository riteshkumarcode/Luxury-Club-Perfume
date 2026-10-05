<?php
// app/views/pages/404.php
?>
<section class="section section-dark" style="min-height: 70vh; display:flex; align-items:center; text-align:center; position:relative; overflow:hidden;">
  
  <div class="hero-outline-word" style="opacity: 0.08;" aria-hidden="true">
    404
  </div>

  <div class="container" style="position: relative; z-index: 2; max-width: 600px;">
    <div class="eyebrow eyebrow-light">PAGE NOT FOUND</div>
    <h1 style="font-size: clamp(48px, 8vw, 84px); color: var(--cream); margin-bottom: 16px;">
      This scent has <br><em style="font-style: italic; color: var(--gold);">drifted away</em>
    </h1>
    <p style="color: var(--muted-dark); font-size: 16px; margin-bottom: 36px;">
      The fragrance note or boutique page you are searching for does not exist, has been retired, or has relocated.
    </p>
    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
      <a href="<?= url('/shop') ?>" class="btn btn-primary" data-magnetic>
        Explore All Fragrances <?= icon('arrow-right') ?>
      </a>
      <a href="<?= url('/') ?>" class="btn btn-outline-light" data-magnetic>
        Return to Home
      </a>
    </div>
  </div>
</section>
