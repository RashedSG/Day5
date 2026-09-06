<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$f = assets_filters_from_request();
$rows = assets_all($f);
$filename = 'assets-' . date('Ymd-His') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('X-Content-Type-Options: nosniff');
$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['id', 'name', 'code', 'category', 'status', 'location', 'purchase_date', 'notes', 'created_at']);
foreach ($rows as $row) {
    fputcsv($out, [
        $row['id'], $row['name'], $row['code'],
        category_label($row['category']), status_label($row['status']),
        $row['location'], $row['purchase_date'], $row['notes'], $row['created_at'],
    ]);
}
exit;
