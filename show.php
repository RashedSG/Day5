<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    flash_set('error', 'معرّف الأصل غير صالح.');
    redirect('index.php');
}
$asset = asset_find($id);
if ($asset === null) {
    http_response_code(404);
    flash_set('error', 'لا يوجد أصل بهذا المعرّف.');
    redirect('index.php');
}
$pageTitle = $asset['name'];
$flash = flash_get();
require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
  <h1><?= e($asset['name']) ?></h1>
  <p><a href="index.php">العودة إلى السجل</a></p>
</div>
<?php if ($flash): ?>
  <div class="banner is-<?= e($flash['type']) ?>"><p><?= e($flash['message']) ?></p></div>
<?php endif; ?>
<div class="card form-narrow">
  <dl class="dl">
    <dt>الرمز</dt>
    <dd dir="ltr"><?= e($asset['code'] !== '' ? $asset['code'] : '—') ?></dd>
    <dt>الفئة</dt>
    <dd><span class="tag"><?= e(category_label($asset['category'])) ?></span></dd>
    <dt>الحالة</dt>
    <dd><span class="tag is-<?= e($asset['status']) ?>"><?= e(status_label($asset['status'])) ?></span></dd>
    <dt>الموقع</dt>
    <dd><?= e($asset['location'] !== '' ? $asset['location'] : '—') ?></dd>
    <dt>تاريخ الشراء</dt>
    <dd dir="ltr"><?= e($asset['purchase_date']) ?></dd>
    <dt>أُضيف في</dt>
    <dd dir="ltr"><?= e((string)$asset['created_at']) ?></dd>
    <dt>ملاحظات</dt>
    <dd><?= $asset['notes'] !== '' ? nl2br(e($asset['notes'])) : '—' ?></dd>
  </dl>
  <div class="actions">
    <a class="btn" href="edit.php?id=<?= (int)$asset['id'] ?>">تعديل</a>
    <form action="delete.php" method="post" class="inline-form"
          onsubmit="return confirm('حذف «<?= e($asset['name']) ?>» نهائياً؟');">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$asset['id'] ?>">
      <button class="btn btn-danger" type="submit">حذف</button>
    </form>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
