<?php
// app/views/admin/subscribers.php
/** @var array $subscribers */
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <h3 style="font-size: 20px;">Subscribers (<?= count($subscribers) ?>)</h3>
  <a href="<?= url('/admin/subscribers/export') ?>" class="btn btn-primary btn-sm">
    <?= icon('download') ?> Export to CSV
  </a>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Subscriber Email</th>
        <th>Subscribed Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($subscribers as $sub): ?>
        <tr>
          <td>#<?= $sub['id'] ?></td>
          <td><strong><?= e($sub['email']) ?></strong></td>
          <td style="font-size: 13px; color: var(--muted);"><?= date('d M Y, H:i', strtotime($sub['created_at'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
