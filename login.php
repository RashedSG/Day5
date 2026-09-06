<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

if (auth_user()) {
    redirect('index.php');
}

$pageTitle = 'دخول';
$errors = [];
$user = AUTH_USER;
$next = (string)($_GET['next'] ?? $_POST['next'] ?? 'index.php');
if ($next === '' || str_contains($next, '://') || str_starts_with($next, '//') || str_contains($next, '..')) {
    $next = 'index.php';
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'انتهت صلاحية الجلسة. أعد المحاولة.';
    } else {
        $user = trim((string)($_POST['user'] ?? ''));
        $pass = (string)($_POST['pass'] ?? '');
        if (!auth_attempt($user, $pass)) {
            $errors[] = 'اسم المستخدم أو كلمة المرور غير صحيحة.';
        } else {
            redirect($next);
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="login-wrap">
  <div class="page-head">
    <h1>دخول سجل الأصول</h1>
    <p>أدخل حساب المشرف للوصول إلى السجلات.</p>
  </div>

  <?php if ($errors): ?>
    <div class="banner is-error">
      <?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="login.php" method="post" class="card" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="next" value="<?= e($next) ?>">
    <div class="field">
      <label for="user">اسم المستخدم</label>
      <input type="text" id="user" name="user" autocomplete="username" value="<?= e($user) ?>">
    </div>
    <div class="field">
      <label for="pass">كلمة المرور</label>
      <input type="password" id="pass" name="pass" autocomplete="current-password">
      <p class="hint">للتجربة: admin / admin123</p>
    </div>
    <div class="actions">
      <button class="btn" type="submit">دخول</button>
    </div>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
