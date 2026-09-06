<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'تعديل أصل';
$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

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

$errors = [];
$old = asset_input_from($asset);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'انتهت صلاحية الجلسة. أعد تحميل الصفحة وحاول مرة أخرى.';
    }
    $old = asset_input_from($_POST);
    $errors += validate_asset($old);
    if (!$errors) {
        try {
            asset_update($id, $old);
            flash_set('success', 'حُدّث الأصل «' . $old['name'] . '».');
            redirect('show.php?id=' . $id);
        } catch (PDOException $ex) {
            error_log('asset_update: ' . $ex->getMessage());
            $errors[] = 'تعذّر حفظ التعديل. حاول مرة أخرى.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
  <h1>تعديل أصل</h1>
  <p><a href="show.php?id=<?= $id ?>">عرض السجل</a> · <a href="index.php">السجل</a></p>
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
<form action="edit.php" method="post" class="card form-narrow" novalidate>
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= $id ?>">
  <?php require __DIR__ . '/includes/form_fields.php'; ?>
  <div class="actions">
    <button class="btn" type="submit">احفظ التعديل</button>
    <a class="btn btn-ghost" href="show.php?id=<?= $id ?>">إلغاء</a>
  </div>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
