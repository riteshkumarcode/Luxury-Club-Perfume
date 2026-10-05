<?php
// app/views/pages/policy.php
use App\Core\View;

/** @var string $slug */
/** @var string $title */
/** @var string $contentBody */
?>

<section class="section section-dark" style="padding-top: 70px; padding-bottom: 60px;">
  <div class="container">
    <?= View::partial('partials/breadcrumb', ['items' => [
      ['label' => 'Home', 'url' => '/'],
      ['label' => 'Client Care', 'url' => '/policies/shipping'],
      ['label' => $title]
    ]]) ?>
    <span class="eyebrow eyebrow-light">CLIENT POLICIES</span>
    <h1 style="color: var(--cream); font-size: 40px;"><?= e($title) ?></h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <div style="display: grid; grid-template-columns: 260px 1fr; gap: 48px; align-items: flex-start;">
      
      <!-- Sticky Table of Contents -->
      <div style="position: sticky; top: 100px; background: var(--sand); border: 1px solid var(--line); border-radius: 18px; padding: 24px;">
        <h4 style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 16px;">
          Policies & Terms
        </h4>
        <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 14px;">
          <li>
            <a href="<?= url('/policies/shipping') ?>" style="color: <?= $slug === 'shipping' ? 'var(--gold-text); font-weight: 700;' : 'var(--muted);' ?>">
              Shipping & Delivery
            </a>
          </li>
          <li>
            <a href="<?= url('/policies/returns') ?>" style="color: <?= $slug === 'returns' ? 'var(--gold-text); font-weight: 700;' : 'var(--muted);' ?>">
              Returns & Exchanges
            </a>
          </li>
          <li>
            <a href="<?= url('/policies/privacy') ?>" style="color: <?= $slug === 'privacy' ? 'var(--gold-text); font-weight: 700;' : 'var(--muted);' ?>">
              Privacy Policy
            </a>
          </li>
          <li>
            <a href="<?= url('/policies/terms') ?>" style="color: <?= $slug === 'terms' ? 'var(--gold-text); font-weight: 700;' : 'var(--muted);' ?>">
              Terms of Service
            </a>
          </li>
        </ul>
      </div>

      <!-- Policy Content -->
      <div style="background: #ffffff; border: 1px solid var(--line); border-radius: var(--radius-card); padding: 40px; line-height: 1.8; color: var(--ink-2);">
        <h2 style="font-size: 28px; margin-bottom: 24px;"><?= e($title) ?></h2>
        <div style="font-size: 15px; color: var(--muted); white-space: pre-wrap;">
          <?= e($contentBody) ?>
        </div>
      </div>

    </div>
  </div>
</section>
