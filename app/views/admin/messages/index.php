<?php
// app/views/admin/messages/index.php
/** @var array $messages */
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <h3 style="font-size: 20px;">Client Inquiries (<?= count($messages) ?>)</h3>
</div>

<div class="admin-card">
  <?php if (empty($messages)): ?>
    <p style="text-align: center; padding: 40px; color: var(--muted);">No messages in inbox.</p>
  <?php else: ?>
    <div style="display: flex; flex-direction: column; gap: 16px;">
      <?php foreach ($messages as $msg): ?>
        <div style="border: 1px solid var(--line); border-radius: 14px; padding: 20px; background: <?= $msg['is_read'] ? '#ffffff' : 'rgba(216, 178, 92, 0.05)' ?>;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
            <div>
              <strong><?= e($msg['name']) ?></strong> 
              <span style="font-size: 13px; color: var(--muted);">&lt;<?= e($msg['email']) ?>&gt;</span>
              <?php if (!empty($msg['phone'])): ?>
                <span style="font-size: 13px; color: var(--muted);">· <?= e($msg['phone']) ?></span>
              <?php endif; ?>
              <div style="font-size: 12px; font-weight: 700; color: var(--gold-text); margin-top: 2px;">
                Topic: <?= e($msg['topic']) ?>
              </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
              <span style="font-size: 12px; color: var(--muted);"><?= date('d M Y, H:i', strtotime($msg['created_at'])) ?></span>
              <?php if (!$msg['is_read']): ?>
                <form action="<?= url('/admin/messages/' . $msg['id'] . '/read') ?>" method="POST" style="display:inline;">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 3px 8px;">Mark Read</button>
                </form>
              <?php endif; ?>
              <a href="mailto:<?= e($msg['email']) ?>?subject=Re: <?= rawurlencode($msg['topic']) ?>" class="btn btn-sm btn-dark" style="font-size: 11px; padding: 3px 10px;">
                Reply &rarr;
              </a>
              <form action="<?= url('/admin/messages/' . $msg['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete message?');" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm" style="color: var(--error); padding: 3px 6px;">
                  <?= icon('trash') ?>
                </button>
              </form>
            </div>
          </div>
          <div style="font-size: 14px; line-height: 1.6; color: var(--ink-2); white-space: pre-wrap; background: var(--sand); padding: 14px; border-radius: 10px;">
            <?= e($msg['message']) ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
