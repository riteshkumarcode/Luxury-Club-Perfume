<?php
// app/views/admin/login.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — Luxury Club</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('/assets/css/app.css') ?>">
</head>
<body style="background: #0D0B08; color: #F4EEE2; min-height: 100vh; display:flex; align-items:center; justify-content:center; padding: 24px;">

  <div style="max-width: 420px; width: 100%; background: #17130E; border: 1px solid rgba(226, 217, 201, 0.15); border-radius: var(--radius-panel); padding: 44px 36px; box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
    
    <div style="text-align: center; margin-bottom: 32px;">
      <div style="color: var(--gold); margin-bottom: 8px;"><?= icon('crown') ?></div>
      <div style="font-family: var(--font-serif); font-size: 24px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--cream);">
        Luxury Club
      </div>
      <div style="font-size: 11px; letter-spacing: 0.1em; color: var(--gold); text-transform: uppercase; margin-top: 4px;">
        Atelier Management Portal
      </div>
    </div>

    <?php if ($err = flash('error')): ?>
      <div style="background: rgba(163, 38, 28, 0.2); border: 1px solid var(--error); color: #ff8a80; padding: 12px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; text-align:center;">
        <?= e($err) ?>
      </div>
    <?php endif; ?>

    <?php if ($succ = flash('success')): ?>
      <div style="background: rgba(47, 93, 42, 0.2); border: 1px solid var(--success); color: #a5d6a7; padding: 12px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; text-align:center;">
        <?= e($succ) ?>
      </div>
    <?php endif; ?>

    <form action="<?= url('/admin/login') ?>" method="POST">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label" style="color: var(--muted-dark);">Email Address</label>
        <input type="email" name="email" class="form-input" placeholder="admin@luxuryclub.com" required style="background: #0D0B08; border-color: rgba(226, 217, 201, 0.2); color: var(--cream);">
      </div>

      <div class="form-group" style="margin-bottom: 28px;">
        <label class="form-label" style="color: var(--muted-dark);">Password</label>
        <input type="password" name="password" class="form-input" placeholder="••••••••" required style="background: #0D0B08; border-color: rgba(226, 217, 201, 0.2); color: var(--cream);">
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="padding: 16px;">
        Sign In to Atelier <?= icon('arrow-right') ?>
      </button>

      <div style="text-align: center; margin-top: 24px;">
        <a href="<?= url('/') ?>" style="font-size: 12px; color: var(--muted-dark); text-decoration: underline;">
          &larr; Return to Boutique
        </a>
      </div>
    </form>

  </div>

</body>
</html>
