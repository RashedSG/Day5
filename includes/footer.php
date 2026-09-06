<?php declare(strict_types=1); ?>
</main>
<footer class="site-footer">
  <div class="wrap">
    <p>نظام سجلات الأصول والمعدّات &mdash; <?= date('Y') ?></p>
    <?php if (DEBUG): ?>
      <p class="debug-note">وضع التطوير مفعّل. اجعل DEBUG = false قبل النشر.</p>
    <?php endif; ?>
  </div>
</footer>
</body>
</html>
