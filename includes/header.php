<?php
declare(strict_types=1);
$pageTitle = $pageTitle ?? 'سجل الأصول';

$navItems = [
    'index.php'  => 'السجل',
    'create.php' => 'إضافة أصل',
    'export.php' => 'تصدير',
];
$user = auth_user();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?></title>
<link rel="icon" href="assets/favicon.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;700&display=swap">
<link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<a class="skip" href="#main">تخطّي إلى المحتوى</a>
<header class="site-header">
  <div class="wrap">
    <a class="brand" href="index.php">سجل الأصول</a>
    <nav class="nav" aria-label="التنقّل الرئيسي">
      <ul>
        <?php foreach ($navItems as $file => $label): ?>
          <li><a href="<?= e($file) ?>"<?= is_current($file) ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
        <?php endforeach; ?>
        <?php if ($user): ?>
          <li class="nav-user">
            <span><?= e($user) ?></span>
            <a href="logout.php">خروج</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>
<main id="main" class="wrap section">
