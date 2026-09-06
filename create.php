<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'إضافة أصل';
$errors = [];
$old = [
    'name' => '', 'code' => '', 'category' => '', 'status' => 'in_use',
    'location' => '', 'purchase_date' => '', 'notes' => '',
];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'انتهت صلاحية الجلسة. أعد تحميل الصفحة وحاول مرة أخرى.';
    }
    $old = asset_input_from($_POST);
    $errors += validate_asset($old);
    if (!$errors) {
        try {
            asset_insert($old);
            flash_set('success', 'أُضيف الأصل «' . $old['name'] . '» إلى السجل.');
            redirect('index.php');
        } catch (PDOException $ex) {
            error_log('asset_insert: ' . $ex->getMessage());
            $errors[] = 'تعذّر حفظ السجل. حاول مرة أخرى.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
  <h1>إضافة أصل</h1>
  <p><a href="index.php">العودة إلى السجل</a></p>
</div>
<?php
$general = array_filter($errors, 'is_int', ARRAY_FILTER_USE_KEY);
if ($general): ?>
  <div class="banner is-error">
    <?php foreach ($general as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
  </div>
<?php elseif ($errors): ?>
  <div class="banner is-error"><p>راجع الحقول المعلّمة أدناه.</p></div>
<?php endif; ?>
<form action="create.php" method="post" class="card form-narrow" novalidate>
  <?= csrf_field() ?>
  <?php require __DIR__ . '/includes/form_fields.php'; ?>
  <div class="actions">
    <button class="btn" type="submit">احفظ الأصل</button>
    <a class="btn btn-ghost" href="index.php">إلغاء</a>
  </div>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
