<?php
// app/views/partials/announcement.php
$rawMessages = get_setting('announcement_messages', '["COMPLIMENTARY SHIPPING ON ORDERS OVER ₹999", "ARTISANAL ALCOHOL-FREE ATTARS & PURE PARFUMS", "EXQUISITE GIFT BOX PACKAGING WITH EVERY ORDER", "DISPATCHED WITHIN 24 HOURS ACROSS INDIA"]');
$messages = json_decode($rawMessages, true) ?: [
    "COMPLIMENTARY SHIPPING ON ORDERS OVER ₹999",
    "ARTISANAL ALCOHOL-FREE ATTARS & PURE PARFUMS",
    "DISPATCHED WITHIN 24 HOURS ACROSS INDIA"
];
?>
<div class="announcement-bar" role="region" aria-label="Announcement">
  <div class="announcement-track">
    <?php for ($i = 0; $i < 4; $i++): // Duplicate for continuous loop ?>
      <?php foreach ($messages as $msg): ?>
        <span class="announcement-item"><?= e($msg) ?></span>
      <?php endforeach; ?>
    <?php endfor; ?>
  </div>
</div>
