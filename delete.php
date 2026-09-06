<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    flash_set('error', 'الحذف يتم عبر زر الحذف في السجل فقط.');
    redirect('index.php');
}
if (!csrf_verify()) {
    flash_set('error', 'انتهت صلاحية الجلسة. أعد تحميل الصفحة وحاول مرة أخرى.');
    redirect('index.php');
}
$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    flash_set('error', 'معرّف الأصل غير صالح.');
    redirect('index.php');
}
$asset = asset_find($id);
if ($asset === null) {
    flash_set('error', 'السجل غير موجود.');
    redirect('index.php');
}
try {
    asset_delete($id);
    flash_set('success', 'حُذف الأصل «' . $asset['name'] . '».');
} catch (PDOException $ex) {
    error_log('asset_delete: ' . $ex->getMessage());
    flash_set('error', 'تعذّر حذف السجل.');
}
redirect('index.php');
