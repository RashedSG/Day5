<?php
declare(strict_types=1);

function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function len(string $s): int
{
    return function_exists('mb_strlen') ? mb_strlen($s, 'UTF-8') : strlen($s);
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): bool
{
    $sent = (string)($_POST['csrf'] ?? '');
    return $sent !== '' && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $sent);
}

function redirect(string $path): void
{
    header('Location: ' . $path, true, 303);
    exit;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function is_current(string $file): bool
{
    return basename($_SERVER['SCRIPT_NAME'] ?? '') === $file;
}

function validate_asset(array $input): array
{
    $errors = [];
    $name = trim((string)($input['name'] ?? ''));
    if ($name === '') {
        $errors['name'] = 'اسم الأصل مطلوب.';
    } elseif (len($name) < 2) {
        $errors['name'] = 'الاسم قصير جداً.';
    } elseif (len($name) > 120) {
        $errors['name'] = 'الاسم طويل جداً.';
    }
    $code = trim((string)($input['code'] ?? ''));
    if ($code !== '' && len($code) > 40) {
        $errors['code'] = 'رمز الأصل طويل جداً.';
    }
    $category = trim((string)($input['category'] ?? ''));
    if ($category === '') {
        $errors['category'] = 'اختر الفئة.';
    } elseif (!isset(CATEGORIES[$category])) {
        $errors['category'] = 'فئة غير معروفة.';
    }
    $status = trim((string)($input['status'] ?? ''));
    if ($status === '') {
        $errors['status'] = 'اختر الحالة.';
    } elseif (!isset(STATUSES[$status])) {
        $errors['status'] = 'حالة غير معروفة.';
    }
    $location = trim((string)($input['location'] ?? ''));
    if (len($location) > 80) {
        $errors['location'] = 'الموقع طويل جداً.';
    }
    $date = trim((string)($input['purchase_date'] ?? ''));
    if ($date === '') {
        $errors['purchase_date'] = 'تاريخ الشراء مطلوب.';
    } elseif (!is_valid_date($date)) {
        $errors['purchase_date'] = 'صيغة التاريخ غير صحيحة (YYYY-MM-DD).';
    } elseif ($date > date('Y-m-d')) {
        $errors['purchase_date'] = 'تاريخ الشراء لا يكون في المستقبل.';
    }
    $notes = trim((string)($input['notes'] ?? ''));
    if (len($notes) > 500) {
        $errors['notes'] = 'الملاحظات أطول من ٥٠٠ حرف.';
    }
    return $errors;
}

function is_valid_date(string $value): bool
{
    $date = DateTime::createFromFormat('Y-m-d', $value);
    return $date !== false && $date->format('Y-m-d') === $value;
}

function category_label(string $slug): string
{
    return CATEGORIES[$slug] ?? $slug;
}

function status_label(string $slug): string
{
    return STATUSES[$slug] ?? $slug;
}

function asset_input_from(array $src): array
{
    return [
        'name'          => trim((string)($src['name'] ?? '')),
        'code'          => trim((string)($src['code'] ?? '')),
        'category'      => trim((string)($src['category'] ?? '')),
        'status'        => trim((string)($src['status'] ?? 'in_use')),
        'location'      => trim((string)($src['location'] ?? '')),
        'purchase_date' => trim((string)($src['purchase_date'] ?? '')),
        'notes'         => trim((string)($src['notes'] ?? '')),
    ];
}
