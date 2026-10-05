<?php
// app/views/layouts/admin.php
use App\Core\Session;
use App\Models\Order;

$adminUser = Session::get('admin_user_name', 'Admin');
$stats = Order::getStats();
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Admin Panel — Luxury Club') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('/assets/css/app.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/admin.css') ?>">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
</head>
<body class="admin-body">

  <!-- Admin Dark Sidebar -->
  <aside class="admin-sidebar">
    <div class="admin-sidebar-brand">
      <div style="color: var(--gold);"><?= icon('crown') ?></div>
      <div class="admin-brand-text">Luxury Club</div>
    </div>

    <ul class="admin-nav">
      <li>
        <a href="<?= url('/admin') ?>" class="admin-nav-link <?= $currentUri === '/admin' ? 'active' : '' ?>">
          <?= icon('sparkle') ?> Dashboard
        </a>
      </li>
      <li>
        <a href="<?= url('/admin/products') ?>" class="admin-nav-link <?= str_starts_with($currentUri, '/admin/products') ? 'active' : '' ?>">
          <?= icon('bag') ?> Products
        </a>
      </li>
      <li>
        <a href="<?= url('/admin/categories') ?>" class="admin-nav-link <?= str_starts_with($currentUri, '/admin/categories') ? 'active' : '' ?>">
          <?= icon('filter') ?> Categories
        </a>
      </li>
      <li>
        <a href="<?= url('/admin/slides') ?>" class="admin-nav-link <?= str_starts_with($currentUri, '/admin/slides') ? 'active' : '' ?>">
          <?= icon('star') ?> Hero Slides
        </a>
      </li>
      <li>
        <a href="<?= url('/admin/orders') ?>" class="admin-nav-link <?= str_starts_with($currentUri, '/admin/orders') ? 'active' : '' ?>">
          <?= icon('truck') ?> Orders
          <?php if ($stats['today_orders'] > 0): ?>
            <span class="badge-count" style="position:static; margin-left:auto; width:auto; padding:2px 8px; border-radius:999px;"><?= $stats['today_orders'] ?> new</span>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a href="<?= url('/admin/messages') ?>" class="admin-nav-link <?= str_starts_with($currentUri, '/admin/messages') ? 'active' : '' ?>">
          <?= icon('user') ?> Inquiries
          <?php if ($stats['unread_messages'] > 0): ?>
            <span class="badge-count" style="position:static; margin-left:auto; width:auto; padding:2px 8px; border-radius:999px;"><?= $stats['unread_messages'] ?></span>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a href="<?= url('/admin/subscribers') ?>" class="admin-nav-link <?= str_starts_with($currentUri, '/admin/subscribers') ? 'active' : '' ?>">
          <?= icon('gift') ?> Subscribers
        </a>
      </li>
      <li>
        <a href="<?= url('/admin/settings') ?>" class="admin-nav-link <?= str_starts_with($currentUri, '/admin/settings') ? 'active' : '' ?>">
          <?= icon('shield') ?> Settings
        </a>
      </li>
    </ul>

    <div class="admin-sidebar-footer">
      <div style="font-size: 11px; color: var(--muted-dark); margin-bottom: 8px;">Logged in as:</div>
      <div style="font-size: 13px; font-weight: 700; color: var(--cream); margin-bottom: 12px;"><?= e($adminUser) ?></div>
      <div style="display:flex; justify-content:space-between;">
        <a href="<?= url('/') ?>" target="_blank" style="font-size: 12px; color: var(--gold); display:flex; align-items:center; gap:4px;">
          View Store &rarr;
        </a>
        <a href="<?= url('/admin/logout') ?>" style="font-size: 12px; color: var(--error);">
          Logout
        </a>
      </div>
    </div>
  </aside>

  <!-- Admin Main Content Area -->
  <div class="admin-main">
    
    <header class="admin-header">
      <h2 style="font-size: 24px; font-weight: 600;"><?= e($pageTitle ?? 'Dashboard') ?></h2>
      <div style="display: flex; gap: 16px; align-items: center;">
        <a href="<?= url('/') ?>" target="_blank" class="btn btn-sm btn-outline">
          <?= icon('eye') ?> Live Boutique
        </a>
      </div>
    </header>

    <div class="admin-content">
      
      <!-- Flash Alert Messages -->
      <?php if ($success = flash('success')): ?>
        <div style="background: rgba(47, 93, 42, 0.12); border: 1px solid var(--success); color: var(--success); padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 600;">
          ✓ <?= e($success) ?>
        </div>
      <?php endif; ?>

      <?php if ($error = flash('error')): ?>
        <div style="background: rgba(163, 38, 28, 0.12); border: 1px solid var(--error); color: var(--error); padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 600;">
          ⚠ <?= e($error) ?>
        </div>
      <?php endif; ?>

      <?= $content ?>

    </div>

  </div>

</body>
</html>
